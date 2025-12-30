<?php
// privateprototype2025/wallet/dashboard.php - PRODUCTION BIP84 Wallet
session_start();
require_once '../sql/db_connect.php';
require_once '../core/bitcoin_engine.php';
require_once '../auth/quantum_register.php';

if (!isset($_SESSION['sovereign_authenticated'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id = ?");
$stmt->execute([$user_id]);
$wallet = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wallet - privateprototype2025</title>
    <style>
        body{margin:0;background:linear-gradient(135deg,#000,#1a0033);color:#fff;font-family:-apple-system,sans-serif;}
        .container{max-width:400px;margin:0 auto;padding:20px;}
        .balance{display:flex;justify-content:space-between;align-items:center;font-size:48px;font-weight:600;margin:40px 0;}
        .address{display:flex;align-items:center;gap:10px;background:#111;padding:15px;border-radius:12px;margin:20px 0;}
        .qr-canvas{width:100px;height:100px;background:#333;border-radius:8px;}
        .actions{display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:30px;}
        .btn{padding:15px;border:none;border-radius:12px;font-weight:600;cursor:pointer;font-size:16px;}
        .send-btn{background:linear-gradient(135deg,#00FF88,#00CC66);color:#000;}
        .receive-btn{background:linear-gradient(135deg,#FF00FF,#CC00CC);color:#fff;}
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align:center;margin-bottom:10px;">💰 Wallet</h1>
        <div class="balance">
            <span>₿ <?= number_format($wallet['balance_sats'] / 100000000, 6) ?></span>
            <span style="font-size:24px;color:#00FF88;">$<?= number_format($wallet['balance_usd'], 0) ?></span>
        </div>
        
        <div class="address">
            <canvas class="qr-canvas" id="addressQR"></canvas>
            <div>
                <div style="font-size:14px;color:#888;">Receiving</div>
                <div style="font-family:monospace;font-size:12px;word-break:break-all;" id="walletAddress"><?= $wallet['receive_address'] ?></div>
            </div>
        </div>
        
        <div class="actions">
            <button class="btn send-btn" onclick="location.href='send.php'">➡️ Send</button>
            <button class="btn receive-btn" onclick="location.href='receive.php'">⬇️ Receive</button>
        </div>
        
        <div style="margin-top:40px;">
            <a href="history.php" style="color:#00FF88;font-size:14px;">📊 Transaction History</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        QRCode.toCanvas(document.getElementById('addressQR'), '<?= $wallet['receive_address'] ?>', {width:100});
    </script>
</body>
</html>
