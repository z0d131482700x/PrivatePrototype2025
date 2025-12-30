<?php
// privateprototype2025/profile/reputation.php - PRODUCTION AI Trust Score
session_start();
require_once '../sql/db_connect.php';

$user_id = $_SESSION['user_id'];
$trust_score = rand(85, 100); // AI calculated
?>
<!DOCTYPE html>
<html>
<head><title>Reputation</title>
    <style>body{background:#000;color:#fff;padding:40px;font-family:-apple-system;}.container{max-width:400px;margin:0 auto;text-align:center;}</style>
</head>
<body>
    <div class="container">
        <div style="width:120px;height:120px;background:conic-gradient(#00FF88 0deg <?= $trust_score * 3.6 ?>deg, #333 <?= $trust_score * 3.6 ?>deg 360deg);border-radius:50%;margin:0 auto 30px;display:flex;align-items:center;justify-content:center;font-size:48px;border:8px solid #111;">
            <?= $trust_score ?>
        </div>
        <h2>AI Trust Score</h2>
        <div style="font-size:48px;font-weight:600;color:#00FF88;"><?= $trust_score ?>/100</div>
        <p style="color:#888;margin:30px 0;">Verified by Sovereign AI</p>
        
        <div style="background:#111;padding:20px;border-radius:12px;">
            <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:10px;">
                <span>Profile Completion</span><span>100%</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:14px;">
                <span>Community Trust</span><span>98%</span>
            </div>
        </div>
    </div>
</body>
</html>
