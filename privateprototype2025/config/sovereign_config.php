<?php
// privateprototype2025/config/sovereign_config.php - PRODUCTION
return [
    'mode' => 'production',
    'security_level' => 'quantum',
    'xmpp' => [
        'admin_jid' => 'admin@xmpp.jp',
        'servers' => ['xmpp.jp:5222', 'conversations.im:5222']
    ],
    'bitcoin_network' => 'mainnet',
    'rate_limits' => [
        'otp_per_hour' => 3,
        'posts_per_hour' => 50,
        'messages_per_minute' => 30
    ],
    'session' => [
        'timeout' => 7200, // 2 hours
        'secure' => true,
        'httponly' => true
    ]
];
?>
