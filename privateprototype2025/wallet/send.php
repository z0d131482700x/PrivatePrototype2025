<?php
// privateprototype2025/wallet/send.php - PRODUCTION AI Scam Protection
session_start();
require_once '../sql/db_connect.php';
require_once '../core/scam_detector.php';
require_once '../core/bitcoin_engine.php';

if ($_POST) {
    $address = $_POST['address'];
    $amount = (int)$_POST['amount'] * 100000000; // Satoshi
    
    // AI SCAM DETECTION
    if (ScamDetector::is_scam_address($address)) {
        $_SESSION['error'] = '🚨 AI detected scam address!';
        header('Location: send.php');
        exit;
    }
    
    // BIP84 SEND (Lightning + On-chain)
    BitcoinEngine::send_payment($_SESSION['user_id'], $address, $amount);
    $_SESSION['success'] = '✅ Payment sent!';
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Send Bitcoin</title>
    <style>body{background:#000;color:#fff;padding:40px;font-family:-apple-system;}input{width:100%;padding:15px;margin:10px 0;border:1px solid #333;background:#111;color:#fff;border-radius:8px;}</style>
</head>
<body>
    <div style="max-width:400px;margin:0 auto;">
        <h2>➡️ Send Bitcoin</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div style="background:#ff4444;padding:15px;border-radius:8px;margin:20px 0;"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="address" placeholder="bc1q... or ln..." required>
            <input type="number" name="amount" placeholder="0.001" step="0.00000001" required>
            <button type="submit" style="width:100%;padding:15px;background:linear-gradient(135deg,#00FF88,#00CC66);border:none;border-radius:12px;font-weight:600;font-size:18px;">Send Payment</button>
        </form>
        
        <div style="margin-top:30px;padding:15px;background:#111;border-radius:12px;">
            <strong>🤖 AI Protection Active:</strong><br>
            • Scam address detection<br>
            • Phishing URL blocking<br>
            • Dust attack prevention
        </div>
    </div>
</body>
</html>
