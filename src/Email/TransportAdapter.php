<?php

declare(strict_types=1);

namespace App\Email;

interface TransportAdapter
{
    public function send(array $recipients, string $subject, string $body): bool;
}
