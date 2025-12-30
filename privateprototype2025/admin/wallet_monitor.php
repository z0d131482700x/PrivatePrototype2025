<?php
// privateprototype2025/admin/wallet_monitor.php - PRODUCTION BTC Analytics
session_start();
require_once '../sql/db_connect.php';

if ($_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    die('CISO Access Only');
}

$stmt = $pdo->prepare("SELECT SUM(amount) as total, COUNT(*) as tx_count FROM transactions WHERE DATE(created_at) = CURDATE()");
$stmt->execute();
$today_stats = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head><title>Wallet Monitor</title>
    <style>body{background:#000;color:#fff;font-family:-apple-system;padding:20px;}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}</style>
</head>
<body>
    <h1>💰 BTC Transaction Analytics</h1>
    <div class="stats">
        <div style="background:#111;padding:30px;border-radius:12px;text-align:center;">
            <div style="font-size:48px;color:#00FF88;">$<?= number_format($today_stats['total'] ?? 0, 0) ?></div>
            <div>24h Volume</div>
        </div>
        <div style="background:#111;padding:30px;border-radius:12px;text-align:center;">
            <div style="font-size:48px;color:#FF00FF;"><?= $today_stats['tx_count'] ?? 0 ?></div>
            <div>Transactions</div>
        </div>
        <div style="background:#111;padding:30px;border-radius:12px;text-align:center;">
            <div style="font-size:48px;color:#00CCFF;">0</div>
            <div>Scam Alerts</div>
        </div>
    </div>
    
    <h3 style="margin:40px 0 20px 0;">Recent Transactions</h3>
    <div style="background:#111;padding:20px;border-radius:12px;">
        <table style="width:100%;border-collapse:collapse;">
            <tr style="border-bottom:1px solid #333;"><th>User</th><th>Type</th><th>Amount</th><th>Time</th></tr>
            <tr style="border-bottom:1px solid #333;"><td>@sovereign</td><td>Receive</td><td style="color:#00FF88;">₿0.001</td><td>2min ago</td></tr>
        </table>
    </div>
</body>
</html>
