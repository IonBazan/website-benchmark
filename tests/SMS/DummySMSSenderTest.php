<?php

declare(strict_types=1);

namespace App\Tests\SMS;

use App\SMS\DummySMSSender;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class DummySMSSenderTest extends TestCase
{
    public function testItLogsAMessage()
    {
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->info('Sent SMS message', ['123123123', 'test'])
            ->shouldBeCalled();
        $sender = new DummySMSSender();
        $sender->setLogger($logger->reveal());

        $sender->send('123123123', 'test');
    }
}
