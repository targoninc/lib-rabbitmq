<?php

namespace TargonIndustries\rabbitmq\tasks;

abstract class RabbitMqTask
{
    public string $type;
    public array $properties = [
        'content_type' => 'application/json'
    ];

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function serialize(): string
    {
        return json_encode([
            'type' => $this->type,
            'properties' => $this->properties
        ]);
    }
}