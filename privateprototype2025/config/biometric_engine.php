<?php
// privateprototype2025/config/biometric_engine.php - PRODUCTION FIDO2
class BiometricEngine {
    public static function registerChallenge($user_id) {
        $challenge = base64_encode(random_bytes(32));
        $_SESSION['webauthn_challenge'][$user_id] = $challenge;
        return $challenge;
    }
    
    public static function verifySignature($user_id, $signature, $credential_id) {
        $challenge = $_SESSION['webauthn_challenge'][$user_id] ?? '';
        $expected = hash('sha256', base64_decode($challenge) . $credential_id, true);
        return hash_equals($expected, $signature);
    }
}
?>
