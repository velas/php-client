<?php


namespace rpcClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use rpcClient\Dto\NodeInfo;

class HttpClient
{
    /**
     * @var Client
     */
    private $client;
    static public $txs;
    static public $wallet;
    static public $header;
    static public $block;

    /**
     * HttpClient constructor.
     * @param string $url
     */
    function __construct(string $url)
    {
        $this->client = new Client(['base_uri' => $url]);
        $this::$txs = new Txs($this->client);
        $this::$wallet = new Wallet($this->client);
        $this::$header = new Header($this->client);
        $this::$block = new Block($this->client);

    }

    /**
     * @return NodeInfo
     * @throws Exception
     */
    function info(): NodeInfo
    {
        try {
            $res = $this->client->get("info");
            $body = json_decode($res->getBody());
            echo $res->getStatusCode() . "\n";
            return new NodeInfo($body);
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