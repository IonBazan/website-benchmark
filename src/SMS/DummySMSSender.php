<?php

declare(strict_types=1);

namespace App\SMS;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * A dummy SMS sender - simply logs the content.
 */
class DummySMSSender implements SMSSender, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function send(string $recipient, string $content): bool
    {
        $this->logger->info('Sent SMS message', [$recipient, $content]);

        return true;
    }
}
