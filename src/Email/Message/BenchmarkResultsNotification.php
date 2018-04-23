<?php

declare(strict_types=1);

namespace App\Email\Message;

use App\DTO\BenchmarkResult;

class BenchmarkResultsNotification extends AbstractMessage
{
    /**
     * @var BenchmarkResult
     */
    protected $results;

    protected $recipients;

    /**
     * @param string[]        $recipients
     * @param BenchmarkResult $results
     */
    public function __construct(array $recipients, BenchmarkResult $results)
    {
        parent::__construct($recipients);

        $this->results = $results;
    }

    public function getTemplateName(): string
    {
        return 'benchmark_results';
    }

    public function getSubject(): string
    {
        return 'email.benchmark_results.title';
    }

    public function getVars(): array
    {
        return [
            'results' => $this->results,
        ];
    }
}
