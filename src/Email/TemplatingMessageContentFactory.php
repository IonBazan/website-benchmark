<?php

declare(strict_types=1);

namespace App\Email;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class TemplatingMessageContentFactory implements MessageContentFactory
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(EngineInterface $templating, TranslatorInterface $translator)
    {
        $this->templating = $templating;
        $this->translator = $translator;
    }

    public function getBody(Message $message): string
    {
        $template = sprintf('email/%s.html.twig', $message->getTemplateName());

        return $this->templating->render($template, $message->getVars());
    }

    public function getSubject(Message $message): string
    {
        return $this->translator->trans($message->getSubject());
    }
}
