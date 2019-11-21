<?php

namespace rpcClient;

use Exception;
use PHPUnit\Framework\TestCase;
use velascrypto\HDKeys;
use velascrypto\Transaction;

const pk = "89d5bd2d31889df63cb1c895e4c6f16772e7b06a8c71228bb59d4c9a0c434fc1f6e586d5d051065a580969d15f48f88251ed24b9c77422410bc39a0e7247e53a";
const pk2 = "31a568d362d35e80464f045d9cf0dc2e925a2d2f9ae99255dbeb7542884d50201a16ea616d8484a0edc1e62c5489df95cb6dab86850cbf806e88779abc803f24";
const pk3 = "085e731142c510792a6490708f8ecdc222263d6616b2ecda53efb4825437e7a1c390fa2cb6ded2712a2c039fd0b9643bf3d9b1117801afca2700d0cc4e279e19";
const url = "https://testnet.velas.com/api/v1/";

class HttpClientTest extends TestCase
{

    public function testGetBalance()
    {
        $client = new HttpClient(url);
        $wallet = HDKeys::FromPrivateKey(pk)->Wallet();
        $balance = $client::$wallet->GetBalance($wallet->base58Address);
        $this->assertNotEquals(0, $balance);
    }

    /**
     * @throws Exception
     */
    public function testGetUnspents()
    {
        $client = new HttpClient(url);
        $wallet = HDKeys::FromPrivateKey(pk)->Wallet();
        $unspents = $client::$wallet->GetUnspent($wallet->base58Address);
        echo json_encode($unspents);
        $this->assertNotCount(0, $unspents);
    }

    /**
     * @throws Exception
     */
    public function testValidate()
    {
        try {
            $client = new HttpClient(url);
            $keypair = HDKeys::FromPrivateKey(pk2);
            $wallet = $keypair->Wallet();
            $unspents = $client::$wallet->GetUnspent($wallet->base58Address);
            $to = "VLSWWh9SCcutqB9APLSxyUyfzeuvG8XXTB1";
            $tx = new Transaction($unspents, 3999999998500000, $keypair, $wallet->base58Address, $to, 1000000);
            $tx = $tx->Sign();
            echo "tx: " . $tx->ToJSON() . "\n";
            $result = $client::$wallet->Validate($tx);
            echo json_encode($result);
            $this->assertEquals("ok", $result);
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function testSend()
    {
        try {
            $client = new HttpClient(url);
            $keypair = HDKeys::FromPrivateKey(pk);
            $wallet = $keypair->Wallet();
            $unspents = $client::$wallet->GetUnspent($wallet->base58Address);
            $to = "VLa1hi77ZXD2BSWDD9wQe8vAhejXyS7vBM4";
            $tx = new Transaction($unspents, 8500000, $keypair, $wallet->base58Address, $to, 1000000);
            $tx = $tx->Sign();
            echo "tx: " . $tx->ToJSON() . "\n";
            $result = $client::$wallet->Send($tx);
            echo json_encode($result);
            $this->assertEquals("ok", $result);
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function testInfo()
    {
        try {
            $client = new HttpClient(url);
            $info = $client->info();
            echo "Height: " . $info->blockchain->height . "\n";
            echo "Peers: " . json_encode($info->p2p_peers) . "\n";
            $this->assertEquals(
                "6ded51778aae36dd026ad5082c0f63bfccc7916ec8aadf788e99d1740276e091",
                $info->p2p_info->id
            );
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function testTxs()
    {
        try {
            $client = new HttpClient(url);
            $txs = $client::$txs->getListByAddress("VLb7itb3EYa6WRub5BBCovFZ9vnN7tBeyPc");
            echo json_encode($txs);
            $this->assertEquals(
                "6ded51778aae36dd026ad5082c0f63bfccc7916ec8aadf788e99d1740276e091",
                $txs
            );
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function testTxByHashes()
    {
        try {
            $client = new HttpClient(url);
            $hashes = [
                "ddbdf3886d024449d4e50fa6537c5a7dc6b948b17705fd7656eb8efd0fd912bb"
            ];
            $txs = $client::$txs->getByHashes($hashes);
            echo json_encode($txs);
            $this->assertEquals(
                true,
                count($txs) > 0
            );
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function testGetBlock()
    {
        try {
            $client = new HttpClient(url);
            $hash = "0e465b0da2de868cfbdb11cd6eca2dd4a84d866a8bb67d3f5b5c0bd28d995692";
            $block = $client::$block->getByHash($hash);
            echo json_encode($block);
            $this->assertEquals(
                $hash,
                $block->header->hash
            );
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function testGetTxsByHeight()
    {
        try {
            $client = new HttpClient(url);
            $height = 0;
            $txs = $client::$txs->getByHeight($height);
            echo json_encode($txs);
            $this->assertEquals(
                true,
                count($txs) > 0
            );
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            throw $e;
        }
    }
}
