<?php
namespace System;

use phpseclib3\Crypt\RSA;
use Exceptions\CryptException;

class Crypt
{
    private ?RSA\PublicKey $public;
    private ?RSA\PrivateKey $private;
    private ?string $publicKey;
    private ?string $privateKey;

    public function __construct(string $publicKey = null, string $privateKey = null)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function generatePair(int $bits = 2048)
    {
        $this->private = RSA::createKey(2048);
        $this->public = $this->private->getPublicKey();

        $this->privateKey = $this->private->toString(
            'PKCS8'/*,
            [
                'encryptionAlgorithm' => 'id-PBES2',
                'encryptionScheme'    => 'aes256-CBC-PAD',
                'PRF'                 => 'id-hmacWithSHA512-256',
                'iterationCount'      => 4096
            ]*/
        );
        $this->publicKey = $this->public->toString('PKCS8');

        return $this;
    }

    /**
     * @throws \Exceptions\CryptException
     */
    public function load(int $id)
    {
        if (!is_file(DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "private.pem") ||
            !is_file(DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "public.pem")
        ) throw new CryptException('Не найдены файлы с ключами', 523);

        $this->privateKey = file_get_contents(DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "private.pem");
        $this->publicKey = file_get_contents(DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "public.pem");
        return $this;
    }

    public function save(int $id)
    {
        if (!is_dir(DIR_CERTIFICATES)) mkdir(DIR_CERTIFICATES);
        if (!is_dir(DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $id)) mkdir(DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $id);
        file_put_contents(DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "public.pem", $this->publicKey);
        file_put_contents(DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "private.pem", $this->privateKey);
        return $this;
    }

    public function encryptByPublicKey(string $plaintext, bool $encodeBase64 = true)
    {
        if (empty($this->publicKey)) return $plaintext;
        openssl_public_encrypt($plaintext, $encrypted, $this->publicKey);
        return $encodeBase64 ? base64_encode($encrypted) : $encrypted;
    }

    public function encryptByPrivateKey(string $plaintext, bool $encodeBase64 = true)
    {
        if (empty($this->privateKey)) return $plaintext;
        openssl_private_encrypt($plaintext, $encrypted, $this->privateKey);
        return $encodeBase64 ? base64_encode($encrypted) : $encrypted;
    }

    public function decryptByPublicKey(string $ciphertext, bool $encodedBase64 = true)
    {
        if (empty($this->publicKey)) return $ciphertext;
        openssl_public_decrypt($encodedBase64 ? base64_decode($ciphertext) : $ciphertext, $out, $this->publicKey);
        return $out;
    }

    public function decryptByPrivateKey(string $ciphertext, bool $encodedBase64 = true)
    {
        if (empty($this->privateKey)) return $ciphertext;
        openssl_private_decrypt($encodedBase64 ? base64_decode($ciphertext) : $ciphertext, $out, $this->privateKey);
        return $out;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @param string|null $publicKey
     */
    public function setPublicKey(?string $publicKey): void
    {
        $this->publicKey = $publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param string|null $privateKey
     */
    public function setPrivateKey(?string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }
}
