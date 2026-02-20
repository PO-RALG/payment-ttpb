<?php

namespace App\Services\Payment;

class GepgCryptoService
{
    public function sign(string $data): string
    {
        $privateKeyPath = config('gepg.private_key_path');
        $passphrase = config('gepg.private_key_passphrase', '');

        if (! $privateKeyPath || ! is_file($privateKeyPath)) {
            return base64_encode(hash('sha256', $data, true));
        }

        $privateKeyContent = file_get_contents($privateKeyPath);
        if ($privateKeyContent === false) {
            return base64_encode(hash('sha256', $data, true));
        }

        $privateKey = openssl_pkey_get_private($privateKeyContent, $passphrase);
        if (! $privateKey) {
            return base64_encode(hash('sha256', $data, true));
        }

        $signature = '';
        $ok = openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        if (! $ok) {
            return base64_encode(hash('sha256', $data, true));
        }

        return base64_encode($signature);
    }

    public function verify(string $data, ?string $signature): bool
    {
        if (! config('gepg.verify_signature', false)) {
            return true;
        }

        if (! $signature) {
            return false;
        }

        $publicKeyPath = config('gepg.public_key_path');
        if (! $publicKeyPath || ! is_file($publicKeyPath)) {
            return false;
        }

        $publicKeyContent = file_get_contents($publicKeyPath);
        if ($publicKeyContent === false) {
            return false;
        }

        $publicKey = openssl_pkey_get_public($publicKeyContent);
        if (! $publicKey) {
            return false;
        }

        $decoded = base64_decode(trim($signature), true);
        if ($decoded === false) {
            return false;
        }

        $result = openssl_verify($data, $decoded, $publicKey, OPENSSL_ALGO_SHA256) === 1;
        openssl_free_key($publicKey);

        return $result;
    }
}
