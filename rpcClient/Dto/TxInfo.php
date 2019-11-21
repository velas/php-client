<?php


namespace rpcClient\Dto;


use velascrypto\TransactionDto;

class TxInfo extends TransactionDto
{
    /**
     * Hash of confirmed block
     * @var string
     */
    public $block;
    /**
     * Height of confirmed block
     * @var int
     */
    public $confirmed;
    /**
     * Timestamp, when tx was confirmed
     * @var int
     */
    public $confirmed_timestamp;
    /**
     * @var int
     */
    public $size;
    /**
     * @var int
     */
    public $total;

    public function __construct($obj)
    {
        foreach ($obj AS $key => $value) $this->{$key} = $value;
    }
}