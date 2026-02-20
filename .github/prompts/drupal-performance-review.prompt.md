# Prompt: Conduct Comprehensive Drupal Performance Review

## Purpose
Analyze Drupal code for performance issues, bottlenecks, and optimization opportunities following PHP 8.3 and Drupal 10 best practices.

## Instructions

You are a Drupal 10 performance specialist. Conduct a comprehensive performance review of the provided code with the following requirements:

### Review Scope

**Code Details:**
- **Code Type**: {CODE_TYPE} (e.g., Service, Controller, Hook, Plugin, Template)
- **File Path**: {FILE_PATH}
- **Module**: {MODULE_NAME}
- **Focus Areas**: {FOCUS_AREAS} (e.g., database queries, caching, loops, memory)

**Performance Review Goals:**
1. **Identify Bottlenecks**: Find slow queries, excessive loops, memory leaks
2. **Detect N+1 Problems**: Identify repeated entity/config loads
3. **Check Caching Strategy**: Verify appropriate caching is implemented
4. **Analyze Queries**: Review database query optimization
5. **Review Loops**: Check for inefficient iteration patterns
6. **Memory Usage**: Identify potential memory issues
7. **Asset Loading**: Check for unnecessary library/asset loads
8. **Rendering Performance**: Review render array handling

**Analysis Areas:**

### 1. Database Query Performance

#### Issues to Check:
- **N+1 Queries**: Detecting repeated queries in loops
- **Inefficient Selects**: Fetching all fields when specific ones needed
- **Missing Indexes**: Conditions on unindexed fields
- **Join Performance**: Complex multi-table joins
- **Range Queries**: Inefficient pagination or limiting
- **Query Complexity**: Overly complex WHERE clauses
- **Transaction Issues**: Missing batch operations

#### Examples to Look For:

```php
// ❌ PROBLEM: N+1 Query Pattern
foreach ($nodes as $node) {
    $author = User::load($node->getOwnerId());  // Query per iteration!
    echo $author->getDisplayName();
}

// ✅ SOLUTION: Batch Load
$node_ids = array_map(function($node) { return $node->id(); }, $nodes);
$users = User::loadMultiple(array_unique($uids));
foreach ($nodes as $node) {
    echo $users[$node->getOwnerId()]->getDisplayName();
}
```

```php
// ❌ PROBLEM: Fetching All Fields
$query = $this->database->select('users', 'u')
    ->fields('u')  // Gets all columns!
    ->execute();

// ✅ SOLUTION: Select Specific Fields
$query = $this->database->select('users', 'u')
    ->fields('u', ['uid', 'name', 'mail'])
    ->execute();
```

### 2. Caching Strategy

#### Issues to Check:
- **Missing Cache Tags**: No cache invalidation strategy
- **Incorrect Cache Contexts**: Overly broad or missing contexts
- **Long Cache TTL**: Stale data served too long
- **Cache Busting**: No mechanism for cache invalidation
- **Render Array Caching**: Missing #cache metadata
- **Service Results**: Not caching expensive operations
- **Configuration Caching**: Not leveraging config cache

#### Examples to Look For:

```php
// ❌ PROBLEM: No Caching
function getExpensiveData($userId) {
    $result = $this->database->select('big_table', 'bt')
        ->condition('user_id', $userId)
        ->execute()
        ->fetchAll();
    return $result;
}

// ✅ SOLUTION: Add Caching
function getExpensiveData($userId) {
    $cacheKey = 'expensive_data:' . $userId;
    if ($cache = $this->cache->get($cacheKey)) {
        return $cache->data;
    }

    $result = $this->database->select('big_table', 'bt')
        ->condition('user_id', $userId)
        ->execute()
        ->fetchAll();

    $this->cache->set($cacheKey, $result, CacheBackendInterface::CACHE_PERMANENT, ['user:' . $userId]);
    return $result;
}
```

