<?php
// privateprototype2025/social/profile_grid.php - PRODUCTION 3x3 Layout
session_start();
require_once '../sql/db_connect.php';

if (!isset($_SESSION['sovereign_authenticated'])) {
    header('Location: ../index.php');
    exit;
}

$username = $_GET['username'] ?? $_SESSION['username_handle'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE username_handle = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC LIMIT 9");
$stmt->execute([$user['id']]);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>@<?= htmlspecialchars($username) ?></title>
    <style>/* Same Instagram style */</style>
</head>
<body>
    <div class="profile-header">
        <div class="avatar" style="width:120px;height:120px;background:#333;border-radius:50%;margin:0 auto;"></div>
        <h1><?= htmlspecialchars($username) ?></h1>
        <div style="display:flex;gap:20px;justify-content:center;margin:20px 0;">
            <div><strong><?= $user['posts_count'] ?></strong> posts</div>
            <div><strong><?= $user['followers_count'] ?></strong> followers</div>
            <div><strong><?= $user['following_count'] ?></strong> following</div>
        </div>
        <div style="text-align:center;color:#ccc;"><?= htmlspecialchars($user['bio']) ?></div>
    </div>
    
    <div class="profile-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:2px;">
        <?php foreach ($posts as $post): ?>
        <div class="grid-post" style="aspect-ratio:1;background:#222;"></div>
        <?php endforeach; ?>
    </div>
</body>
</html>
