#!/usr/bin/env php
<?php

/**
 * Environment Detection Script for Drupal AGENTS.md Generator
 *
 * Detects:
 * - Development environment (DDEV, Lando, Docker Compose, or Vanilla)
 * - Drupal version from composer.json
 * - PHP version requirements
 *
 * Outputs JSON with detected values.
 */

// Get project root (assume script is run from project root or skill directory)
// Strategy: Walk up the directory tree until we find composer.json (project root indicator)
$projectRoot = getcwd();
$maxDepth = 10; // Prevent infinite loops
$depth = 0;

while ($depth < $maxDepth) {
    // Check if we're at the project root (has composer.json)
    if (file_exists($projectRoot . '/composer.json')) {
        break;
    }
    
    // If we're in scripts directory, go up one level
    if (basename($projectRoot) === 'scripts') {
        $projectRoot = dirname($projectRoot);
        continue;
    }
    
    // If we're in the skill directory, go up one level
    if (basename($projectRoot) === 'drupal-agents-generator') {
        $projectRoot = dirname($projectRoot);
        continue;
    }
    
    // Otherwise, go up one level
    $parent = dirname($projectRoot);
    if ($parent === $projectRoot) {
        // We've reached the filesystem root, stop
        break;
    }
    $projectRoot = $parent;
    $depth++;
}

$detected = [
    'environment' => 'vanilla',
    'drupal_version' => null,
    'php_version' => null,
    'detection_method' => [],
];

