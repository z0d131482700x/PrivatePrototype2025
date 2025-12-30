<?php
// privateprototype2025/profile/settings.php - PRODUCTION Privacy Controls
session_start();
require_once '../sql/db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT privacy_setting FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$privacy = $user['privacy_setting'] ?? 'public';

if ($_POST) {
    $new_privacy = $_POST['privacy'];
    $stmt = $pdo->prepare("UPDATE users SET privacy_setting = ? WHERE id = ?");
    $stmt->execute([$new_privacy, $user_id]);
    header('Location: settings.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Settings</title>
    <style>body{background:#000;color:#fff;padding:40px;font-family:-apple-system;}.container{max-width:400px;margin:0 auto;}</style>
</head>
<body>
    <div class="container">
        <h2>⚙️ Privacy Settings</h2>
        
        <form method="POST">
            <div style="background:#111;padding:20px;border-radius:12px;margin:20px 0;">
                <label style="display:flex;align-items:center;gap:15px;font-size:18px;margin-bottom:20px;cursor:pointer;">
                    <input type="radio" name="privacy" value="public" <?= $privacy === 'public' ? 'checked' : '' ?>>
                    <span>🌐 Public Account</span>
                </label>
                <label style="display:flex;align-items:center;gap:15px;font-size:18px;cursor:pointer;">
                    <input type="radio" name="privacy" value="private" <?= $privacy === 'private' ? 'checked' : '' ?>>
                    <span>🔒 Private Account</span>
                </label>
            </div>
            <button type="submit" style="width:100%;padding:15px;background:#00FF88;color:#000;border:none;border-radius:12px;font-weight:600;">Save Settings</button>
        </form>
    </div>
</body>
</html>
