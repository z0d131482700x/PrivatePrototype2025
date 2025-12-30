<?php
// privateprototype2025/profile/following.php - PRODUCTION Following
session_start();
require_once '../sql/db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT u.* FROM users u JOIN followers f ON u.id = f.user_id WHERE f.follower_id = ? ORDER BY f.created_at DESC LIMIT 50");
$stmt->execute([$user_id]);
$following = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Following (<?= count($following) ?>)</title>
    <style>body{background:#000;color:#fff;padding:20px;font-family:-apple-system;}.user{padding:20px;border-bottom:1px solid #333;display:flex;align-items:center;gap:15px;}</style>
</head>
<body>
    <div style="max-width:500px;margin:0 auto;">
        <h2>❤️ Following (<?= count($following) ?>)</h2>
        <?php foreach ($following as $user): ?>
        <div class="user">
            <div style="width:50px;height:50px;background:#333;border-radius:50%;"></div>
            <div style="flex:1;">
                <div style="font-weight:600;">@<?= htmlspecialchars($user['username_handle']) ?></div>
                <div style="color:#888;font-size:14px;"><?= htmlspecialchars($user['bio'] ?? '') ?></div>
            </div>
            <button style="padding:8px 16px;background:#FF4444;color:#fff;border:none;border-radius:8px;font-size:14px;">Unfollow</button>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