// Detect development environment
if (is_dir($projectRoot . '/.ddev') || file_exists($projectRoot . '/.ddev/config.yaml')) {
    $detected['environment'] = 'ddev';
    $detected['detection_method'][] = 'Found .ddev directory or config.yaml';

    // Try to read PHP version from DDEV config
    $ddevConfig = $projectRoot . '/.ddev/config.yaml';
    if (file_exists($ddevConfig)) {
        $configContent = file_get_contents($ddevConfig);
        if (preg_match('/php_version:\s*["\']?([0-9.]+)["\']?/', $configContent, $matches)) {
            $detected['php_version'] = $matches[1];
        }
    }
} elseif (file_exists($projectRoot . '/.lando.yml')) {
    $detected['environment'] = 'lando';
    $detected['detection_method'][] = 'Found .lando.yml';

    // Try to read PHP version from Lando config
    $landoConfig = $projectRoot . '/.lando.yml';
    if (file_exists($landoConfig)) {
        $configContent = file_get_contents($landoConfig);
        if (preg_match('/php:\s*["\']?([0-9.]+)["\']?/', $configContent, $matches)) {
            $detected['php_version'] = $matches[1];
        }
    }
} else {
    // Check for Docker Compose files in priority order (per compose-spec)
    $composeFiles = ['compose.yaml', 'compose.yml', 'docker-compose.yaml', 'docker-compose.yml'];
    $composeFile = null;
    $composeFileName = null;

    foreach ($composeFiles as $file) {
        if (file_exists($projectRoot . '/' . $file)) {
            $composeFile = $projectRoot . '/' . $file;
            $composeFileName = $file;
            break;
        }
    }

    if ($composeFile) {
        $detected['environment'] = 'docker-compose';
        $detected['detection_method'][] = 'Found ' . $composeFileName;

        // Initialize Docker Compose specific fields with defaults
        $detected['docker_compose'] = [
            'compose_command' => 'docker compose',
            'compose_file' => $composeFileName,
            'web_service' => 'web',
            'db_service' => 'db',
            'db_name' => 'drupal',
            'db_user' => 'drupal',
            'db_password' => 'drupal',
            'db_host' => 'db',
        ];

        // Detect which compose command is available (V2: docker compose, V1: docker-compose)
        $output = [];
        $return = 0;
        @exec('docker compose version 2>/dev/null', $output, $return);
        if ($return === 0) {
            $detected['docker_compose']['compose_command'] = 'docker compose';
            $detected['detection_method'][] = 'Detected Docker Compose V2 (docker compose)';
        } else {
            @exec('docker-compose version 2>/dev/null', $output, $return);
            if ($return === 0) {
                $detected['docker_compose']['compose_command'] = 'docker-compose';
                $detected['detection_method'][] = 'Detected Docker Compose V1 (docker-compose)';
            } else {
                // Fallback to V2 as default
                $detected['docker_compose']['compose_command'] = 'docker compose';
                $detected['detection_method'][] = 'Assuming Docker Compose V2 (docker compose) as default';
            }
        }

        // Parse compose file if YAML parser is available
        if (function_exists('yaml_parse_file')) {
            $composeData = @yaml_parse_file($composeFile);
            if ($composeData && isset($composeData['services']) && is_array($composeData['services'])) {
                $services = $composeData['services'];

                // First, detect database service to help identify web service
                $dbServiceName = null;
                foreach (['db', 'database', 'mysql', 'mariadb', 'postgres', 'postgresql'] as $pattern) {
                    if (isset($services[$pattern])) {
                        $dbServiceName = $pattern;
                        $detected['docker_compose']['db_service'] = $pattern;
                        $detected['docker_compose']['db_host'] = $pattern;
                        $dbService = $services[$pattern];

                        // Extract database credentials from environment variables
                        if (isset($dbService['environment'])) {
                            $env = $dbService['environment'];
                            if (is_array($env)) {
                                foreach ($env as $key => $value) {
                                    if (is_string($key)) {
                                        $envKey = $key;
                                        $envValue = $value;
                                    } else {
                                        // Handle "KEY=value" format
                                        if (preg_match('/^(\w+)=(.+)$/', $value, $matches)) {
                                            $envKey = $matches[1];
                                            $envValue = $matches[2];
                                        } else {
                                            continue;
                                        }
                                    }

                                    $envKeyUpper = strtoupper($envKey);
                                    if ($envKeyUpper === 'MYSQL_DATABASE' || $envKeyUpper === 'POSTGRES_DB') {
                                        $detected['docker_compose']['db_name'] = $envValue;
                                    } elseif ($envKeyUpper === 'MYSQL_USER' || $envKeyUpper === 'POSTGRES_USER') {
                                        $detected['docker_compose']['db_user'] = $envValue;
                                    } elseif ($envKeyUpper === 'MYSQL_PASSWORD' || $envKeyUpper === 'POSTGRES_PASSWORD') {
                                        $detected['docker_compose']['db_password'] = $envValue;
                                    }
                                }
                            }
                        }

                        break;
                    }
                }

                // Detect web service with multiple strategies
                $webServiceName = null;
                
                // Strategy 1: Check common service name patterns
                $webPatterns = ['web', 'php', 'app', 'drupal', 'nginx', 'apache', 'www', 'frontend', 'httpd', 'php-fpm'];
                foreach ($webPatterns as $pattern) {
                    if (isset($services[$pattern])) {
                        $webServiceName = $pattern;
                        break;
                    }
                }
                
                // Strategy 2: If not found, use intelligent heuristics
                if (!$webServiceName) {
                    foreach ($services as $serviceName => $serviceConfig) {
                        // Skip if this is the database service
                        if ($serviceName === $dbServiceName) {
                            continue;
                        }
                        
                        $isWebService = false;
                        
                        // Check for web-related ports (80, 8080, 443, 8443)
                        if (isset($serviceConfig['ports'])) {
                            $ports = is_array($serviceConfig['ports']) ? $serviceConfig['ports'] : [$serviceConfig['ports']];
                            foreach ($ports as $port) {
                                $portStr = is_string($port) ? $port : (is_array($port) ? ($port['published'] ?? '') : '');
                                if (preg_match('/:?(80|8080|443|8443)(:|\/|$)/', $portStr)) {
                                    $isWebService = true;
                                    break;
                                }
                            }
                        }
                        
                        // Check for web root volumes (common Drupal paths)
                        if (!$isWebService && isset($serviceConfig['volumes'])) {
                            $volumes = is_array($serviceConfig['volumes']) ? $serviceConfig['volumes'] : [$serviceConfig['volumes']];
                            foreach ($volumes as $volume) {
                                $volumeStr = is_string($volume) ? $volume : (is_array($volume) ? ($volume['source'] ?? '') : '');
                                if (preg_match('/(web|html|www|docroot|public)/i', $volumeStr)) {
                                    $isWebService = true;
                                    break;
                                }
                            }
                        }
                        
                        // Check if service depends on database (web services typically do)
                        if (!$isWebService && isset($serviceConfig['depends_on'])) {
                            $dependsOn = is_array($serviceConfig['depends_on']) ? $serviceConfig['depends_on'] : [$serviceConfig['depends_on']];
                            if ($dbServiceName && in_array($dbServiceName, $dependsOn)) {
                                $isWebService = true;
                            }
                        }
                        
                        // Check for web server images
                        if (!$isWebService && isset($serviceConfig['image'])) {
                            $image = is_string($serviceConfig['image']) ? $serviceConfig['image'] : '';
                            if (preg_match('/(nginx|apache|httpd|php|drupal|wordpress)/i', $image)) {
                                $isWebService = true;
                            }
                        }
                        
                        if ($isWebService) {
                            $webServiceName = $serviceName;
                            break;
                        }
                    }
                }
                
                // Strategy 3: If still not found and we have a database service, 
                // pick the first service that's not the database
                if (!$webServiceName && $dbServiceName && count($services) > 1) {
                    foreach ($services as $serviceName => $serviceConfig) {
                        if ($serviceName !== $dbServiceName) {
                            $webServiceName = $serviceName;
                            break;
                        }
                    }
                }
                
                if ($webServiceName) {
                    $detected['docker_compose']['web_service'] = $webServiceName;
                    $detected['detection_method'][] = 'Detected web service: ' . $webServiceName;
                } else {
                    $detected['detection_method'][] = 'Could not detect web service, using default: web';
                }

                $detected['detection_method'][] = 'Parsed compose file to detect services and database credentials';
            }
        } else {
            // Fallback: Try to detect running containers
            $output = [];
            $return = 0;
            $composeCmd = $detected['docker_compose']['compose_command'];
            @exec($composeCmd . ' ps --services 2>/dev/null', $output, $return);
            if ($return === 0 && !empty($output)) {
                $serviceList = $output;
                
                // Detect database service first
                $dbServiceName = null;
                foreach (['db', 'database', 'mysql', 'mariadb', 'postgres', 'postgresql'] as $pattern) {
                    if (in_array($pattern, $serviceList)) {
                        $dbServiceName = $pattern;
                        $detected['docker_compose']['db_service'] = $pattern;
                        $detected['docker_compose']['db_host'] = $pattern;
                        break;
                    }
                }
                
                // Detect web service
                $webServiceName = null;
                $webPatterns = ['web', 'php', 'app', 'drupal', 'nginx', 'apache', 'www', 'frontend', 'httpd', 'php-fpm'];
                foreach ($webPatterns as $pattern) {
                    if (in_array($pattern, $serviceList)) {
                        $webServiceName = $pattern;
                        break;
                    }
                }
                
                // If not found by pattern, use first non-database service
                if (!$webServiceName && $dbServiceName && count($serviceList) > 1) {
                    foreach ($serviceList as $service) {
                        if ($service !== $dbServiceName) {
                            $webServiceName = $service;
                            break;
                        }
                    }
                } elseif (!$webServiceName && !empty($serviceList)) {
                    // Last resort: use first service
                    $webServiceName = $serviceList[0];
                }
                
                if ($webServiceName) {
                    $detected['docker_compose']['web_service'] = $webServiceName;
                    $detected['detection_method'][] = 'Detected web service from running containers: ' . $webServiceName;
                }

                $detected['detection_method'][] = 'Detected services from running containers';
            }
        }
    } else {
        $detected['environment'] = 'vanilla';
        $detected['detection_method'][] = 'No environment indicators found, assuming vanilla/traditional setup';
    }
}

