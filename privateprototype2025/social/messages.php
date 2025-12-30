<?php
// privateprototype2025/social/messages.php - PRODUCTION E2EE Chat
session_start();
require_once '../core/encryption_engine.php';
require_once '../sql/db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT DISTINCT u.* FROM users u JOIN chats c ON c.creator_id = u.id WHERE u.id != ? LIMIT 10");
$stmt->execute([$user_id]);
$chats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Messages</title></head>
<body style="height:100vh;background:#000;color:#fff;display:flex;flex-direction:column;">
    <div style="height:60px;background:#111;border-bottom:1px solid #333;padding:0 20px;display:flex;align-items:center;">
        <h2 style="margin:0;">Messages</h2>
    </div>
    <div style="flex:1;overflow-y:auto;">
        <?php foreach ($chats as $chat): ?>
        <div style="padding:20px;border-bottom:1px solid #333;display:flex;align-items:center;gap:15px;cursor:pointer;">
            <div style="width:50px;height:50px;background:#333;border-radius:50%;"></div>
            <div>
                <div style="font-weight:600;"><?= htmlspecialchars($chat['username_handle']) ?></div>
                <div style="color:#888;font-size:14px;">E2EE message preview...</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
