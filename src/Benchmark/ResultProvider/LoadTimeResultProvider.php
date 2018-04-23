<?php

declare(strict_types=1);

namespace App\Benchmark\ResultProvider;

use App\Benchmark\ResultProvider;
use App\DTO\LoadTimeResult;
use App\DTO\Result;
use App\DTO\Website;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Symfony\Component\HttpFoundation\Request;

class LoadTimeResultProvider implements ResultProvider
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    public function __construct(HttpClient $client, MessageFactory $messageFactory)
    {
        $this->client = $client;
        $this->messageFactory = $messageFactory;
    }

    public function getResult(Website $website): Result
    {
        $startTime = microtime(true);
        $request = $this->messageFactory->createRequest(Request::METHOD_GET, $website->getUrl());
        $this->client->sendRequest($request);

        return new LoadTimeResult(microtime(true) - $startTime);
    }
}