```php
// ❌ PROBLEM: Missing Cache Metadata in Render Array
return [
    '#theme' => 'my_template',
    '#data' => $data,
];

// ✅ SOLUTION: Add Cache Metadata
return [
    '#theme' => 'my_template',
    '#data' => $data,
    '#cache' => [
        'max-age' => 3600,
        'tags' => ['my_module_data'],
        'contexts' => ['user'],
    ],
];
```

### 3. Loop & Iteration Performance

#### Issues to Check:
- **Unnecessary Loops**: Could use array functions
- **Nested Loops**: O(n²) complexity issues
- **Loop-Based Queries**: Queries inside loops
- **Expensive Operations**: Heavy computations in loops
- **String Concatenation**: Using `.` in loops (should use array)
- **Array Operations**: Inefficient array manipulations
- **Premature Exit**: Missing break/continue statements

#### Examples to Look For:

```php
// ❌ PROBLEM: Loop-Based Queries
foreach ($ids as $id) {
    $node = Node::load($id);  // Query for each item!
    $processed[] = $node->getTitle();
}

// ✅ SOLUTION: Batch Load
$nodes = Node::loadMultiple($ids);
foreach ($nodes as $node) {
    $processed[] = $node->getTitle();
}
```

```php
// ❌ PROBLEM: String Concatenation in Loop
$output = '';
foreach ($items as $item) {
    $output .= $item->render();  // String copy each iteration!
}

// ✅ SOLUTION: Use Array
$output = [];
foreach ($items as $item) {
    $output[] = $item->render();
}
return implode('', $output);
```

### 4. Memory Usage

#### Issues to Check:
- **Unbounded Result Sets**: Loading all records without limit
- **Circular References**: Objects preventing garbage collection
- **Unnecessarily Large Arrays**: Keeping data in memory too long
- **Object Duplication**: Creating duplicate entity instances
- **Memory Leaks**: Variables not released
- **Large Batch Operations**: Processing too many items at once
- **Recursive Calls**: Stack overflow potential

#### Examples to Look For:

```php
// ❌ PROBLEM: Loading All Records
$all_users = User::loadMultiple([]);  // Loads ALL users!

// ✅ SOLUTION: Use Pagination/Limits
$users = $this->database->select('users', 'u')
    ->fields('u')
    ->range(0, 100)
    ->execute()
    ->fetchAll();
```

```php
// ❌ PROBLEM: Large Unprocessed Arrays
$data = $this->getAllProductData();  // Millions of items in RAM
$processed = array_map(..., $data);

// ✅ SOLUTION: Process in Batches
$batch_size = 1000;
foreach (range(0, PHP_INT_MAX) as $offset) {
    $data = $this->getProductDataBatch($offset, $batch_size);
    if (empty($data)) break;
    array_map(..., $data);
}
```

### 5. Rendering Performance

#### Issues to Check:
- **Excessive Preprocessing**: Too much logic before rendering
- **Unoptimized Templates**: Expensive operations in Twig
- **Missing Lazy Loading**: Loading all related entities
- **Asset Bloat**: Loading unnecessary CSS/JS
- **AJAX Overuse**: Too many round trips
- **Eager Loading**: Loading data not needed for current view
- **DOM Complexity**: Rendering massive HTML

#### Examples to Look For:

```twig
{# ❌ PROBLEM: Logic in Template #}
{% for item in items %}
    <div>
        {{ getAuthorName(item.author_id) }}  {# Query per item! #}
    </div>
{% endfor %}

{# ✅ SOLUTION: Prepare Data in Preprocess #}
{% for item in items %}
    <div>
        {{ item.author_name }}  {# Pre-loaded data #}
    </div>
{% endfor %}
```

### 6. Entity Loading Optimization

#### Issues to Check:
- **Loading Entities in Loops**: Use loadMultiple() instead
- **Redundant Field Access**: Not caching entity access
- **Deep Nesting**: Loading related entities unnecessarily
- **Missing Access Checks**: Inefficient access control
- **Field Reference Chains**: Multiple entity loads for chains
- **Revision Loading**: Loading unnecessary revisions
- **Entity Type Misuse**: Using wrong storage type

