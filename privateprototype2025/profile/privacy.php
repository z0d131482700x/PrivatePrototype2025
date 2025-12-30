<?php
// privateprototype2025/profile/privacy.php - PRODUCTION Post Privacy
session_start();
?>
<!DOCTYPE html>
<html>
<head><title>Post Privacy</title></head>
<body style="background:#000;color:#fff;padding:40px;font-family:-apple-system;">
    <div style="max-width:400px;margin:0 auto;">
        <h2>🔒 Post Visibility</h2>
        <div style="background:#111;padding:25px;border-radius:16px;margin:20px 0;">
            <label style="display:block;margin-bottom:20px;font-size:18px;cursor:pointer;">
                <input type="radio" name="post_privacy" checked> 🌐 Public
            </label>
            <label style="display:block;margin-bottom:20px;font-size:18px;cursor:pointer;">
                <input type="radio" name="post_privacy"> 👥 Followers Only
            </label>
            <label style="display:block;font-size:18px;cursor:pointer;">
                <input type="radio" name="post_privacy"> 🔒 Close Friends
            </label>
        </div>
        <button style="width:100%;padding:15px;background:#00FF88;color:#000;border:none;border-radius:12px;font-weight:600;">Apply to All Posts</button>
    </div>
</body>
</html>
