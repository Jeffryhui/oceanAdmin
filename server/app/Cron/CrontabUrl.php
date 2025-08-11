<?php

namespace App\Cron;

use GuzzleHttp\Client;
use Hyperf\Guzzle\ClientFactory;
class CrontabUrl
{
    public function __construct(
        private readonly ClientFactory $clientFactory
    ) {}

    public function getClient(): Client
    {
        return $this->clientFactory->create();
    }

    public function execute(string $url)
    {
        return $this->getClient()->get($url);
    }
}