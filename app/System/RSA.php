<?php

namespace App\System;

/**
 * Class RSA
 * @package App\System
 */
class RSA
{
    const CIPHER = 'aes-256-cbc';
    const OPTION = OPENSSL_RAW_DATA;
    const SHA2LEN = 32;

    protected $key;

    public function __construct()
    {
        $this->key = $_SESSION['public_key'];var_dump($_SESSION['public_key']);
    }

    /**
     * Шифрование данных
     * @param string $plaintext - данные для шифрования
     * @return string
     */
    public function encrypt(string $plaintext)
    {
        $iv = self::generateRandomBytes();

        $ciphertext_raw = openssl_encrypt($plaintext, self::CIPHER, $this->key, self::OPTION, $iv);

        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary=true);

        return base64_encode($iv . $hmac . $ciphertext_raw);
    }

    /**
     * Дешифрование данных
     * @param string $ciphertext - данные для дешифрования
     * @return false|string
     */
    public function decrypt(string $ciphertext)
    {
        $c = base64_decode($ciphertext);

        $ivlen = self::getIvLength();
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, self::SHA2LEN);
        $ciphertext_raw = substr($c, $ivlen + self::SHA2LEN);

        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, true);

        return hash_equals($hmac, $calcmac) ?
            openssl_decrypt($ciphertext_raw, self::CIPHER, $this->key, self::OPTION, $iv) :
            false;
    }

    /**
     * Генерирование строки случайных символов
     * @param int $count - длина строки (0 - длина определяется текущим методом шифрования)
     * @param bool $encode - необходимость кодирования в base64
     * @return false|string
     */
    public static function generateRandomBytes(int $count = 0, bool $encode = false)
    {
        return $encode ?
            base64_encode(openssl_random_pseudo_bytes($count ?: self::getIvLength())) :
            openssl_random_pseudo_bytes($count ?: self::getIvLength());
    }

    /**
     * Определяет длину публичного ключа шифрования
     * @return false|int
     */
    protected static function getIvLength()
    {
        return openssl_cipher_iv_length(self::CIPHER);
    }
}
