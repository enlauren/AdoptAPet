<?php

namespace App\NotificationsBundle\Model;

class Notification
{
    private $to;
    private $subject;
    private $body;

    public function __construct(
        string $to,
        string $subject,
        string $body
    )
    {
        $this->to      = $to;
        $this->subject = $subject;
        $this->body    = $body;
    }
}
