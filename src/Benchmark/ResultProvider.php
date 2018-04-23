<?php

declare(strict_types=1);

namespace App\Benchmark;

use App\DTO\Result;
use App\DTO\Website;

interface ResultProvider
{
    public function getResult(Website $website): Result;
}
