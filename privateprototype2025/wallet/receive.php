<?php
// privateprototype2025/wallet/receive.php - PRODUCTION Lightning
session_start();
require_once '../sql/db_connect.php';
require_once '../core/bitcoin_engine.php';

$user_id = $_SESSION['user_id'];
$invoice = BitcoinEngine::create_lightning_invoice($user_id, 1000000); // 0.01 BTC
?>
<!DOCTYPE html>
<html>
<head><title>Receive</title>
    <style>body{background:#000;color:#fff;padding:40px;font-family:-apple-system;}.container{max-width:400px;margin:0 auto;text-align:center;}</style>
</head>
<body>
    <div class="container">
        <h2>⬇️ Receive Payment</h2>
        <div style="width:200px;height:200px;margin:30px auto;background:#333;border-radius:20px;display:flex;align-items:center;justify-content:center;">
            <canvas id="invoiceQR" width="180" height="180"></canvas>
        </div>
        <div style="background:#111;padding:20px;border-radius:12px;margin:20px 0;">
            <div style="font-family:monospace;font-size:12px;word-break:break-all;padding:10px;background:#222;border-radius:8px;"><?= $invoice ?></div>
        </div>
        <div style="font-size:24px;font-weight:600;color:#00FF88;">₿ 0.01000000</div>
        <p style="color:#888;">Lightning Invoice (Instant)</p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>QRCode.toCanvas(document.getElementById('invoiceQR'), '<?= $invoice ?>');</script>
</body>
</html>
