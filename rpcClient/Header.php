<?php


namespace rpcClient;


use GuzzleHttp\Client;
use mysql_xdevapi\Exception;

class Header
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    function getLastHeaders(int $limit = 20) {
        throw new Exception("not implementned yet");
    }

    function getHeader(string $hash): Header {
        throw new Exception("not implementned yet");
    }
}