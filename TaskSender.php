<?php

namespace TargonIndustries\rabbitmq;

use Exception;
use TargonIndustries\rabbitmq\tasks\RabbitMqTask;

define('DOCROOT_LIB_RABBITMQ', realpath(dirname(__FILE__)).'/');
require_once DOCROOT_LIB_RABBITMQ . 'RabbitMQ.php';
require_once DOCROOT_LIB_RABBITMQ . 'tasks/MailTask.php';
require_once DOCROOT_LIB_RABBITMQ . 'tasks/GenericTask.php';

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