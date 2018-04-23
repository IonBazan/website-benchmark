<?php

declare(strict_types=1);

namespace App\Benchmark\Reporter;

use App\Benchmark\Reporter;
use App\DTO\BenchmarkResult;
use App\Email\Mailer;
use App\Email\Message\BenchmarkResultsNotification;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class EmailReporter implements Reporter, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $recipient;

    public function __construct(Mailer $mailer, string $recipient)
    {
        $this->mailer = $mailer;
        $this->recipient = $recipient;
    }

    public function report(BenchmarkResult $benchmarkResult): void
    {
        $message = new BenchmarkResultsNotification([$this->recipient], $benchmarkResult);
        $this->mailer->sendMessage($message);

        if (null !== $this->logger) {
            $this->logger->info('Sent an email', [$this->recipient]);
        }
    }
}
