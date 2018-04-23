<?php

declare(strict_types=1);

namespace App\Benchmark;

use App\DTO\BenchmarkResult;

interface Reporter
{
    public function report(BenchmarkResult $benchmarkResult): void;
}
