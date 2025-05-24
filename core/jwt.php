<?php

// These functions are used to generate and verify JWT tokens for user authentication.
function generate_jwt($payload, $secret = 'clave_supersecreta', $exp = 3600) {
    $header = ['typ' => 'JWT', 'alg' => 'HS256'];
    $payload['exp'] = time() + $exp;

    $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
    $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
    $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $secret, true);
    $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
}

function verify_jwt($token, $secret = 'clave_supersecreta') {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;

    list($header, $payload, $signature) = $parts;
    $valid_signature = rtrim(strtr(base64_encode(hash_hmac('sha256', "$header.$payload", $secret, true)), '+/', '-_'), '=');
    if ($signature !== $valid_signature) return false;

    $payload = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    if ($payload['exp'] < time()) return false;

    return $payload;
}
