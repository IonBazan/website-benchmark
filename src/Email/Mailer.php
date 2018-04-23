<?php

declare(strict_types=1);

namespace App\Email;

class Mailer
{
    /**
     * @var MessageContentFactory
     */
    protected $contentFactory;

    /**
     * @var TransportAdapter
     */
    protected $transport;

    public function __construct(MessageContentFactory $contentFactory, TransportAdapter $transport)
    {
        $this->contentFactory = $contentFactory;
        $this->transport = $transport;
    }

    public function sendMessage(Message $message): bool
    {
        return $this->transport->send(
            $message->getRecipients(),
            $this->contentFactory->getSubject($message),
            $this->contentFactory->getBody($message)
        );
    }
}
