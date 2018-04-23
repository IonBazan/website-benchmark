<?php

declare(strict_types=1);

namespace App\Benchmark\Reporter;

use App\Benchmark\Reporter;
use App\DTO\BenchmarkResult;
use App\DTO\WebsiteResult;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

abstract class ConditionalDecorator implements Reporter
{
    /**
     * @var Reporter
     */
    protected $decoratedReporter;

    /**
     * @var ExpressionLanguage
     */
    protected $expressionLanguage;

    /**
     * @var string
     */
    protected $condition;

    public function __construct(Reporter $decoratedReporter, string $condition, ExpressionLanguage $expressionLanguage = null)
    {
        $this->decoratedReporter = $decoratedReporter;
        $this->expressionLanguage = $expressionLanguage ?? new ExpressionLanguage();
        $this->condition = $condition;
    }

    public function report(BenchmarkResult $benchmarkResult): void
    {
        if ($this->isSatisfied($benchmarkResult)) {
            $this->decoratedReporter->report($benchmarkResult);
        }
    }

    abstract public function isSatisfied(BenchmarkResult $benchmarkResult): bool;

    protected function isSingleSatisfied(WebsiteResult $myWebsiteResult, WebsiteResult $competitorResult): bool
    {
        return $this->expressionLanguage->evaluate($this->condition, [
            'my' => $myWebsiteResult,
            'competitor' => $competitorResult,
        ]);
    }
}
