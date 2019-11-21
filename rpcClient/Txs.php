<?php


namespace rpcClient;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use rpcClient\Dto\TxInfo;

class Txs
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
     * @param string $walletAddres
     * @return string[]
     * @throws Exception
     */
    function getListByAddress(string $walletAddres): array
    {
        try {
            $res = $this->client->get("wallet/txs/{$walletAddres}");
            $body = json_decode($res->getBody());
            echo $res->getStatusCode() . "\n";
            return $body;
        } catch (RequestException $e) {
            echo $e->getCode();
            echo $e->getMessage();
            throw $e;
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * @param array $hashes
     * @return TxInfo[]
     * @throws Exception
     */
    function getByHashes(array $hashes): array
    {
        try {
            $res = $this->client->post("txs", [
                'json' => ['hashes' => $hashes,],

            ]);
            $body = json_decode($res->getBody());
            echo $res->getStatusCode() . "\n";
            $res = [];
            foreach ($body AS $index => $value) $res[$index] = new TxInfo($value);
            return $res;
        } catch (RequestException $e) {
            echo $e->getCode();
            echo $e->getMessage();
            throw $e;
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * @param int $height
     * @return string[]
     * @throws Exception
     */
    function getByHeight(int $height): array
    {
        try {
            $res = $this->client->get("txs/height/{$height}");
            $body = json_decode($res->getBody());
            echo $res->getStatusCode() . "\n";
            return $body;
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