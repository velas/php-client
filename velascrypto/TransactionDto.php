<?php


namespace velascrypto;


use rpcClient\Dto\Out;

class TransactionDto
{
    /**
     * @var string
     */
    public $hash;

    /**
     * @var int
     */
    public $version = 1;

    /**
     * @var int
     */
    public $lock_time = 0;
    /**
     * @var TxIn[]
     */
    public $tx_in = [];
    /**
     * @var TxOut[]
     */
    public $tx_out = [];
}

class TxIn
{
    /**
     * @var string
     */
    public $signature_script;

    /**
     * @var int[]
     */
    public $public_key;

    /**
     * @var Out
     */
    public $previous_output;

    /**
     * @var int
     */
    public $sequence;

    /**
     * @var int
     */
    public $wallet_address;
}

class TxOut
{
    /**
     * @var string
     */
    public $pk_script;

    /**
     * @var string
     */
    public $node_id = "0000000000000000000000000000000000000000000000000000000000000000";

    /**
     * @var object
     */
    public $payload;

    /**
     * @var int
     */
    public $value;

    /**
     * @var int
     */
    public $index;

    /**
     * @var int
     */
    public $wallet_address;
}