// Detect Drupal version from composer.json
$composerJson = $projectRoot . '/composer.json';
if (file_exists($composerJson)) {
    $composerData = json_decode(file_get_contents($composerJson), true);

    if (isset($composerData['require']['drupal/core'])) {
        $coreVersion = $composerData['require']['drupal/core'];
        $detected['drupal_version'] = $coreVersion;
        $detected['detection_method'][] = 'Found drupal/core in composer.json: ' . $coreVersion;
    } elseif (isset($composerData['require']['drupal/core-recommended'])) {
        $coreVersion = $composerData['require']['drupal/core-recommended'];
        $detected['drupal_version'] = $coreVersion;
        $detected['detection_method'][] = 'Found drupal/core-recommended in composer.json: ' . $coreVersion;
    }

    // Extract major version (e.g., "10.2.0" -> "10", "^10.2" -> "10")
    if ($detected['drupal_version']) {
        if (preg_match('/^[~^]?(\d+)\./', $detected['drupal_version'], $matches)) {
            $detected['drupal_major_version'] = (int)$matches[1];
        }
    }

    // Detect PHP version requirement if not already set
    if (!$detected['php_version'] && isset($composerData['require']['php'])) {
        $phpRequirement = $composerData['require']['php'];
        $detected['php_version'] = $phpRequirement;
        $detected['detection_method'][] = 'Found PHP requirement in composer.json: ' . $phpRequirement;
    }
}

// Output JSON
echo json_encode($detected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
