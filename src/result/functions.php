<?php
/**
 * Checks whether the signature of a payment response is valid.
 *
 * @param string $responseBase64
 * @param string $signatureBase64
 * @param string $merchantSecretKey
 * @return bool
 */
function isValidSignature($responseBase64, $signatureBase64, $merchantSecretKey)
{
    $signature = hash_hmac('sha256', $responseBase64, $merchantSecretKey, $raw_output = true);
    return hash_equals($signature, base64_decode($signatureBase64));
}
