<?php

declare(strict_types=1);

namespace App\Tests\Benchmark\Reporter;

use App\Benchmark\Reporter\SMSReporter;
use App\DTO\BenchmarkResult;
use App\SMS\SMSSender;
use PHPUnit\Framework\TestCase;

class SMSReporterTest extends TestCase
{
    public function testSenderIsBeingCalled()
    {
        $sender = $this->prophesize(SMSSender::class);
        $sender->send('111222333', 'Your site was really slow!')
            ->shouldBeCalled();
        $reporter = new SMSReporter($sender->reveal(), '111222333');
        $reporter->report($this->prophesize(BenchmarkResult::class)->reveal());
    }
}
