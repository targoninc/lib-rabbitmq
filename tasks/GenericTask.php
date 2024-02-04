<?php

namespace TargonIndustries\rabbitmq\tasks;

require_once DOCROOT_LIB_RABBITMQ . 'tasks/RabbitMqTask.php';

class GenericTask extends RabbitMqTask
{
    public string $body;

    public function __construct(string $body)
    {
        $this->body = $body;
        parent::__construct('generic');
    }

    public function serialize(): string
    {
        return json_encode([
            'type' => $this->type,
            'properties' => $this->properties,
            'body' => $this->body
        ]);
    }
}