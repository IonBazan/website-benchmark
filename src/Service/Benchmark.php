<?php

declare(strict_types=1);

namespace App\Service;

use App\Benchmark\ResultProvider;
use App\DTO\BenchmarkResult;
use App\DTO\Website;
use App\DTO\WebsiteCollection;
use App\DTO\WebsiteResult;
use App\DTO\WebsiteResultCollection;
use App\Exception\InvalidUrlException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Benchmark
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var ResultProvider
     */
    protected $resultProvider;

    public function __construct(ValidatorInterface $validator, ResultProvider $resultProvider)
    {
        $this->validator = $validator;
        $this->resultProvider = $resultProvider;
    }

    public function benchmark(Website $mainWebsite, WebsiteCollection $websites)
    {
        $createdAt = new \DateTime();
        $this->validateInput($mainWebsite, $websites);
        $websiteResults = new WebsiteResultCollection();

        foreach ($websites as $website) {
            $result = $this->resultProvider->getResult($website);
            $websiteResults[] = new WebsiteResult($website, $result);
        }

        $mainWebsiteResult = new WebsiteResult($mainWebsite, $this->resultProvider->getResult($mainWebsite));

        return new BenchmarkResult($mainWebsiteResult, $websiteResults, $createdAt);
    }

    protected function validateInput(Website $mainWebsite, WebsiteCollection $websites)
    {
        $violations = $this->validator->validate($mainWebsite);
        $violations->addAll($this->validator->validate($websites));

        if ($violations->count()) {
            throw new InvalidUrlException($violations);
        }
    }
}
