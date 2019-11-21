<?php


namespace rpcClient\Dto;


class NodeInfo
{
    /**
     * @var P2PPeer
     */
    public $p2p_info;

    /**
     * @var P2PPeer[]
     */
    public $p2p_peers;
    /**
     * @var BlockchainInfo
     */
    public $blockchain;

    public function __construct($obj)
    {
        if ($obj->p2p_info) {
            $this->p2p_info = $obj->p2p_info;
        }
        if ($obj->p2p_peers) {
            $this->p2p_peers = $obj->p2p_peers;
        }
        if ($obj->blockchain) {
            $this->blockchain = $obj->blockchain;
        }
    }

}

class P2PPeer
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $addr;

}

class BlockchainInfo
{
    /**
     * @var string
     */
    public $height;
    /**
     * @var string
     */
    public $current_hash;
    /**
     * @var string
     */
    public $current_epoch;
}