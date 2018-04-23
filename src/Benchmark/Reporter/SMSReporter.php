<?php

declare(strict_types=1);

namespace App\Benchmark\Reporter;

use App\Benchmark\Reporter;
use App\DTO\BenchmarkResult;
use App\SMS\SMSSender;

class SMSReporter implements Reporter
{
    /**
     * @var SMSSender
     */
    protected $smsSender;

    /**
     * @var string
     */
    protected $recipient;

    public function __construct(SMSSender $smsSender, string $recipient)
    {
        $this->smsSender = $smsSender;
        $this->recipient = $recipient;
    }

    public function report(BenchmarkResult $benchmarkResult): void
    {
        $this->smsSender->send($this->recipient, 'Your site was really slow!');
    }
}
