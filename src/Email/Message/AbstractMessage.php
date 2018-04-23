<?php

declare(strict_types=1);

namespace App\Email\Message;

use App\Email\Message;

abstract class AbstractMessage implements Message
{
    /**
     * @var string[]
     */
    protected $recipients;

    /**
     * @param string[] $recipients
     */
    public function __construct(array $recipients)
    {
        $this->recipients = $recipients;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }
}
