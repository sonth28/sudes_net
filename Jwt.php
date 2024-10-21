<?php
class Jwt
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;

    }

    private function base64url_encode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public function encode(array $payload)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        $header = $this->base64url_encode($header);
        $payload = json_encode($payload);
        $payload = $this->base64url_encode($payload);

        $signature = hash_hmac("sha256", $header. "." . $payload, $this->key, true);
        $signature = $this->base64url_encode($signature);
        return $header.".".$payload.".".$signature;
    }
}

?>