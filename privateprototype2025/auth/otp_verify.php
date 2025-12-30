<?php
// privateprototype2025/auth/otp_verify.php - PRODUCTION MODE
session_start();
require_once '../config/xmpp_otp.php';

$xmpp = new XMPPOTP();
$error = '';

if ($_POST['verify_otp']) {
    $jabber_id = $_SESSION['jabber_id'] ?? '';
    $user_otp = str_pad($_POST['otp'], 6, '0', STR_PAD_LEFT);
    
    // PRODUCTION: 5min expiry + rate limit
    if (time() - ($_SESSION['otp_timestamp'] ?? 0) > 300) {
        $error = '❌ OTP expired (5min). Resend.';
    } elseif ($xmpp->verifyOTP($jabber_id, $user_otp)) {
        $_SESSION['xmpp_verified'] = true;
        header('Location: quantum_register.php');
        exit;
    } else {
        $error = '❌ Invalid OTP';
        $_SESSION['failed_attempts'] = ($_SESSION['failed_attempts'] ?? 0) + 1;
        
        if ($_SESSION['failed_attempts'] >= 3) {
            unset($_SESSION['jabber_id']);
            header('Location: xmpp_setup.php?error=too_many_attempts');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify XMPP OTP - PRODUCTION</title>
    <style>/* Same neon style as above */</style>
</head>
<body>
    <div class="otp-container">
        <div class="logo">📱 Verify OTP</div>
        
        <div class="jabber">
            📨 <?= htmlspecialchars($_SESSION['jabber_id']) ?>
        </div>
        
        <p style="color:#888888;">Check your Conversations/Gajim app</p>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="otp" maxlength="6" class="otp-input" 
                   placeholder="123456" required autofocus autocomplete="one-time-code">
            <button type="submit" name="verify_otp">✅ Verify OTP</button>
        </form>
        
        <div style="margin-top:30px;">
            <a href="?resend=1" style="color:#ffaa00;">📤 Resend (<?= 300 - (time() - ($_SESSION['otp_timestamp'] ?? 0)) ?>s)</a>
        </div>
    </div>
</body>
</html>
