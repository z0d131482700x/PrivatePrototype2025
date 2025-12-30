<?php
// privateprototype2025/social/explore.php - PRODUCTION AI Explore
session_start();
require_once '../sql/db_connect.php';

$stmt = $pdo->prepare("SELECT p.*, u.username_handle FROM posts p JOIN users u ON p.user_id = u.id WHERE p.privacy = 'public' ORDER BY p.views_count DESC, p.created_at DESC LIMIT 30");
$stmt->execute();
$explore = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Explore</title></head>
<body style="background:#000;color:#fff;padding:20px;">
    <h2 style="margin-bottom:30px;">🔍 Discover</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:15px;">
        <?php foreach ($explore as $item): ?>
        <div style="background:#111;border-radius:12px;overflow:hidden;border:1px solid #333;">
            <div style="height:200px;background:#333;"></div>
            <div style="padding:15px;">
                <div style="font-weight:600;margin-bottom:5px;">@<?= htmlspecialchars($item['username_handle']) ?></div>
                <div style="color:#888;font-size:14px;"><?= htmlspecialchars(substr($item['caption'] ?? '', 0, 100)) ?>...</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