#### Examples to Look For:

```php
// ❌ PROBLEM: Multiple Entity Loads
$data = [];
foreach ($nids as $nid) {
    $node = Node::load($nid);
    $author = User::load($node->getOwnerId());
    $data[] = [
        'node' => $node,
        'author' => $author,
    ];
}

// ✅ SOLUTION: Batch Load All
$nodes = Node::loadMultiple($nids);
$uids = array_map(fn($n) => $n->getOwnerId(), $nodes);
$users = User::loadMultiple(array_unique($uids));
$data = array_map(function($node) use ($users) {
    return [
        'node' => $node,
        'author' => $users[$node->getOwnerId()],
    ];
}, $nodes);
```

### 7. Configuration & Variable Performance

#### Issues to Check:
- **Repeated Config Loads**: Loading same config multiple times
- **Configuration in Loops**: Re-reading config each iteration
- **State vs Config**: Using wrong storage mechanism
- **Missing Config Caching**: Not leveraging Drupal config cache
- **Variable Pollution**: Too many variables in scope
- **Excessive Conditionals**: Complex condition evaluation

#### Examples to Look For:

```php
// ❌ PROBLEM: Config Loaded in Loop
foreach ($items as $item) {
    $config = \Drupal::config('my_module.settings');
    $value = $config->get('setting');
}

// ✅ SOLUTION: Load Config Once
$config = $this->configFactory->get('my_module.settings');
$value = $config->get('setting');
foreach ($items as $item) {
    // Use $value
}
```

### 8. API/External Calls

#### Issues to Check:
- **Synchronous Calls**: Blocking on external APIs
- **No Timeouts**: Infinite wait on failed connections
- **Retry Logic**: Missing or excessive retries
- **No Caching**: Calling same endpoint repeatedly
- **Rate Limiting**: Not respecting API limits
- **Connection Pooling**: Creating new connections per request
- **Large Payload Transfers**: Not filtering response data

---

## Performance Review Output

### 1. Performance Issues Found

**Critical Issues** (Performance Impact: High)
- Issue: {Description}
- Location: {File:Line}
- Severity: Critical
- Estimated Impact: {Impact}
- Solution: {Recommended Fix}

**Major Issues** (Performance Impact: Medium)
- {List with same format}

**Minor Issues** (Performance Impact: Low)
- {List with same format}

### 2. Optimization Opportunities

**Quick Wins** (Easy to Implement, High ROI)
- Opportunity: {Description}
- Effort: Low
- Expected Improvement: {Percentage/Impact}
- Implementation: {How to fix}

### 3. Caching Recommendations

**Missing Caches:**
- What: {What should be cached}
- Where: {Which method/query}
- TTL: {Suggested cache lifetime}
- Tags: {Cache tags to use}

### 4. Database Optimization

