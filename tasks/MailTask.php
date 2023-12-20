<?php

namespace TargonIndustries\rabbitmq\tasks;

require_once DOCROOT_LIB_RABBITMQ . 'tasks/RabbitMqTask.php';

class MailTask extends RabbitMqTask
{
    public string $recipient_mail;
    public string $recipient_name;
    public string $subject;
    public string $body;

    public function __construct(string $recipient_mail, string $recipient_name, string $subject, string $body)
    {
        $this->recipient_mail = $recipient_mail;
        $this->recipient_name = $recipient_name;
        $this->subject = $subject;
        $this->body = $body;
        parent::__construct('mail');
    }

    public function serialize(): string
    {
        return json_encode([
            'type' => $this->type,
            'properties' => $this->properties,
            'recipient_mail' => $this->recipient_mail,
            'recipient_name' => $this->recipient_name,
            'subject' => $this->subject,
            'body' => $this->body
        ]);
    }
}