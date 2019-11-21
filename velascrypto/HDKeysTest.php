<?php

namespace velascrypto;

use PHPUnit\Framework\TestCase;

const pk = "008b178cb6e6c608ca45a2507ac225ef31dc7261d9326d0c0e2f1c7cd92079336e8012feee7af34f57b872d20fe13ea591667b04694e5a939b2f3022d2a7648c";

class HDKeysTest extends TestCase
{

    public function test__construct()
    {
        $keys = HDKeys::FromPrivateKey(pk);
        echo bin2hex($keys->getPrivateKey()) . "\n";
        echo bin2hex($keys->getPublicKey()) . "\n";
        echo bin2hex($keys->Wallet()->base58Address);
        $this->assertNotEmpty( $keys->getPrivateKey());
    }
}