**Query Issues:**
- Query: {SQL or query builder}
- Problem: {What's inefficient}
- Solution: {How to optimize}
- Potential Speedup: {x times faster}

**Index Recommendations:**
- Table: {table_name}
- Fields: {field1, field2}
- Type: {Normal, Full-text, Unique}
- Impact: {Performance improvement}

### 5. Memory & Load Testing

**Benchmarks:**
- Function: {function_name}
- Current: {Memory/Time}
- Optimized: {Estimated}
- Improvement: {Percentage}

---

## Common Performance Antipatterns

### 1. N+1 Query Problem
```php
// ❌ ANTI-PATTERN
foreach ($nodes as $node) {
    $related = Related::load($node->field_related);  // Query per iteration
}

// ✅ PATTERN
$related_ids = array_map(fn($n) => $n->field_related, $nodes);
$related_items = Related::loadMultiple($related_ids);
```

### 2. Unoptimized Loops
```php
// ❌ ANTI-PATTERN
for ($i = 0; $i < count($array); $i++) {  // count() called each iteration
    echo $array[$i];
}

// ✅ PATTERN
$count = count($array);
for ($i = 0; $i < $count; $i++) {
    echo $array[$i];
}
// OR
foreach ($array as $item) {
    echo $item;
}
```

### 3. Missing Caching Layer
```php
// ❌ ANTI-PATTERN
function getUser($uid) {
    return User::load($uid);  // Always queries
}

// ✅ PATTERN
function getUser($uid) {
    static $users = [];
    return $users[$uid] ?? ($users[$uid] = User::load($uid));
}
```

### 4. Inefficient Database Access
```php
// ❌ ANTI-PATTERN
$result = $this->database->query('SELECT * FROM {users}')->fetchAll();

// ✅ PATTERN
$result = $this->database->select('users', 'u')
    ->fields('u', ['uid', 'name'])
    ->range(0, 100)
    ->orderBy('u.uid', 'DESC')
    ->execute()
    ->fetchAll();
```

### 5. No Cache Metadata
```php
// ❌ ANTI-PATTERN
return [
    '#markup' => $markup,
];

// ✅ PATTERN
return [
    '#markup' => $markup,
    '#cache' => [
        'max-age' => 86400,
        'tags' => ['entity_type:node'],
        'contexts' => ['user'],
    ],
];
```

---

## Performance Testing Recommendations

### Load Testing
```bash
# Use Apache Bench
ab -n 1000 -c 10 https://example.com/page

# Use wrk
wrk -t12 -c400 -d30s https://example.com/page
```

### Query Analysis
```php
// Enable query logging
$this->database->enableDebug();

// Review query log
$queries = $this->database->getDebugInfo();
foreach ($queries as $query) {
    echo $query['query'] . ' (' . $query['time'] . 'ms)';
}
```

### Memory Profiling
```php
// Track memory usage
$start_memory = memory_get_usage();
// ... code to profile ...
$end_memory = memory_get_usage();
echo 'Memory used: ' . ($end_memory - $start_memory) . ' bytes';
```

### Xdebug Profiling
```php
// Generate cachegrind file
xdebug_start_code_coverage(XDEBUG_CODE_COVERAGE_DEAD_CODE_ELIMINATION);
// ... code ...
$data = xdebug_get_code_coverage();
```

---

## Performance Benchmarking Template

### Function Performance Comparison

| Function | Scenario | Time (ms) | Memory (MB) | Queries | Status |
|----------|----------|-----------|------------|---------|--------|
| Original Function | 1000 items | 450 | 12.5 | 1001 | ❌ Slow |
| Optimized Function | 1000 items | 45 | 2.1 | 1 | ✅ Good |
| Improvement | - | 10x faster | 83% less | 99.9% fewer | - |

---

## Recommendations Priority Matrix

### High Priority (Implement Immediately)
- N+1 query issues
- Memory leaks
- Runaway loops
- Missing critical caches
- Blocking operations

### Medium Priority (Implement This Sprint)
- Query optimization
- Index addition
- Caching strategy
- Batch processing
- Asset optimization

### Low Priority (Backlog)
- Minor code cleanup
- Style improvements
- Documentation
- Refactoring non-critical code

---

## Implementation Roadmap

### Phase 1: Critical Fixes (1-2 weeks)
- Fix N+1 queries
- Add missing caches
- Resolve memory issues

### Phase 2: Query Optimization (2-3 weeks)
- Add database indexes
- Optimize complex queries
- Implement pagination

### Phase 3: Caching Strategy (2 weeks)
- Implement cache warming
- Add cache tags strategy
- Set up cache invalidation

### Phase 4: Monitoring (Ongoing)
- Set up performance monitoring
- Create performance alerts
- Regular performance audits

---

## Validation Checklist

### Database Performance
- [ ] No N+1 queries detected
- [ ] Queries use field-specific selects
- [ ] Appropriate indexes exist
- [ ] Batch loading used for collections
- [ ] Query complexity is reasonable

### Caching Strategy
- [ ] All expensive operations cached
- [ ] Cache tags properly configured
- [ ] Cache contexts appropriate
- [ ] TTL values reasonable
- [ ] Cache invalidation strategy clear

### Memory Usage
- [ ] No unbounded result sets
- [ ] Batch processing for large datasets
- [ ] No circular references
- [ ] Memory usage under control
- [ ] Garbage collection working

### Rendering Performance
- [ ] No logic in templates
- [ ] Data preloaded in preprocess
- [ ] Asset loading optimized
- [ ] Render arrays properly cached
- [ ] DOM complexity reasonable

### General Performance
- [ ] No blocking operations
- [ ] Appropriate timeouts
- [ ] Error handling efficient
- [ ] Logging not excessive
- [ ] Code complexity reasonable

---

## Performance Improvement Examples

### Before Optimization
```
Page Load Time: 5.2 seconds
Database Queries: 450
Memory Usage: 95 MB
Cache Hit Rate: 0%
Slowest Query: 1200ms
```

### After Optimization
```
Page Load Time: 0.8 seconds (85% improvement)
Database Queries: 12 (97% reduction)
Memory Usage: 18 MB (81% reduction)
Cache Hit Rate: 89%
Slowest Query: 45ms
```

---

## Resources & Tools

### Performance Monitoring Tools
- **New Relic**: APM and monitoring
- **Datadog**: Infrastructure monitoring
- **Blackfire**: PHP profiling
- **Xdebug**: Debugging and profiling
- **Apache Bench**: Load testing

### Drupal Performance Tools
- **Drupal Speed**: Performance audit module
- **Devel**: Development utilities
- **Cache Warmer**: Cache warming
- **Query Statistics**: Query analysis
- **Performance Log**: Event logging

### Metrics to Track
- Page load time (First Contentful Paint, Largest Contentful Paint)
- Time to First Byte (TTFB)
- Database query count
- Memory usage
- Cache hit rate
- Error rate

---

## Performance Review Example

### Input Code:
```php
<?php
function my_module_get_articles($limit = 10) {
    $nodes = Node::loadByProperties(['type' => 'article']);

    $articles = [];
    foreach ($nodes as $node) {
        $user = User::load($node->getOwnerId());
        $articles[] = [
            'title' => $node->getTitle(),
            'author' => $user->getDisplayName(),
            'created' => date('Y-m-d', $node->getCreatedTime()),
        ];
    }

    return array_slice($articles, 0, $limit);
}
```

### Performance Issues Found:

**Critical Issues:**
1. **N+1 Query Problem**: Loading users in loop
   - Line: foreach loop at line 8
   - Impact: One query per article
   - Solution: Use batch loading

2. **Unbounded Result Set**: Loading ALL articles
   - Line: Node::loadByProperties() at line 3
   - Impact: Memory usage scales with total articles
   - Solution: Use range/limit in query

**Optimized Version:**
```php
<?php
declare(strict_types=1);

function my_module_get_articles(int $limit = 10): array {
    $node_query = \Drupal::entityQuery('node')
        ->condition('type', 'article')
        ->range(0, $limit)
        ->sort('created', 'DESC')
        ->execute();

    $nodes = Node::loadMultiple($node_query);
    $uids = array_unique(array_map(fn($n) => $n->getOwnerId(), $nodes));
    $users = User::loadMultiple($uids);

    return array_map(function($node) use ($users) {
        return [
            'title' => $node->getTitle(),
            'author' => $users[$node->getOwnerId()]->getDisplayName(),
            'created' => date('Y-m-d', $node->getCreatedTime()),
        ];
    }, $nodes);
}
```

**Performance Improvement:**
- Queries: 101+ → 3 (97% reduction)
- Memory: 125MB → 8MB (94% reduction)
- Load Time: 2.5s → 0.15s (94% improvement)

---

## Quick Performance Audit Checklist

- [ ] Identified all database queries
- [ ] Checked for N+1 patterns
- [ ] Reviewed caching strategy
- [ ] Analyzed loop complexity
- [ ] Checked memory usage
- [ ] Reviewed rendering performance
- [ ] Checked asset loading
- [ ] Identified blocking operations
- [ ] Analyzed error handling
- [ ] Created optimization roadmap

