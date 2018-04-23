<?php

declare(strict_types=1);

namespace App\DTO;

class BenchmarkResult
{
    /**
     * @var WebsiteResult
     */
    protected $mainWebsiteResult;

    /**
     * @var WebsiteResultCollection
     */
    protected $websitesResults;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct(WebsiteResult $mainWebsiteResult, WebsiteResultCollection $websitesResults, \DateTime $createdAt)
    {
        $this->mainWebsiteResult = $mainWebsiteResult;
        $this->websitesResults = $websitesResults;
        $this->createdAt = $createdAt;
    }

    public function getMainWebsiteResult(): WebsiteResult
    {
        return $this->mainWebsiteResult;
    }

    public function getWebsitesResults(): WebsiteResultCollection
    {
        return $this->websitesResults;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
