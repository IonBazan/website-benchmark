<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Benchmark\ResultProvider;
use App\DTO\Result;
use App\DTO\Website;
use App\DTO\WebsiteCollection;
use App\Exception\InvalidUrlException;
use App\Service\Benchmark;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BenchmarkTest extends TestCase
{
    public function testValidatorIsCalled()
    {
        $benchmark = new Benchmark($this->getValidator(1), $this->prophesize(ResultProvider::class)->reveal());
        $this->expectException(InvalidUrlException::class);
        $benchmark->benchmark(new Website('invalid-url'), new WebsiteCollection());
    }

    public function testItGeneratesValidResults()
    {
        $myWebsite = new Website('my-url');
        $theirWebsite = new Website('their-url');
        $resultProvider = $this->prophesize(ResultProvider::class);
        $resultProvider->getResult($myWebsite)
            ->willReturn($this->getResultMock(10));
        $resultProvider->getResult($theirWebsite)
            ->willReturn($this->getResultMock(20));
        $benchmark = new Benchmark($this->getValidator(0), $resultProvider->reveal());
        $benchmarkResult = $benchmark->benchmark($myWebsite, new WebsiteCollection([$theirWebsite]));

        $this->assertSame($myWebsite, $benchmarkResult->getMainWebsiteResult()->getWebsite());
        $this->assertSame(10.0, $benchmarkResult->getMainWebsiteResult()->getResult()->getValue());
        $this->assertSame($theirWebsite, $benchmarkResult->getWebsitesResults()[0]->getWebsite());
        $this->assertSame(20.0, $benchmarkResult->getWebsitesResults()[0]->getResult()->getValue());
        $this->assertInstanceOf(\DateTime::class, $benchmarkResult->getCreatedAt());
    }

    protected function getResultMock(float $value): Result
    {
        $result = $this->prophesize(Result::class);
        $result->getValue()->willReturn($value);

        return $result->reveal();
    }

    protected function getValidator(int $violationsCount): ValidatorInterface
    {
        $violations = $this->prophesize(ConstraintViolationListInterface::class);
        $violations->count()
            ->willReturn($violationsCount);
        $violations->addAll(Argument::type(ConstraintViolationListInterface::class))
            ->will(function () {
                return $this;
            });
        $validator = $this->prophesize(ValidatorInterface::class);
        $validator->validate(Argument::type(Website::class))
            ->willReturn($violations->reveal());
        $validator->validate(Argument::type(WebsiteCollection::class))
            ->willReturn($violations->reveal());

        return $validator->reveal();
    }
}
