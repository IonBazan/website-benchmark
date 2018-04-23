<?php

declare(strict_types=1);

namespace App\Tests\Email;

use App\Email\Message;
use App\Email\TemplatingMessageContentFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class TemplatingMessageContentFactoryTest extends TestCase
{
    public function testProperTemplateIsBeingRendered()
    {
        $message = $this->prophesize(Message::class);
        $message->getTemplateName()
            ->willReturn('my_template');
        $message->getVars()
            ->willReturn(['param' => 'val']);
        $templating = $this->prophesize(EngineInterface::class);
        $templating->render('email/my_template.html.twig', ['param' => 'val'])
            ->willReturn('mail content')
            ->shouldBeCalled();
        $factory = new TemplatingMessageContentFactory(
            $templating->reveal(),
            $this->prophesize(TranslatorInterface::class)->reveal()
        );

        $this->assertSame('mail content', $factory->getBody($message->reveal()));
    }
}
