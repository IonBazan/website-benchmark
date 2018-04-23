<?php

declare(strict_types=1);

namespace App\Email;

class SwiftMailerTransportAdapter implements TransportAdapter
{
    /**
     * @var \Swift_Mailer
     */
    protected $swiftMailer;

    public function __construct(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
    }

    public function send(array $recipients, string $subject, string $body): bool
    {
        $message = (new \Swift_Message($subject, $body))
            ->setTo($recipients);

        return (bool) $this->swiftMailer->send($message);
    }
}
