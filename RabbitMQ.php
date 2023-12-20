<?php

namespace Lyda\utility\rabbitmq;

use AMQPChannel;
use AMQPChannelException;
use AMQPConnection;
use AMQPConnectionException;
use AMQPExchange;
use AMQPExchangeException;
use AMQPQueue;
use AMQPQueueException;
use Lyda\utility\rabbitmq\tasks\RabbitMqTask;

require_once DOCROOT_LIB_RABBITMQ . 'tasks/RabbitMqTask.php';

class RabbitMQ
{
    private static ?AMQPConnection $connection = null;
    private static ?AMQPChannel $channel = null;

    /**
     * @throws AMQPConnectionException
     */
    public static function getConnection(): ?AMQPConnection
    {
        if (self::$connection == null) {
            self::$connection = new AMQPConnection([
                'host' => getenv('RABBITMQ_HOST'),
                'port' => 5672,
                'vhost' => '/',
                'login' => getenv('RABBITMQ_USER'),
                'password' => getenv('RABBITMQ_PASS')
            ]);
            self::$connection->connect();
        }
        return self::$connection;
    }

    /**
     * @throws AMQPConnectionException
     */
    public static function getChannel(): ?AMQPChannel
    {
        if (self::$channel == null) {
            self::$channel = new AMQPChannel(self::getConnection());
        }
        return self::$channel;
    }

    /**
     * @throws AMQPQueueException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    public static function getQueue(string $name): AMQPQueue
    {
        $queue = new AMQPQueue(self::getChannel());
        $queue->setName($name);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();
        return $queue;
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    public static function getExchange(string $name): AMQPExchange
    {
        $exchange = new AMQPExchange(self::getChannel());
        $exchange->setName($name);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();
        return $exchange;
    }

    /**
     * @throws AMQPQueueException
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    public static function bindQueueToExchange(string $queueName, string $exchangeName, string $routingKey): void
    {
        $queue = self::getQueue($queueName);
        $exchange = self::getExchange($exchangeName);
        $queue->bind($exchangeName, $routingKey);
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    public static function sendTask(string $queueName, string $exchangeName, string $routingKey, RabbitMqTask $task): void
    {
        $exchange = self::getExchange($exchangeName);
        $exchange->publish($task->serialize(), $routingKey);
    }

    /**
     * @throws AMQPQueueException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    public static function getTask(string $queueName)
    {
        $queue = self::getQueue($queueName);
        $message = $queue->get();
        if ($message) {
            $queue->ack($message->getDeliveryTag());
            return unserialize($message->getBody());
        }
        return null;
    }
}