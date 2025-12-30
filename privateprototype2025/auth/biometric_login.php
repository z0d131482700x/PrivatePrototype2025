<?php
// privateprototype2025/auth/biometric_login.php - PRODUCTION
session_start();
require_once '../config/biometric_engine.php';

if (!isset($_SESSION['sovereign_authenticated'])) {
    // Require XMPP OTP for biometric setup
    if ($_POST['biometric_login']) {
        $jabber_id = $_POST['jabber_id'];
        require_once '../config/xmpp_otp.php';
        $xmpp = new XMPPOTP();
        
        $otp = $xmpp->generateOTP();
        $xmpp->sendOTP($jabber_id, $otp);
        $_SESSION['biometric_jid'] = $jabber_id;
        $_SESSION['biometric_otp'] = $otp;
        
        $error = '✅ XMPP OTP sent. Enter code below.';
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Biometric Login - PRODUCTION</title></head>
<body>
    <div style="background:#000;color:#00ff88;text-align:center;padding:100px;">
        <h2>👁️🔐 Face ID + XMPP 2FA</h2>
        
        <?php if (isset($error)): ?>
            <div style="background:#225522;padding:20px;border-radius:12px;margin:20px;">
                <?= $error ?>
                <form method="POST" style="margin-top:20px;">
                    <input type="text" name="otp" placeholder="XMPP OTP" maxlength="6" required>
                    <button>Verify + Face Scan</button>
                </form>
            </div>
        <?php else: ?>
            <form method="POST">
                <input type="email" name="jabber_id" placeholder="your@xmpp.jp" required>
                <button>🔐 Send XMPP OTP → Biometric Login</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
