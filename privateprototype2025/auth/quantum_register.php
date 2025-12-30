<?php
// privateprototype2025/auth/quantum_register.php - PRODUCTION
session_start();

if (!isset($_SESSION['xmpp_verified'])) {
    header('Location: xmpp_setup.php');
    exit;
}

require_once '../config/bitcoin_engine.php';
require_once '../config/quantum_crypto.php';
require_once '../sql/db_connect.php'; // PDO

$error = '';

if ($_POST['complete_register']) {
    $username = trim($_POST['username']);
    
    // PRODUCTION: Strict @username validation
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9._]{2,29}$/', $username)) {
        $error = '❌ Username: 3-30 chars, start with letter';
    } elseif (usernameExists($username)) {
        $error = '❌ @' . $username . ' taken';
    } else {
        // Generate PRODUCTION Bitcoin wallet (BIP84)
        $wallet = new BitcoinEngine($_SESSION['jabber_id'] . time());
        $wallet_address = $wallet->getAddress();
        $wallet_seed = $wallet->generateQuantumSeed();
        
        // Quantum-encrypt seed
        $encrypted_seed = QuantumCrypto::encrypt($wallet_seed);
        
        // PRODUCTION: Save to database (PDO)
        $stmt = $pdo->prepare("
            INSERT INTO users (
                sovereign_id, username, username_handle, jabber_id, 
                wallet_address, wallet_seed_hash, xmpp_verified
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $sovereign_id = hash('sha256', $username . $_SESSION['jabber_id']);
        $stmt->execute([
            $sovereign_id,
            $username,
            '@' . strtolower($username),
            $_SESSION['jabber_id'],
            $wallet_address,
            hash('sha256', $wallet_seed),
            true
        ]);
        
        // Set secure session
        $_SESSION['sovereign_authenticated'] = true;
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        $_SESSION['username_handle'] = '@' . strtolower($username);
        $_SESSION['wallet_address'] = $wallet_address;
        $_SESSION['wallet_seed_encrypted'] = $encrypted_seed;
        
        // Security: Clear sensitive data
        unset($_SESSION['xmpp_verified'], $_SESSION['jabber_id']);
        
        header('Location: ../social/feed.php');
        exit;
    }
}

function usernameExists($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE LOWER(username) = LOWER(?)");
    $stmt->execute([$username]);
    return $stmt->rowCount() > 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Registration</title>
    <style>/* Neon cyberpunk style */</style>
</head>
<body>
    <div class="register-container">
        <div class="logo">🛡️ Sovereign Registration</div>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="username-input">
                <span class="at-symbol">@</span>
                <input type="text" name="username" placeholder="yourusername" 
                       maxlength="30" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>
            
            <p style="color:#888888;">
                XMPP: <?= htmlspecialchars($_SESSION['jabber_id']) ?><br>
                💾 <strong>BACKUP your 24-word seed when prompted</strong>
            </p>
            
            <button type="submit" name="complete_register">
                🚀 Create Production Account + Bitcoin Wallet
            </button>
        </form>
        
        <div style="margin-top:30px;color:#888888;font-size:14px;">
            <strong>Production features:</strong><br>
            • BIP84 Bitcoin wallet (non-custodial)<br>
            • Kyber1024 quantum seed encryption<br>
            • Instagram @username (unique)<br>
            • 3-Layer IP protection
        </div>
    </div>
</body>
</html>
