<?php

declare(strict_types=1);

namespace App\Benchmark\Reporter;

use App\DTO\BenchmarkResult;

class AnyOfConditionalDecorator extends ConditionalDecorator
{
    public function isSatisfied(BenchmarkResult $benchmarkResult): bool
    {
        foreach ($benchmarkResult->getWebsitesResults() as $competitorResult) {
            if ($this->isSingleSatisfied($benchmarkResult->getMainWebsiteResult(), $competitorResult)) {
                return true;
            }
        }

        return false;
    }
}
