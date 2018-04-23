<?php

declare(strict_types=1);

namespace App\DTO;

class LoadTimeResult extends Result
{
    public function getUnit(): string
    {
        return 's';
    }
}
