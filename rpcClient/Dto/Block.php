<?php


namespace rpcClient\Dto;


use velascrypto\TransactionDto;

class Block
{
    /**
     * @var Header
     */
    public $header;

    /**
     * @var TransactionDto[]
     */
    public $txns;

    public function __construct($obj)
    {
        if ($obj->header) {
            $this->header = new Header($obj->header);
        }

        if ($obj->txns) {
            $this->txns = $obj->txns;
        }
    }
}


class Header
{
    /**
     * @var string
     */
    public $hash;

    /**
     * @var string
     */
    public $prev_block;

    /**
     * @var string
     */
    public $merkle_root;

    /**
     * @var string
     */
    public $script;

    /**
     * @var string
     */
    public $seed;

    /**
     * @var int
     */
    public $type;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $size;

    /**
     * @var int
     */
    public $version = 1;

    /**
     * @var int
     */
    public $timestamp;

    /**
     * @var int
     */
    public $bits;

    /**
     * @var int
     */
    public $nonce;

    /**
     * @var int
     */
    public $txn_count;

    /**
     * @var int
     */
    public $advice_count;

    public function __construct($obj)
    {
        foreach ($obj AS $key => $value) $this->{$key} = $value;
    }
}