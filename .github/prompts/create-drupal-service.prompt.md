# Prompt: Create a Drupal Service Class

## Purpose
Generate a well-structured Drupal service class following best practices, dependency injection patterns, and PHP 8.3 standards.

## Instructions

You are a Drupal 10 expert. Create a service class for `{SERVICE_NAME}` with the following requirements:

### Requirements

**Service Details:**
- **Service Name**: {SERVICE_NAME}
- **Module**: {MODULE_NAME}
- **Purpose**: {SERVICE_PURPOSE}
- **Dependencies**: {LIST_DEPENDENCIES} (e.g., database, entity_type.manager, logger)

**Class Structure:**
1. **Strict Types**: Include `declare(strict_types=1);` at the very start
2. **Namespace**: Follow Drupal convention `Drupal\{module_name}\Service\{ServiceName}`
3. **Dependencies**: Use constructor dependency injection ONLY (no \Drupal::service() calls)
4. **Methods**: Create the following methods:
   - {METHOD_NAME_1}: {METHOD_DESCRIPTION_1}
   - {METHOD_NAME_2}: {METHOD_DESCRIPTION_2}
   - (Add more as needed)

**Code Quality:**
- Add comprehensive PHPDoc blocks for class and all methods
- Use typed properties and return types for all methods
- Use typed parameters with proper type hints
- Include `@param`, `@return`, `@throws` in PHPDoc
- Use camelCase for method/property names
- Use UPPERCASE_WITH_UNDERSCORES for constants
- Use meaningful variable names
- Add comments for complex logic

**Best Practices:**
- Follow Single Responsibility Principle
- Keep class focused on one purpose
- Extract complex logic into separate methods
- Handle errors with specific exceptions
- Use logger service for logging
- Add cache support where appropriate
- Include comprehensive examples in docblocks

**File Structure:**
```php
<?php

declare(strict_types=1);

namespace Drupal\{module_name}\Service;

use [Required imports];

/**
 * Service class for {SERVICE_PURPOSE}.
 *
 * Detailed description of what this service does.
 */
class {ServiceName} {

    /**
     * Constructor.
     *
     * @param [Type] ${param}
     *   Description of the parameter.
     */
    public function __construct(
        private readonly [Type] ${param},
    ) {}

    /**
     * Method description.
     *
     * @param [Type] ${param}
     *   Parameter description.
     *
     * @return [ReturnType]
     *   Description of return value.
     *
     * @throws [ExceptionType]
     *   When something goes wrong.
     */
    public function methodName([Type] ${param}): [ReturnType] {
        // Implementation
    }
}
```

**Services.yml Entry:**
Generate a corresponding services.yml entry for registration:
```yaml
services:
  {module_name}.{service_name}:
    class: Drupal\{module_name}\Service\{ServiceName}
    arguments:
      - '@database'
      - '@entity_type.manager'
      - '@logger.channel.{module_name}'
```

### Output Requirements

1. **Complete PHP Class File** with:
   - Opening PHP tag with strict types
   - Proper namespace
   - All required imports
   - Complete class with all methods
   - Comprehensive PHPDoc documentation

2. **Services.yml Entry** - Ready to add to module's services.yml file

3. **Usage Example** - PHP code showing how to inject and use the service

4. **Key Features** - Brief explanation of what the service does

---

## Example Usage

### Input:
```
Service Name: UserProfileService
Module: user_profiles
Purpose: Manage user profile data including creation, updates, and retrieval
Dependencies: database, entity_type.manager, current_user, logger.channel.user_profiles
Methods:
  - getUserProfile(userId): Retrieve user profile by ID
  - updateUserProfile(userId, data): Update user profile information
  - deleteUserProfile(userId): Delete user profile
  - getUserProfiles(limit): Get list of user profiles
```

