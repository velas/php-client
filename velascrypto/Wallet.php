<?php

namespace velascrypto;

use Tuupola\Base58;
use velascrypto\utils\GetBytes;

/**
 * @property array address
 * @property string base58Address
 */
class Wallet
{
    /**
     * @const string
     */
    const version = [15, 244];
    /**
     * @const int
     */
    const addressChecksumLen = 4;
    /**
     * @var int[]
     */
    public $address = [];
    public $base58Address = "";

    /**
     * Wallet constructor.
     * @param string $publicKey
     */
    function __construct(string $publicKey)
    {
        $payload = self::byteArrayToSring($this::version);
        $pub256key = hash('sha256', $publicKey);
        $ripemd160 = hash('ripemd160', hex2bin($pub256key));
        $payload .= $ripemd160;
        $firstSha256 = hash('sha256', hex2bin($payload));
        $secondSha256 = hash('sha256', hex2bin($firstSha256));
        $checksum = substr(hex2bin($secondSha256), 0, self::addressChecksumLen);
        $payload .= bin2hex($checksum);
        $this->address = $payload;
        $this->base58Address = $this->base58(hex2bin($payload));
    }

    /**
     * @param string $str
     * @return string
     */
    private function base58(string $str): string
    {
        $encoder = new Base58\PhpEncoder([
            "characters" => Base58::BITCOIN,
        ]);
        $bytes = unpack('C*', $str);
        $bytes[0] = 0;

        $leadingZeroes = 0;
        $data = [];

        for ($i = 1; $i < count($bytes); $i++) {
            if ($bytes[$i] == 0 && $i - 1 == $leadingZeroes) {
                $leadingZeroes++;
            }

            array_push($data, $bytes[$i]);
        }

        $converted = $encoder->baseConvert($data, 256, 58);

        if (0 < $leadingZeroes) {
            $converted = array_merge(
                array_fill(0, $leadingZeroes, 0),
                $converted
            );
        }

        return implode("", array_map(function ($index) {
            return Base58::BITCOIN[$index];
        }, $converted));
    }

    static private function byteArrayToSring(array $arr): string
    {
        $str = "";
        foreach ($arr as $el){
            $str .= bin2hex(pack("C*", $el));
        }
        return $str;
    }
}