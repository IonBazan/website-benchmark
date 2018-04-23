<?php

declare(strict_types=1);

namespace App\Email;

interface Message
{
    /**
     * @return string[]
     */
    public function getRecipients(): array;

    public function getSubject(): string;

    public function getTemplateName(): string;

    public function getVars(): array;
}
