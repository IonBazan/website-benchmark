<?php

declare(strict_types=1);

namespace App\Tests\Benchmark\Reporter;

use App\Benchmark\Reporter;
use App\Benchmark\Reporter\AnyOfConditionalDecorator;
use App\DTO\BenchmarkResult;
use App\DTO\Result;
use App\DTO\Website;
use App\DTO\WebsiteResult;
use App\DTO\WebsiteResultCollection;
use PHPUnit\Framework\TestCase;

class AnyOfConditionalDecoratorTest extends TestCase
{
    /**
     * @dataProvider conditionsMetProvider
     *
     * @param string  $condition
     * @param float   $myScore
     * @param float[] $competitorsScores
     */
    public function testReportsWhenConditionIsMet(string $condition, float $myScore, array $competitorsScores)
    {
        $result = $this->getBenchmarkResult($myScore, $competitorsScores);
        $decoratedReporter = $this->prophesize(Reporter::class);
        $decoratedReporter->report($result)
            ->shouldBeCalled();
        $reporter = new AnyOfConditionalDecorator($decoratedReporter->reveal(), $condition);
        $this->assertTrue($reporter->isSatisfied($result));
        $reporter->report($result);
    }

    /**
     * @dataProvider conditionsNotMetProvider
     *
     * @param string  $condition
     * @param float   $myScore
     * @param float[] $competitorsScores
     */
    public function testDoesNotReportWhenConditionIsNotMet(string $condition, float $myScore, array $competitorsScores)
    {
        $result = $this->getBenchmarkResult($myScore, $competitorsScores);
        $decoratedReporter = $this->prophesize(Reporter::class);
        $decoratedReporter->report($result)
            ->shouldNotBeCalled();
        $reporter = new AnyOfConditionalDecorator($decoratedReporter->reveal(), $condition);
        $this->assertFalse($reporter->isSatisfied($result));
        $reporter->report($result);
    }

    public function conditionsMetProvider(): array
    {
        return [
            [
                'my.getResult().getValue() > 2 * competitor.getResult().getValue()',
                27,
                [13, 15, 20, 30],
            ],
            [
                'my.getResult().getValue() > 10 * competitor.getResult().getValue()',
                301,
                [13, 15, 20, 30],
            ],
            [
                'my.getResult().getValue() < competitor.getResult().getValue()',
                29,
                [13, 15, 20, 30],
            ],
        ];
    }

    public function conditionsNotMetProvider(): array
    {
        return [
            [
                'my.getResult().getValue() > 2 * competitor.getResult().getValue()',
                25,
                [13, 15, 20, 30],
            ],
            [
                'my.getResult().getValue() > 10 * competitor.getResult().getValue()',
                129,
                [13, 15, 20, 30],
            ],
            [
                'my.getResult().getValue() < competitor.getResult().getValue()',
                30,
                [13, 15, 20, 30],
            ],
        ];
    }

    /**
     * @param float   $myScore
     * @param float[] $competitorsScores
     *
     * @return BenchmarkResult
     */
    protected function getBenchmarkResult(float $myScore, array $competitorsScores): BenchmarkResult
    {
        $competitors = array_map(function (float $value) {
            return $this->getWebsiteResult('http://competitor.tld', $value);
        }, $competitorsScores);

        return new BenchmarkResult(
            $this->getWebsiteResult('http://my-website.tld', $myScore),
            new WebsiteResultCollection($competitors),
            new \DateTime()
        );
    }

    protected function getWebsiteResult(string $url, float $value): WebsiteResult
    {
        $result = $this->prophesize(Result::class);
        $result->getValue()->willReturn($value);

        return new WebsiteResult(new Website($url), $result->reveal());
    }
}
