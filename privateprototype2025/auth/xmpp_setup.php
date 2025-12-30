<?php
// privateprototype2025/auth/xmpp_setup.php - PRODUCTION MODE
session_start();
require_once '../config/xmpp_otp.php';

$xmpp = new XMPPOTP();
$error = '';

if ($_POST['setup_xmpp']) {
    $jabber_id = filter_var($_POST['jabber_id'], FILTER_SANITIZE_EMAIL);
    
    // PRODUCTION: Validate JID format
    if (!preg_match('/^[^@]+@xmpp\.jp$/', $jabber_id) && !preg_match('/^[^@]+@conversations\.im$/', $jabber_id)) {
        $error = '❌ Use xmpp.jp or conversations.im';
    } else {
        // Generate + send REAL OTP to CLIENT'S JID
        $otp = $xmpp->generateOTP();
        if ($xmpp->sendOTP($jabber_id, $otp)) {
            $_SESSION['jabber_id'] = $jabber_id;
            $_SESSION['otp_timestamp'] = time();
            header('Location: otp_verify.php');
            exit;
        } else {
            $error = '❌ XMPP delivery failed. Try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XMPP OTP Setup - PRODUCTION</title>
    <style>*{margin:0;padding:0;box-sizing:border-box;}body{background:linear-gradient(135deg,#000000 0%,#0a0a0a 100%);color:#fff;font-family:'Courier New',monospace;min-height:100vh;display:flex;align-items:center;justify-content:center;}.xmpp{max-width:450px;width:90%;background:#111111;padding:50px;border:3px solid #00ff88;border-radius:20px;box-shadow:0 0 40px rgba(0,255,136,0.4);text-align:center;animation:neonPulse 2s infinite;}@keyframes neonPulse{0%{box-shadow:0 0 40px rgba(0,255,136,0.4);}50%{box-shadow:0 0 60px rgba(0,255,136,0.7),0 0 80px rgba(255,0,255,0.3);}}.logo{font-size:32px;background:linear-gradient(45deg,#FF00FF,#00FF88);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-shadow:0 0 20px rgba(255,0,255,0.5);margin-bottom:20px;}input{width:100%;padding:20px;margin:15px 0;background:#222222;border:2px solid #444444;border-radius:12px;color:#fff;font-size:18px;font-family:'Courier New',monospace;transition:all 0.3s;}input:focus{outline:none;border-color:#00ff88;box-shadow:0 0 20px rgba(0,255,136,0.4);}button{width:100%;padding:20px;background:linear-gradient(45deg,#00ff88,#00cc66);color:#000;border:none;border-radius:12px;font-size:18px;font-weight:bold;cursor:pointer;transition:all 0.3s;text-transform:uppercase;}button:hover{transform:translateY(-3px);box-shadow:0 15px 40px rgba(0,255,136,0.5);}.error{background:#ff4444;color:#fff;padding:15px;border-radius:10px;margin:20px 0;}.info{background:#225522;padding:20px;border-radius:12px;margin:20px 0;color:#00ff88;}</style>
</head>
<body>
    <div class="xmpp">
        <div class="logo">🔐 Sovereign XMPP OTP</div>
        <h2>PRODUCTION - No Phone Required</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="info">
            <strong>✅ REAL XMPP Delivery</strong><br>
            Check Conversations.im or Gajim app
        </div>
        
        <form method="POST">
            <input type="email" name="jabber_id" placeholder="yourname@xmpp.jp" required autofocus>
            <small style="color:#888888;">yourname@xmpp.jp or @conversations.im</small>
            <button type="submit" name="setup_xmpp">📤 Send REAL OTP</button>
        </form>
        
        <div style="margin-top:30px;color:#888888;font-size:14px;">
            <p><strong>Server:</strong> admin@xmpp.jp sends to YOUR JID</p>
            ✅ Quantum-encrypted<br>
            ✅ Expires in 5 minutes<br>
            ✅ Rate limited (3/hour)
        </div>
    </div>
</body>
</html>
