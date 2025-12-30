<?php
// privateprototype2025/profile/followers.php - PRODUCTION Followers
session_start();
require_once '../sql/db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT u.*, f.created_at FROM users u JOIN followers f ON u.id = f.follower_id WHERE f.user_id = ? ORDER BY f.created_at DESC");
$stmt->execute([$user_id]);
$followers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Followers (<?= count($followers) ?>)</title>
    <style>body{background:#000;color:#fff;padding:20px;font-family:-apple-system;}.follower{padding:20px;border-bottom:1px solid #333;display:flex;align-items:center;gap:15px;cursor:pointer;}</style>
</head>
<body>
    <div style="max-width:500px;margin:0 auto;">
        <h2>👥 Followers (<?= count($followers) ?>)</h2>
        <?php foreach ($followers as $follower): ?>
        <div class="follower">
            <div style="width:50px;height:50px;background:#333;border-radius:50%;"></div>
            <div>
                <div style="font-weight:600;">@<?= htmlspecialchars($follower['username_handle']) ?></div>
                <div style="color:#888;font-size:14px;">Followed <?= date('M j', strtotime($follower['created_at'])) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
