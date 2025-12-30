<?php
// privateprototype2025/profile/bio.php - PRODUCTION Instagram Bio
session_start();
require_once '../sql/db_connect.php';

if (!isset($_SESSION['sovereign_authenticated'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_POST) {
    $bio = substr($_POST['bio'], 0, 500);
    $stmt = $pdo->prepare("UPDATE users SET bio = ? WHERE id = ?");
    $stmt->execute([$bio, $user_id]);
    header('Location: bio.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Bio - privateprototype2025</title>
    <style>
        body{margin:0;background:#000;color:#fff;font-family:-apple-system,sans-serif;padding:40px;}
        .container{max-width:500px;margin:0 auto;}
        textarea{width:100%;height:120px;background:#111;border:1px solid #333;color:#fff;padding:15px;border-radius:12px;font-family:inherit;}
        .badges{display:flex;gap:10px;margin:20px 0;flex-wrap:wrap;}
        .badge{background:#333;padding:8px 12px;border-radius:20px;font-size:14px;}
        .nft-badge{background:linear-gradient(135deg,#00FF88,#00CC66);color:#000;}
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ Edit Bio</h2>
        <form method="POST">
            <textarea name="bio" placeholder="Tell us about yourself..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            <p style="color:#888;font-size:14px;margin:5px 0;">500 characters remaining</p>
            <button type="submit" style="width:100%;padding:15px;background:linear-gradient(135deg,#00FF88,#00CC66);border:none;border-radius:12px;font-weight:600;font-size:16px;">Save Bio</button>
        </form>
        
        <div style="margin-top:30px;">
            <h3>NFT Badges</h3>
            <div class="badges">
                <span class="badge nft-badge">🔵 Sovereign Elite</span>
                <span class="badge nft-badge">⚡️ Quantum Verified</span>
                <span class="badge">💎 Diamond Member</span>
            </div>
        </div>
    </div>
</body>
</html>
