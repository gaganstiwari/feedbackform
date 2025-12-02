<?php

namespace App\Services;

class SignedUrlService
{
    private $encKey;
    private $hmacKey;
    private $ttl;

    public function __construct()
    {
        $this->encKey = config('signed_url.enc_key', '12345678901234567890123456789012');
        $this->hmacKey = config('signed_url.hmac_key', 'super_secret_hmac_key');
        $this->ttl = config('signed_url.ttl', 600); // 10 minutes
    }

    /**
     * Base64 URL encode
     */
    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64 URL decode
     */
    private function base64url_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Encrypt payload
     */
    private function encrypt_payload($data, $key)
    {
        $ivlen = openssl_cipher_iv_length('AES-256-CBC');
        $iv = random_bytes($ivlen);
        $ciphertext = openssl_encrypt(json_encode($data), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return $this->base64url_encode($iv . $ciphertext);
    }

    /**
     * Decrypt payload
     */
    private function decrypt_payload($data, $key)
    {
        $data = $this->base64url_decode($data);
        $ivlen = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($data, 0, $ivlen);
        $ciphertext = substr($data, $ivlen);
        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return json_decode($decrypted, true);
    }

    /**
     * Sign data
     */
    private function sign($data, $key)
    {
        return $this->base64url_encode(hash_hmac('sha256', $data, $key, true));
    }

    /**
     * Verify signature
     */
    private function verify_signature($data, $signature, $key)
    {
        $expected = $this->sign($data, $key);
        return hash_equals($expected, $signature);
    }

    /**
     * Generate signed URL with token number and/or requestid
     */
    public function generateSignedUrl($tokenNumber = null, $baseUrl = null, $requestid = null)
    {
        if (!$baseUrl) {
            $baseUrl = url('/feedback');
        }

        // If requestid is provided but no token_number, use requestid as token_number
        if ($requestid && !$tokenNumber) {
            $tokenNumber = $requestid;
        }

        $payload = [
            'token_number' => $tokenNumber,
            'requestid' => $requestid,
            'issued_at' => time(),
            'expires_at' => time() + $this->ttl
        ];

        $token = $this->encrypt_payload($payload, $this->encKey);
        $sig = $this->sign($token, $this->hmacKey);
        
        return $baseUrl . '?token=' . urlencode($token) . '&sig=' . urlencode($sig);
    }

    /**
     * Validate and extract token number from signed URL
     */
    public function validateAndExtractToken($token, $signature)
    {
        // Verify signature
        if (!$this->verify_signature($token, $signature, $this->hmacKey)) {
            return ['valid' => false, 'error' => 'Invalid signature'];
        }

        // Decrypt payload
        $payload = $this->decrypt_payload($token, $this->encKey);
        
        if (!$payload) {
            return ['valid' => false, 'error' => 'Invalid token'];
        }

        // Check expiration
        if (isset($payload['expires_at']) && $payload['expires_at'] < time()) {
            return ['valid' => false, 'error' => 'Token expired'];
        }

        return [
            'valid' => true,
            'token_number' => $payload['token_number'] ?? null,
            'requestid' => $payload['requestid'] ?? null,
            'issued_at' => $payload['issued_at'] ?? null,
            'expires_at' => $payload['expires_at'] ?? null
        ];
    }
}

