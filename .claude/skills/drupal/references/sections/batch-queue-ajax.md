## Advanced Development Patterns

### Batch API for Long Operations
- **Purpose**: Process large datasets without PHP timeout issues
- **Use cases**: Data migration, bulk updates, file processing, API calls
- **Batch structure**: Create associative array with title, operations, and finished callback
- **Operations**: Array of callable methods and their arguments
- **Progress tracking**: Automatically shows progress bar to users
- **Error handling**: Implement proper exception handling in batch operations
- **User experience**: Provides real-time feedback during long operations
- **Memory management**: Processes data in chunks to prevent memory exhaustion

### Queue API for Background Processing
- **Purpose**: Process tasks in the background without blocking user interaction
- **Queue creation**: Use `\Drupal::queue('queue_name')` to get queue instance
- **Item addition**: Use `createItem()` to add tasks to the queue
- **Processing**: Claim items with `claimItem()` and delete with `deleteItem()`
- **Cron integration**: Process queue items during cron runs for regular background tasks
- **Reliability**: Failed items can be released back to the queue
- **Worker plugins**: Create QueueWorker plugins for structured queue processing
- **Logging**: Implement proper logging for queue processing monitoring
- **Performance**: Process multiple items per cron run for efficiency

### AJAX Forms
- **Trigger elements**: Add `#ajax` property to form elements (select, checkbox, button)
- **Callback method**: Reference callback method using `::methodName` syntax
- **Wrapper element**: Specify target element ID for AJAX response replacement
- **Response format**: Return form element or render array from callback
- **Event types**: Use 'change', 'click', 'blur' events as needed
- **Progress indicator**: Automatically shows loading indicator during AJAX requests
- **Error handling**: Implement try-catch blocks in AJAX callbacks
- **Form state**: Use `$form_state->getTriggeringElement()` to identify trigger
- **Multiple triggers**: Can have multiple AJAX elements in same form
- **Dynamic forms**: Update form options, show/hide fields based on user input
