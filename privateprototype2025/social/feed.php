<?php
// privateprototype2025/social/feed.php - PRODUCTION Instagram Feed
session_start();
require_once '../sql/db_connect.php';
require_once '../config/sovereign_config.php';
require_once '../core/shell_killer.php';
ShellKiller::enforce_national_security();

if (!isset($_SESSION['sovereign_authenticated'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username_handle'];

// PRODUCTION: Sovereign feed algorithm (Instagram-style)
$stmt = $pdo->prepare("
    SELECT p.*, u.username_handle, u.profile_photo, u.followers_count,
           (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) as like_count,
           (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) as comment_count
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    LEFT JOIN followers f ON (f.following_id = p.user_id AND f.follower_id = ? AND f.status = 'approved')
    WHERE (p.privacy = 'public' OR p.user_id = ? OR f.following_id IS NOT NULL)
    ORDER BY p.created_at DESC 
    LIMIT 20
");
$stmt->execute([$user_id, $user_id]);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed - privateprototype2025</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{background:#000;color:#fff;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;min-height:100vh;}
        .header{position:fixed;top:0;width:100%;height:60px;background:#000;border-bottom:1px solid #262626;display:flex;align-items:center;justify-content:center;z-index:100;}
        .logo{font-size:24px;background:linear-gradient(45deg,#00FF88,#FF00FF);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
        .feed{padding-top:60px;padding-bottom:100px;overflow-y:auto;max-height:100vh;}
        .post{margin:20px 0;background:#111;border-radius:12px;overflow:hidden;border:1px solid #262626;}
        .post-header{padding:15px;display:flex;align-items:center;gap:12px;}
        .avatar{width:40px;height:40px;border-radius:50%;background:#333;}
        .username{font-weight:600;color:#fff;}
        .post-media{position:relative;width:100%;height:400px;background:linear-gradient(45deg,#00FF88,#FF00FF);display:flex;align-items:center;justify-content:center;color:#888;font-size:18px;}
        .post-actions{padding:15px;display:flex;gap:15px;}
        .action-btn{background:none;border:none;color:#fff;font-size:24px;cursor:pointer;}
        .action-btn.active{color:#00FF88;}
        .caption{padding:0 15px 15px;color:#ccc;line-height:1.4;}
        .bottom-nav{position:fixed;bottom:0;width:100%;height:60px;background:#000;border-top:1px solid #262626;display:flex;justify-content:space-around;align-items:center;}
        .nav-item{color:#888;font-size:28px;}
        .nav-item.active{color:#00FF88;}
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">privateprototype2025</div>
    </div>
    
    <div class="feed">
        <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <div class="avatar" style="background-image:url('../assets/images/profile_avatars/<?= $post['profile_photo'] ?>')"></div>
                <span class="username"><?= htmlspecialchars($post['username_handle']) ?></span>
            </div>
            <div class="post-media">
                <?= $post['type'] === 'reel' ? '🎥 REEL' : '📸 PHOTO' ?>
            </div>
            <div class="post-actions">
                <button class="action-btn like <?= isset($post['liked']) ? 'active' : '' ?>">❤️</button>
                <button class="action-btn comment">💬</button>
                <button class="action-btn share">📤</button>
                <span style="margin-left:auto;color:#888;"><?= $post['like_count'] ?> likes</span>
            </div>
            <?php if ($post['caption']): ?>
            <div class="caption"><?= htmlspecialchars($post['caption']) ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="bottom-nav">
        <div class="nav-item active">🏠</div>
        <div class="nav-item">🔍</div>
        <div class="nav-item">➕</div>
        <div class="nav-item">🔔</div>
        <div class="nav-item">👤</div>
    </div>
</body>
</html>
