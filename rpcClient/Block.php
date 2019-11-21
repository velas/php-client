<?php


namespace rpcClient;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Block
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $hash
     * @return Dto\Block
     * @throws Exception
     */
    function getByHash(string $hash): Dto\Block
    {
        try {
            $res = $this->client->get("blocks/{$hash}");
            $body = json_decode($res->getBody());
            echo $res->getStatusCode() . "\n";
            return new Dto\Block($body);
        } catch (RequestException $e) {
            echo $e->getCode();
            echo $e->getMessage();
            throw $e;
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

}