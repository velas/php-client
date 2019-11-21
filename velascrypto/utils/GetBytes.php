<?php


namespace velascrypto\utils;


class GetBytes
{
    /**
     * @param int $i
     * @return string
     */
    public function FromInt(int $i): string
    {
        return $this::Convertor($i, "L");
    }

    /**
     * @param int $i
     * @return string
     */
    public function FromUlong(int $i): string
    {
        return $this::Convertor($i, "Q");
    }

    /**
     * @param $arg
     * @param string $type
     * @return string
     */
    static private function Convertor($arg, string $type): string {
        $bytes = unpack("C*", pack($type, $arg));
        return call_user_func_array("pack", array_merge(array("C*"), $bytes));
    }
}