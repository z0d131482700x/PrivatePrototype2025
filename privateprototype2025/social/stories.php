<?php
// privateprototype2025/social/stories.php - PRODUCTION Stories
session_start();
require_once '../sql/db_connect.php';

$stmt = $pdo->prepare("SELECT * FROM posts WHERE type = 'story' AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY created_at DESC");
$stmt->execute();
$stories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Stories</title></head>
<body style="background:#000;color:#fff;">
    <div style="display:flex;height:100vh;overflow-x:auto;padding:20px;gap:10px;">
        <?php foreach ($stories as $story): ?>
        <div style="width:90px;height:90px;background:#00FF88;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;border:3px solid #111;">
            📸
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
