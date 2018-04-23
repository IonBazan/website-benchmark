<?php

declare(strict_types=1);

namespace App\Email;

interface MessageContentFactory
{
    public function getBody(Message $message): string;

    public function getSubject(Message $message): string;
}
