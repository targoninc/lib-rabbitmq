# Description

This is a simple interface for sending tasks to RabbitMQ. It will serialize the task and send it to the specified exchange and queue.
While getting tasks is generally implemented, it is *not* tested. If you intend to use it, feel free to contribute to this repo to improve it.

# Example usage

## Environment variables

| Variable      | Description                           |
|---------------|---------------------------------------|
| RABBITMQ_HOST | The host of the RabbitMQ instance     |
| RABBITMQ_USER | The user of the RabbitMQ instance     |
| RABBITMQ_PASS | The password of the RabbitMQ instance |

```php
<?php

use TargonIndustries\rabbitmq\tasks\MailTask;
use TargonIndustries\rabbitmq\TaskSender;

require_once './tasks/MailTask.php';

$task = new MailTask('someone@somewhere.com', 'User', 'Your Subject here', 'This is an email.');
try {
    TaskSender::sendToRMQ($task->type, 'exchange', 'mail', $task);
} catch (Exception $e) {
    error_log("RabbitMQ error: " . $e->getMessage());
    return true;
}
```