### Output:
```php
<?php

declare(strict_types=1);

namespace Drupal\user_profiles\Service;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Psr\Log\LoggerInterface;

/**
 * Service for managing user profile data.
 *
 * This service handles creation, updates, retrieval, and deletion
 * of user profile information. It provides methods to work with
 * user profiles in the system.
 *
 * @see \Drupal\user_profiles\Controller\UserProfileController
 */
class UserProfileService {

    /**
     * Constructor.
     *
     * @param \Drupal\Core\Database\Connection $database
     *   The database connection service.
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
     *   The entity type manager service.
     * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
     *   The current user service.
     * @param \Psr\Log\LoggerInterface $logger
     *   The logger service.
     */
    public function __construct(
        private readonly Connection $database,
        private readonly EntityTypeManagerInterface $entityTypeManager,
        private readonly AccountProxyInterface $currentUser,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Retrieves a user profile by user ID.
     *
     * @param int $userId
     *   The user ID.
     *
     * @return array|null
     *   The user profile data, or NULL if not found.
     *
     * @throws \Exception
     *   If database query fails.
     *
     * @example
     * @code
     * $profile = $userProfileService->getUserProfile(42);
     * if ($profile) {
     *   echo $profile['name'];
     * }
     * @endcode
     */
    public function getUserProfile(int $userId): ?array {
        try {
            $query = $this->database->select('user_profiles', 'up')
                ->fields('up')
                ->condition('up.uid', $userId)
                ->execute();

            $profile = $query->fetchAssoc();

            if ($profile) {
                $this->logger->info('Retrieved profile for user @uid', ['@uid' => $userId]);
            }

            return $profile;
        } catch (\Exception $e) {
            $this->logger->error('Error retrieving profile: @message', ['@message' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Updates user profile information.
     *
     * @param int $userId
     *   The user ID.
     * @param array $data
     *   The profile data to update.
     *
     * @return int
     *   Number of affected rows.
     *
     * @throws \InvalidArgumentException
     *   If user ID is invalid or data is empty.
     *
     * @example
     * @code
     * $updated = $userProfileService->updateUserProfile(42, [
     *   'name' => 'John Doe',
     *   'bio' => 'Software developer',
     * ]);
     * @endcode
     */
    public function updateUserProfile(int $userId, array $data): int {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('User ID must be positive');
        }

        if (empty($data)) {
            throw new \InvalidArgumentException('Profile data cannot be empty');
        }

        try {
            $rows = $this->database->update('user_profiles')
                ->fields($data)
                ->condition('uid', $userId)
                ->execute();

            $this->logger->info('Updated profile for user @uid', ['@uid' => $userId]);

            return $rows;
        } catch (\Exception $e) {
            $this->logger->error('Error updating profile: @message', ['@message' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Deletes a user profile.
     *
     * @param int $userId
     *   The user ID.
     *
     * @return int
     *   Number of affected rows.
     *
     * @throws \InvalidArgumentException
     *   If user ID is invalid.
     * @throws \RuntimeException
     *   If user does not have permission to delete.
     *
     * @example
     * @code
     * $deleted = $userProfileService->deleteUserProfile(42);
     * @endcode
     */
    public function deleteUserProfile(int $userId): int {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('User ID must be positive');
        }

        if (!$this->currentUser->hasPermission('delete user profiles')) {
            throw new \RuntimeException('User does not have permission to delete profiles');
        }

        try {
            $rows = $this->database->delete('user_profiles')
                ->condition('uid', $userId)
                ->execute();

            $this->logger->notice('Deleted profile for user @uid', ['@uid' => $userId]);

            return $rows;
        } catch (\Exception $e) {
            $this->logger->error('Error deleting profile: @message', ['@message' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Retrieves a list of user profiles.
     *
     * @param int $limit
     *   Maximum number of profiles to retrieve.
     * @param int $offset
     *   Number of profiles to skip.
     *
     * @return array
     *   Array of user profiles.
     *
     * @throws \InvalidArgumentException
     *   If limit or offset is negative.
     *
     * @example
     * @code
     * $profiles = $userProfileService->getUserProfiles(10, 0);
     * foreach ($profiles as $profile) {
     *   echo $profile['name'];
     * }
     * @endcode
     */
    public function getUserProfiles(int $limit = 10, int $offset = 0): array {
        if ($limit < 0 || $offset < 0) {
            throw new \InvalidArgumentException('Limit and offset must be non-negative');
        }

        try {
            $query = $this->database->select('user_profiles', 'up')
                ->fields('up')
                ->range($offset, $limit)
                ->orderBy('up.created', 'DESC')
                ->execute();

            $profiles = $query->fetchAllAssoc('uid');

            $this->logger->debug('Retrieved @count profiles', ['@count' => count($profiles)]);

            return $profiles;
        } catch (\Exception $e) {
            $this->logger->error('Error retrieving profiles: @message', ['@message' => $e->getMessage()]);
            throw $e;
        }
    }
}
```

**Services.yml Entry:**
```yaml
services:
  user_profiles.profile_service:
    class: Drupal\user_profiles\Service\UserProfileService
    arguments:
      - '@database'
      - '@entity_type.manager'
      - '@current_user'
      - '@logger.channel.user_profiles'
```

**Usage in Controller:**
```php
<?php

declare(strict_types=1);

namespace Drupal\user_profiles\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user_profiles\Service\UserProfileService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for user profile operations.
 */
class UserProfileController extends ControllerBase {

    private UserProfileService $profileService;

    /**
     * Constructor.
     *
     * @param \Drupal\user_profiles\Service\UserProfileService $profileService
     *   The user profile service.
     */
    public function __construct(UserProfileService $profileService) {
        $this->profileService = $profileService;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container): self {
        return new self(
            $container->get('user_profiles.profile_service')
        );
    }

    /**
     * Displays a user profile.
     *
     * @param int $userId
     *   The user ID.
     *
     * @return array
     *   A render array.
     */
    public function view(int $userId): array {
        $profile = $this->profileService->getUserProfile($userId);

        if (!$profile) {
            throw new \Drupal\Core\Http\Exception\NotFoundHttpException();
        }

        return [
            '#theme' => 'user_profile',
            '#profile' => $profile,
        ];
    }
}
```

---

## Customization Tips

### For Database Services:
- Use Connection service for database queries
- Always use prepared statements
- Use table names with {} syntax for Drupal compatibility
- Cache expensive queries when appropriate

### For Entity Services:
- Use EntityTypeManagerInterface for entity operations
- Load entities via storage service
- Use entity queries for complex lookups
- Handle entity access and permissions

### For Content Transformation:
- Use separate methods for each transformation
- Document input/output formats
- Use type hints for clarity
- Handle edge cases explicitly

### For External API Integration:
- Use HttpClient service for requests
- Implement proper error handling
- Add retry logic for transient failures
- Cache responses appropriately

---

## Validation Checklist

- [ ] Strict types declared
- [ ] Proper namespace used
- [ ] All imports included
- [ ] Constructor injection only
- [ ] All properties typed
- [ ] All methods have return types
- [ ] All parameters have types
- [ ] PHPDoc on class and methods
- [ ] @param, @return, @throws tags
- [ ] Usage examples provided
- [ ] Services.yml entry provided
- [ ] Error handling implemented
- [ ] Logging included
- [ ] No static method calls
- [ ] No global variable usage

