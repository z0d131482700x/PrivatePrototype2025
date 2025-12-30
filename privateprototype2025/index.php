<?php
// privateprototype2025/index.php - RM5M PRODUCTION GATEWAY
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'cookie_samesite' => 'Strict'
]);

// =====================================================
// PRODUCTION CORE INCLUDES (All Production config/)
require_once 'config/sovereign_config.php';
require_once 'config/xmpp_otp.php';
require_once 'config/quantum_crypto.php';
require_once 'config/bitcoin_engine.php';
require_once 'config/ai_security.php';
require_once 'sql/db_connect.php'; // PDO Production

// =====================================================
// PERSEPHRAK SECURITY (Production)
require_once 'core/shell_killer.php';
ShellKiller::enforce_national_security();

// =====================================================
// PRODUCTION SECURITY HEADERS
header('Content-Security-Policy: default-src \'self\'; style-src \'self\' \'unsafe-inline\'; script-src \'self\';');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-National-Sovereignty: RM5000000-Production');
header('Referrer-Policy: strict-origin-when-cross-origin');

// =====================================================
// PRODUCTION AUTH FLOW
if (isset($_SESSION['sovereign_authenticated'])) {
    header('Location: social/feed.php');
    exit;
}

// AI Security Scan
if (AISecurity::scan($_SERVER['QUERY_STRING'], 'gateway')) {
    http_response_code(403);
    die('Access Denied');
}

if (isset($_GET['xmpp_setup'])) {
    include 'auth/xmpp_setup.php';
} elseif (isset($_GET['otp_verify'])) {
    include 'auth/otp_verify.php';
} elseif (isset($_GET['quantum_register'])) {
    include 'auth/quantum_register.php';
} else {
    include 'auth/xmpp_setup.php'; // Production starts with XMPP
}
?>
