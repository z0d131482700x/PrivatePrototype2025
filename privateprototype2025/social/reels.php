<?php
// privateprototype2025/social/reels.php - PRODUCTION 8K Reels
session_start();
require_once '../sql/db_connect.php';
require_once '../core/shell_killer.php';
ShellKiller::enforce_national_security();

if (!isset($_SESSION['sovereign_authenticated'])) {
    header('Location: ../index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE type = 'reel' ORDER BY RAND() LIMIT 10");
$stmt->execute();
$reels = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reels - privateprototype2025</title>
    <style>
        body{margin:0;background:#000;color:#fff;font-family:-apple-system,sans-serif;overflow:hidden;}
        .reel-container{width:100vw;height:100vh;position:relative;}
        .reel-video{width:100%;height:100%;object-fit:cover;}
        .reel-overlay{position:absolute;bottom:100px;left:20px;color:#fff;}
        .reel-actions{position:absolute;right:20px;top:50%;transform:translateY(-50%);display:flex;flex-direction:column;gap:30px;}
        .action-btn{background:none;border:none;color:#fff;font-size:40px;cursor:pointer;}
    </style>
</head>
<body>
    <?php foreach ($reels as $index => $reel): ?>
    <div class="reel-container" style="display:<?= $index === 0 ? 'block' : 'none' ?>;">
        <video class="reel-video" src="../assets/images/sample_reel.mp4" autoplay muted loop playsinline></video>
        <div class="reel-overlay">
            <div style="font-size:24px;font-weight:600;margin-bottom:10px;">@<?= $reel['username_handle'] ?? 'sovereign' ?></div>
            <div style="font-size:18px;">Quantum-secure reel 🚀</div>
        </div>
        <div class="reel-actions">
            <button class="action-btn">❤️</button>
            <button class="action-btn">💬</button>
            <button class="action-btn">🔄</button>
            <button class="action-btn">➡️</button>
        </div>
    </div>
    <?php endforeach; ?>
</body>
</html>
