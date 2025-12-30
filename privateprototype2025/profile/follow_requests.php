<?php
// privateprototype2025/profile/follow_requests.php - PRODUCTION Requests
session_start();
require_once '../sql/db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT u.* FROM users u JOIN follow_requests r ON u.id = r.requester_id WHERE r.user_id = ? AND r.status = 'pending'");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Follow Requests (<?= count($requests) ?>)</title></head>
<body style="background:#000;color:#fff;padding:20px;font-family:-apple-system;">
    <div style="max-width:500px;margin:0 auto;">
        <h2>📩 Follow Requests (<?= count($requests) ?>)</h2>
        <?php foreach ($requests as $request): ?>
        <div style="display:flex;align-items:center;padding:20px;background:#111;border-radius:12px;margin-bottom:15px;gap:15px;">
            <div style="width:60px;height:60px;background:#333;border-radius:50%;"></div>
            <div style="flex:1;">
                <div style="font-weight:600;font-size:18px;">@<?= htmlspecialchars($request['username_handle']) ?></div>
                <div style="color:#888;">Requested to follow you</div>
            </div>
            <div style="display:flex;gap:10px;">
                <button style="padding:10px 20px;background:#00FF88;color:#000;border:none;border-radius:8px;font-weight:600;">Accept</button>
                <button style="padding:10px 20px;background:#666;border:none;border-radius:8px;">Decline</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
