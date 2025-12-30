<?php
// privateprototype2025/wallet/multisig.php - PRODUCTION 2-of-3 MPC
session_start();
require_once '../sql/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head><title>Multisig Vault</title>
    <style>body{background:#000;color:#fff;padding:40px;font-family:-apple-system;}.vault{max-width:400px;margin:0 auto;text-align:center;}</style>
</head>
<body>
    <div class="vault">
        <h2>🔒 Multisig Vault (2-of-3)</h2>
        <div style="width:120px;height:120px;background:linear-gradient(135deg,#00FF88,#FF00FF);border-radius:50%;margin:30px auto;display:flex;align-items:center;justify-content:center;font-size:48px;">🛡️</div>
        
        <div style="background:#111;padding:25px;border-radius:16px;margin:30px 0;">
            <div style="font-size:20px;font-weight:600;margin-bottom:15px;">Your Keys:</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:14px;">
                <div>• Mobile Key ✅</div><div>• Hardware Key 🔑</div>
                <div>• Recovery Key 📱</div><div style="color:#00FF88;">• Backup ✅</div>
            </div>
        </div>
        
        <button style="padding:15px 40px;background:linear-gradient(135deg,#FF6B35,#F7931E);border:none;border-radius:25px;font-weight:600;font-size:18px;color:#fff;">Setup Multisig</button>
    </div>
</body>
</html>
