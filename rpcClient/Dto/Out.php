<?php


namespace rpcClient\Dto;

use Exception;

class Out
{
    /**
     * @var string
     */
    public $hash;

    /**
     * @var int
     */
    public $index;

    /**
     * @var int
     */
    public $value;

    /**
     * Out constructor.
     * @param $body
     * @throws Exception
     */
    public function __construct($body)
    {
        if (is_string($body->hash)){
            $this->hash = $body->hash;
        }else {
            throw new Exception("{$body->hash} isn't string");
        }
        if (is_int($body->index)){
            $this->index = $body->index;
        }else {
            throw new Exception("{$body->index} isn't integer");
        }
        if (is_int($body->value)){
            $this->value = $body->value;
        } else {
            throw new Exception("{$body->value} isn't integer");
        }
    }

    /**
     * @param array $body
     * @return Out[]
     * @throws Exception
     */
    static function FromArray(array $body) {
        $arr = [];
        foreach ($body as $el) {
            array_push($arr, new Out($el));
        }
        return $arr;
    }
}