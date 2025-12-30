<?php
// privateprototype2025/profile/close_friends.php - PRODUCTION Close Friends
session_start();
require_once '../sql/db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM close_friends WHERE user_id = ? UNION SELECT * FROM close_friends WHERE friend_id = ? AND user_id = ?");
$stmt->execute([$user_id, $user_id, $user_id]);
$close_friends = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Close Friends</title></head>
<body style="background:#000;color:#fff;padding:20px;font-family:-apple-system;">
    <div style="max-width:500px;margin:0 auto;">
        <h2>💚 Close Friends</h2>
        <p style="color:#888;">Green border stories & exclusive posts</p>
        
        <?php foreach ($close_friends as $friend): ?>
        <div style="display:flex;align-items:center;padding:20px;background:#111;border-left:4px solid #00FF88;border-radius:0 12px 12px 0;margin-bottom:10px;">
            <div style="width:50px;height:50px;background:#333;border-radius:50%;margin-right:15px;"></div>
            <div style="flex:1;">
                <div style="font-weight:600;">@<?= htmlspecialchars($friend['username_handle'] ?? 'friend') ?></div>
            </div>
            <button style="padding:8px 16px;background:#FF4444;color:#fff;border:none;border-radius:8px;font-size:14px;">Remove</button>
        </div>
        <?php endforeach; ?>
        
        <button style="width:100%;padding:15px;background:linear-gradient(135deg,#00FF88,#00CC66);color:#000;border:none;border-radius:12px;font-weight:600;margin-top:20px;">Add Close Friend</button>
    </div>
</body>
</html>
