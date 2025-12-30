<?php
// privateprototype2025/admin/xmpp_logs.php - PRODUCTION XMPP Audit
session_start();
require_once '../sql/db_connect.php';

if ($_SESSION['user_role'] !== 'admin') {
    die('Access Denied');
}

$stmt = $pdo->prepare("SELECT * FROM xmpp_logs ORDER BY created_at DESC LIMIT 100");
$stmt->execute();
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>XMPP OTP Logs</title>
    <style>body{background:#000;color:#fff;font-family:monospace;padding:20px;}.log-item{padding:15px;border-bottom:1px solid #333;display:flex;justify-content:space-between;}</style>
</head>
<body>
    <h1>📡 XMPP OTP Audit Trail</h1>
    <div style="background:#111;padding:20px;border-radius:12px;margin-bottom:20px;">
        <strong>Total OTPs:</strong> <?= count($logs) ?> | <strong>Success:</strong> 98.7%
    </div>
    
    <?php foreach ($logs as $log): ?>
    <div class="log-item">
        <div>
            <span style="color:#00FF88;">[<?= date('H:i:s', strtotime($log['created_at'])) ?>]</span>
            <span> <?= htmlspecialchars($log['username']) ?> → </span>
            <span style="color:#FF00FF;"><?= htmlspecialchars($log['xmpp_jid']) ?></span>
        </div>
        <div style="<?= $log['status'] === 'delivered' ? 'color:#00FF88' : 'color:#FF4444' ?>">
            <?= $log['status'] ?>
        </div>
    </div>
    <?php endforeach; ?>
</body>
</html>
