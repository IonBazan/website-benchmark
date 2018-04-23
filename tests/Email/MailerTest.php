<?php

declare(strict_types=1);

namespace App\Tests\Email;

use App\Email\Mailer;
use App\Email\Message;
use App\Email\MessageContentFactory;
use App\Email\TransportAdapter;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class MailerTest extends TestCase
{
    public function testDelegatesEmailSending()
    {
        $contentFactory = $this->prophesize(MessageContentFactory::class);
        $contentFactory->getBody(Argument::type(Message::class))
            ->willReturn('message body');
        $contentFactory->getSubject(Argument::type(Message::class))
            ->willReturn('message subject');
        $transportAdapter = $this->prophesize(TransportAdapter::class);
        $transportAdapter->send(['recipient@example.com'], 'message subject', 'message body')
            ->willReturn(true)
            ->shouldBeCalled();
        $message = $this->prophesize(Message::class);
        $message->getRecipients()
            ->willReturn(['recipient@example.com']);

        $mailer = new Mailer($contentFactory->reveal(), $transportAdapter->reveal());
        $mailer->sendMessage($message->reveal());
    }
}
