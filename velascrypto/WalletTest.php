<?php

use velascrypto\Wallet;

const pubKey = "6e8012feee7af34f57b872d20fe13ea591667b04694e5a939b2f3022d2a7648c";

class WalletTest extends PHPUnit\Framework\TestCase
{

    public function test__construct()
    {
        $wallet = new Wallet(hex2bin(pubKey));
        echo $wallet->base58Address;
        $this->assertIsString($wallet->base58Address);
    }
}
