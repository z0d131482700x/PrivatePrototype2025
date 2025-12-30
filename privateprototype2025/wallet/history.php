<?php
// privateprototype2025/wallet/history.php - PRODUCTION Transaction History
session_start();
require_once '../sql/db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Transaction History</title>
    <style>body{background:#000;color:#fff;padding:20px;font-family:-apple-system;}.tx-item{padding:20px;border-bottom:1px solid #333;display:flex;justify-content:space-between;align-items:center;}</style>
</head>
<body>
    <div style="max-width:500px;margin:0 auto;">
        <h2>📊 Transaction History</h2>
        <?php foreach ($history as $tx): ?>
        <div class="tx-item">
            <div>
                <div style="font-weight:600;"><?= $tx['type'] === 'send' ? '➡️ Sent' : '⬇️ Received' ?></div>
                <div style="color:#888;font-size:14px;"><?= date('M j, H:i', strtotime($tx['created_at'])) ?></div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:24px;font-weight:600;color:<?= $tx['type'] === 'send' ? '#FF4444' : '#00FF88' ?>">
                    ₿ <?= number_format($tx['amount'] / 100000000, 6) ?>
                </div>
                <div style="font-size:12px;color:#888;">txid: <?= substr($tx['txid'], 0, 16) ?>...</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
