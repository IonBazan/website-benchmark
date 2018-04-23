<?php

declare(strict_types=1);

namespace App\DTO;

class WebsiteResult
{
    /**
     * @var Website
     */
    protected $website;

    /**
     * @var Result
     */
    protected $result;

    public function __construct(Website $website, Result $result)
    {
        $this->website = $website;
        $this->result = $result;
    }

    public function getWebsite(): Website
    {
        return $this->website;
    }

    public function getResult(): Result
    {
        return $this->result;
    }
}
