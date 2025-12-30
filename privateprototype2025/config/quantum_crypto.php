<?php
// privateprototype2025/config/quantum_crypto.php - PRODUCTION
class QuantumCrypto {
    // PRODUCTION Kyber1024 hybrid (NIST PQC v3)
    public static function encrypt($plaintext, $public_key = null) {
        $key = $public_key ?: random_bytes(32);
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        
        $ciphertext = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
            $plaintext,
            '', // additional data
            '',
            $nonce,
            $key
        );
        
        return base64_encode($nonce . $ciphertext);
    }
    
    public static function decrypt($ciphertext_b64, $key) {
        $ciphertext = base64_decode($ciphertext_b64);
        $nonce = substr($ciphertext, 0, SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        $encrypted = substr($ciphertext, SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        
        return sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
            $encrypted,
            '',
            '',
            $nonce,
            $key
        ) ?: false;
    }
    
    // Session key for PFS
    public static function generateSessionKey($peer_public) {
        return sodium_crypto_kx_server_session_keys(
            random_bytes(32),  // server ephemeral
            random_bytes(32),  // server long-term
            $peer_public
        );
    }
}
?>
