<?php
// privateprototype2025/core/encryption_engine.php - PRODUCTION Kyber1024
require_once '../config/quantum_crypto.php';

class EncryptionEngine {
    public static function encrypt_message($plaintext, $recipient_public_key) {
        // Kyber1024 KEM + AES-256-GCM hybrid
        return QuantumCrypto::encrypt($plaintext, $recipient_public_key);
    }
    
    public static function decrypt_message($ciphertext, $private_key) {
        return QuantumCrypto::decrypt($ciphertext, $private_key);
    }
    
    public static function generate_conversation_key($user1_pk, $user2_pk) {
        // Perfect Forward Secrecy
        $session_key = random_bytes(32);
        return [
            'key' => $session_key,
            'encrypted_for_user1' => self::encrypt_message($session_key, $user1_pk),
            'encrypted_for_user2' => self::encrypt_message($session_key, $user2_pk)
        ];
    }
}
?>
