<?php

namespace App\Services;

class JwtService
{
    private $secret;

    public function __construct()
    {
        $this->secret = $_ENV['JWT_SECRET'] ?? 'your-secret-key-change-this';
    }

    public function generate($payload, $expiresIn = 86400)
    {
        $header = $this->base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiresIn;
        $payload = $this->base64UrlEncode(json_encode($payload));
        
        $signature = $this->base64UrlEncode(hash_hmac('sha256', "$header.$payload", $this->secret, true));
        
        return "$header.$payload.$signature";
    }

    public function verify($token)
    {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;
        
        $validSignature = $this->base64UrlEncode(hash_hmac('sha256', "$header.$payload", $this->secret, true));
        
        if ($signature !== $validSignature) {
            return false;
        }

        $payload = json_decode($this->base64UrlDecode($payload), true);
        
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
