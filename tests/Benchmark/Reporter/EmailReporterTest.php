<?php

declare(strict_types=1);

namespace App\Tests\Benchmark\Reporter;

use App\Benchmark\Reporter\EmailReporter;
use App\DTO\BenchmarkResult;
use App\Email\Mailer;
use App\Email\Message\BenchmarkResultsNotification;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class EmailReporterTest extends TestCase
{
    public function testMailerIsBeingCalledWithProperMessage()
    {
        $mailer = $this->prophesize(Mailer::class);
        $mailer->sendMessage(Argument::type(BenchmarkResultsNotification::class))
            ->shouldBeCalled();
        $reporter = new EmailReporter($mailer->reveal(), 'example@example.com');
        $reporter->report($this->prophesize(BenchmarkResult::class)->reveal());
    }
}
