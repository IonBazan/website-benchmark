<?php

declare(strict_types=1);

namespace App\Service;

use App\Benchmark\Reporter;
use App\DTO\BenchmarkResult;

class ResultsReporter implements Reporter
{
    /**
     * @var Reporter[]
     */
    protected $reporters = [];

    /**
     * @param Reporter[] $reporters
     */
    public function __construct(iterable $reporters)
    {
        $this->reporters = $reporters;
    }

    public function report(BenchmarkResult $benchmarkResult): void
    {
        foreach ($this->reporters as $reporter) {
            $reporter->report($benchmarkResult);
        }
    }
}
