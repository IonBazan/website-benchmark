<?php

declare(strict_types=1);

namespace App\Tests\Email;

use App\Email\SwiftMailerTransportAdapter;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class SwiftMailerTransportAdapterTest extends TestCase
{
    public function testItSendsAnEmail()
    {
        $swiftMailer = $this->prophesize(\Swift_Mailer::class);
        $swiftMailer->send(Argument::which('getSubject', 'subject'))->shouldBeCalled();
        $swiftMailer->send(Argument::which('getBody', 'message'))->shouldBeCalled();
        $adapter = new SwiftMailerTransportAdapter($swiftMailer->reveal());
        $adapter->send(['example@example.com'], 'subject', 'message');
    }
}
