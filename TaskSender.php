<?php

namespace Lyda\utility\rabbitmq;

use Exception;
use Lyda\utility\rabbitmq\tasks\RabbitMqTask;

require_once $_SERVER['DOCUMENT_ROOT'] . '/v1/utility/rabbitmq/RabbitMQ.php';

class TaskSender
{
    static function sendToRMQ(string $queueName, string $exchangeName, string $routingKey, RabbitMqTask $task): void
    {
        try {
            RabbitMQ::getConnection();
            RabbitMQ::bindQueueToExchange($queueName, $exchangeName, $routingKey);
            RabbitMQ::sendTask($queueName, $exchangeName, $routingKey, $task);
        } catch (Exception $e) {
            error_log("RabbitMQ error: " . $e->getMessage());
        }
    }
}