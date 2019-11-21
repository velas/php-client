<?php


namespace rpcClient;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use rpcClient\Dto\Out;
use rpcClient\Dto\TransactionResponse;
use velascrypto\Transaction;

class Wallet
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
     * Get balance by wallet address
     * @param string $address
     * @return float
     */
    function GetBalance(string $address): float
    {
        try {
            $res = $this->client->get("wallet/balance/{$address}");
            $body = $res->getBody();
            echo $res->getStatusCode();
            echo $body;
            return json_decode($body)->{'amount'};
        } catch (RequestException $e) {
            echo $e->getCode();
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * Get array of unspent outs by wallet address
     * @param string $address
     * @return Out[]
     * @throws Exception
     */
    function GetUnspent(string $address): array
    {
        try {
            $res = $this->client->get("wallet/unspent/{$address}");
            $body = json_decode($res->getBody());
            echo $res->getStatusCode();
            if ($body == null) {
                return [];
            }
            $res = Out::FromArray($body);
            return $res;
        } catch (RequestException $e) {
            echo $e->getCode();
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * Validate transaction
     * @param Transaction $tx
     * @return string
     * @throws Exception
     */
    function Validate(Transaction $tx): string
    {
        try {
            $res = $this->client->post("txs/validate", [
                'body' => $tx->ToJSON()
            ]);
            $body = json_decode($res->getBody());
            echo $res->getStatusCode();
            return $body->result;
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
     * Send transaction from wallet to wallet
     * @param Transaction $tx
     * @return TransactionResponse
     * @throws Exception
     */
    function Send(Transaction $tx): string
    {
        try {
            $res = $this->client->post("txs/publish", [
                'body' => $tx->ToJSON()
            ]);
            $body = json_decode($res->getBody());
            echo $res->getStatusCode();
            return $body->result;
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
