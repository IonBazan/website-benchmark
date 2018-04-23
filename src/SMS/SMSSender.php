<?php

declare(strict_types=1);

namespace App\SMS;

interface SMSSender
{
    public function send(string $recipient, string $content): bool;
}
