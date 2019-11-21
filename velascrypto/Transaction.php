<?php


namespace velascrypto;


use Exception;
use rpcClient\Dto\Out;
use Tuupola\Base58;
use velascrypto\utils\GetBytes;

class Transaction extends TransactionDto
{
    /**
     * @var GetBytes
     */
    private $byteConvertor;

    /**
     * Transaction constructor.
     * @param Out[] $unspents
     * @param int $amount
     * @param HDKeys $velasKey
     * @param string $changeAddress
     * @param string $to
     * @param int $commission
     */
    function __construct(array $unspents, int $amount, HDKeys $velasKey, string $changeAddress, string $to, int $commission)
    {
        $base58 = new Base58([
            "characters" => Base58::BITCOIN,
        ]);

        $this->byteConvertor = new GetBytes();

        $totalin = 0;
        foreach ($unspents as $po) {
            $totalin += $po->value;
        }

        $index = 0;

        //Commission
        $tx_out1 = new TxOut();
        $tx_out1->index = $index++;
        $tx_out1->pk_script = "";
        $tx_out1->value = $commission;
        array_push($this->tx_out, $tx_out1);

        // Dest address
        $tx_out2 = new TxOut();
        $tx_out2->index = $index++;
        $tx_out2->wallet_address = $to;
        $tx_out2->pk_script = bin2hex($base58->decode($to));
        $tx_out2->value = $amount;
        array_push($this->tx_out, $tx_out2);

        $change = $totalin - $amount - $commission;
        if ($change > 0) {
            // Change address
            $tx_out3 = new TxOut();
            $tx_out3->index = $index++;
            $tx_out3->wallet_address = $changeAddress;
            $tx_out3->pk_script = bin2hex($base58->decode($changeAddress));
            $tx_out3->value = $change;
            array_push($this->tx_out, $tx_out3);
        }

        $pk = $velasKey->getPrivateKey();

        foreach ($unspents as $po) {
            $sigMsg = $this->MsgForSign($po->hash, $po->index);
            $sig = sodium_crypto_sign_detached($sigMsg, $pk);

            $tx_in = new TxIn();
            $tx_in->wallet_address = $changeAddress;
            $tx_in->public_key = bin2hex($velasKey->getPublicKey());
            $tx_in->sequence = 1;
            $tx_in->previous_output = $po;
            $tx_in->signature_script = bin2hex($sig);
            array_push($this->tx_in, $tx_in);
        }
    }

    public function Sign(): Transaction
    {
        $this->hash = $this->GenerateHash();
        return $this;
    }

    /**
     * @throws Exception
     */
    public function ToJSON(): string
    {
        $i = 0;
        $tx = unserialize(serialize($this));
        foreach ($tx->tx_in as $tx_in){
            $tx_in->public_key = base64_encode(hex2bin($tx_in->public_key));
            $tx->tx_in[$i] = $tx_in;
            $i++;
        }
        $tx = json_encode($tx);
        $err = json_last_error();
        if ($err != 0) {
            throw new Exception("json encode error code: " . $err);
        }

        return $tx;
    }

    private function MsgForSign(string $hash, int $index): string
    {
        $payload = hex2bin($hash);
        $payload .= $this->byteConvertor->FromInt($index);
        $payload .= $this->byteConvertor->FromInt($this->version);
        $payload .= $this->byteConvertor->FromInt($this->lock_time);
        foreach ($this->tx_out as $tx_out) {
            $payload .= $this->byteConvertor->FromInt($tx_out->index);
            $payload .= $this->byteConvertor->FromUlong($tx_out->value);
            $payload .= hex2bin($tx_out->pk_script);
            if ($this->IsNullNodeID($tx_out->node_id)) {
                $payload .= $tx_out->node_id;
            }
        }

        return $payload;
    }

    private function GenerateHash()
    {
        $payload = $this->byteConvertor->FromInt($this->version);
        $payload .= $this->byteConvertor->FromInt($this->lock_time);
        foreach ($this->tx_in as $tx_in) {
            $payload .= hex2bin($tx_in->previous_output->hash);
            $payload .= $this->byteConvertor->FromInt($tx_in->previous_output->index);
            $payload .= $this->byteConvertor->FromUlong($tx_in->previous_output->value);
            $payload .= $this->byteConvertor->FromInt($tx_in->sequence);
            $payload .= hex2bin($tx_in->public_key);
            $payload .= hex2bin($tx_in->signature_script);
        }

        foreach ($this->tx_out as $tx_out) {
            $payload .= $this->byteConvertor->FromInt($tx_out->index);
            $payload .= $this->byteConvertor->FromUlong($tx_out->value);
            $payload .= hex2bin($tx_out->pk_script);
            if ($this->IsNullNodeID($tx_out->node_id)) {
                $payload .= $tx_out->node_id;
            }
        }

        return hash('sha256', hex2bin(hash('sha256', $payload)));
    }

    private function AddNodeID (?string $nodeID): string {
        if (!$nodeID || $nodeID == " " &&
            $nodeID != "0000000000000000000000000000000000000000000000000000000000000000") {
            return hex2bin($nodeID);
        }
        return $nodeID;
    }

    private function IsNullNodeID (?string $nodeID): bool {
        if (!$nodeID || $nodeID == " " &&
            $nodeID != "0000000000000000000000000000000000000000000000000000000000000000") {
            return true;
        }
        return false;
    }

}