## Installation
## Use
#### Generate keys
generate keys from random: 
```php
$keys = HDKeys::GenerateRandom();
```
generate keys from seed(**NOT IMPLEMENTED YET**): 
```php
$keys = HDKeys::FromSeed($seed);
```
create keys from private key: 
```php
$keys = HDKeys::FromPrivateKey($pk);
```

____

### Wallet

#### Create wallet
сreate wallet from hd keys:
```php
$keys = new \velascrypto\HDKeys($keypair);
$wallet = $keys->Wallet();
```
сreate wallet from public key:
```php
$wallet = new Wallet(hex2bin($pubKey));
```
#### Get balance
get balance by wallet address:
```php
$node_url = "https://testnet.velas.com/api/v1/" //testnet
$client = new HttpClient($node_url);
$balance = $client::$wallet->GetBalance($address); 
```
#### Get unspents
get unspents by wallet address:
```php
$client = new HttpClient($node_url);
$unspents = $client::$wallet->GetUnspent($address);
```
#### Create transaction
create, sign and convert transaction to json:
```php
$tx = new Transaction($unspent, $amount, $keypair, $from, $to, $commission);
$tx = $tx->Sign();
$tx->ToJSON();
```
#### Validate transaction
validate transaction
```php
$client = new HttpClient($node_url);
$unspents = $client->GetUnspent($address);
$unspent = $unspents[0]; // unspent value must greater then amount plus commission
$keypair = HDKeys::FromPrivateKey(pk);
$tx = new Transaction($unspent, $amount, $keypair, $from, $to, $commision);
$tx = $tx->Sign();
echo "tx: " . $tx->ToJSON() . "\n";
$result = $client::$wallet->Validate($tx); // ok or not ok
```
#### Send Transaction
broadcast transaction(send to net):
```php
$client = new HttpClient($node_url);
$unspents = $client->GetUnspent($address);
$unspent = $unspents[0]; // unspent value must greater then amount plus commission
$keypair = HDKeys::FromPrivateKey(pk);
$tx = new Transaction($unspent, $amount, $keypair, $from, $to, $commision);
$tx = $tx->Sign();
echo "tx: " . $tx->ToJSON() . "\n";
$result = $client::$wallet->Send($tx); // ok or not ok
```

____

### Transactions

#### Get transactions by block
get transaction hashes in blocks of range from some height to last block
```php
$client = new HttpClient(url);
$height = 0;
$txs = $client::$txs->getByHeight($height);
echo json_encode($txs);
```

#### Get Detail of transactions by hashes

```php
$client = new HttpClient(url);
$hashes = [
    "781b12dbe9a4e2546908759f00fdbda122cea4d432a4583dc1a2e3d4d6f66f18",
];
$txs = $client::$txs->getByHashes($hashes);
```