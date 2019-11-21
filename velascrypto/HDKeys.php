<?php


namespace velascrypto;

use velascrypto\utils\GetBytes;

class HDKeys
{
    /**
     * @var string
     */
    private $keyPair;

    /**
     * HDKeys constructor. Generate HDKeys from sodium keypair
     * @param string $keyPair
     */
    function __construct(string $keyPair)
    {
        $this->keyPair = $keyPair;
    }

    /**
     * Generate random hd keys
     * @return HDKeys
     */
    static public function GenerateRandom(): HDKeys {
        $keyPair = sodium_crypto_sign_keypair();
        return new HDKeys($keyPair);
    }

    /**
     * Create hd keys from private key
     * @param string $privateKeyHex
     * @return HDKeys
     */
    static public function FromPrivateKey(string $privateKeyHex): HDKeys {
        $privateKey = hex2bin($privateKeyHex);
        $publicKey = sodium_crypto_sign_publickey_from_secretkey($privateKey);
        return new HDKeys(sodium_crypto_sign_keypair_from_secretkey_and_publickey($privateKey, $publicKey));
    }

    // TODO need make correct
    static public function FromSeed(string $seed): HDKeys {
        $keyPair = sodium_crypto_box_seed_keypair($seed);
        return new HDKeys($keyPair);
    }

    /**
     * Generate wallet(address) from hd keys
     * @return Wallet
     */
    public function Wallet(): Wallet
    {
        $publicKey = $this->getPublicKey();
        $a = bin2hex($publicKey);
        return new Wallet($publicKey);
    }

    /**
     * get public key(binary string)
     * @return string
     */
    public function getPublicKey(): string
    {
        return sodium_crypto_sign_publickey($this->keyPair);
    }

    /**
     * get private key(binary string)
     * @return string
     */
    public function getPrivateKey(): string
    {
        return sodium_crypto_sign_secretkey($this->keyPair);
    }
}