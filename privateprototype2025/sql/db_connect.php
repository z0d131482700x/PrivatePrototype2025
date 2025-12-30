<?php
// privateprototype2025/sql/db_connect.php - RM5M PRODUCTION DATABASE
// Sovereign Social Platform - Enterprise MySQL Connection

// =====================================================
// PRODUCTION CONFIGURATION
// =====================================================
$DB_CONFIG = [
    'host' => 'localhost',                    // XAMPP: localhost
    'port' => 3306,                           // Standard MySQL port
    'dbname' => 'sovereign_social',           // Production database
    'username' => 'sovereign_user',           // Production user (NOT root)
    'password' => 'RM5M_Sovereign2025!',      // Strong production password
    'charset' => 'utf8mb4',                   // Instagram emoji support
    'timeout' => 10,                          // Connection timeout
    'max_attempts' => 3                       // Retry logic
];

// =====================================================
// PRODUCTION SECURITY HEADERS (Database Layer)
header('X-Database-Security: Sovereign-Production');
header('X-SQLi-Protection: PDO-Prepared-Statements');

// =====================================================
// PRODUCTION PDO CONNECTION WITH FAILOVER
function get_production_db() {
    global $DB_CONFIG;
    
    $attempts = 0;
    while ($attempts < $DB_CONFIG['max_attempts']) {
        try {
            $dsn = "mysql:host={$DB_CONFIG['host']};port={$DB_CONFIG['port']};dbname={$DB_CONFIG['dbname']};charset={$DB_CONFIG['charset']}";
            
            $pdo = new PDO($dsn, $DB_CONFIG['username'], $DB_CONFIG['password'], [
                // PRODUCTION SECURITY
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,              // Strict errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Secure fetch
                PDO::ATTR_EMULATE_PREPARES => false,                      // Real prepared statements
                PDO::ATTR_STRINGIFY_FETCHES => false,                     // No string conversion
                PDO::ATTR_STATEMENT_CLASS => ['PDOStatementSecure'],       // Custom secure wrapper
                
                // PERFORMANCE
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$DB_CONFIG['charset']} COLLATE {$DB_CONFIG['charset']}_unicode_ci, sql_mode='STRICT_TRANS_TABLES,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER'",
                PDO::ATTR_PERSISTENT => false,                            // No persistent (security)
                
                // TIMEOUTS
                PDO::ATTR_TIMEOUT => $DB_CONFIG['timeout'],
                PDO::MYSQL_ATTR_READ_TIMEOUT => 10,
                PDO::MYSQL_ATTR_WRITE_TIMEOUT => 30
            ]);
            
            // PRODUCTION: Test connection
            $pdo->query('SELECT 1');
            
            // Log successful connection
            error_log("✅ PRODUCTION DB: Connected as {$DB_CONFIG['username']} to {$DB_CONFIG['dbname']}");
            
            return $pdo;
            
        } catch (PDOException $e) {
            $attempts++;
            error_log("DB Connection attempt $attempts failed: " . $e->getMessage());
            
            if ($attempts >= $DB_CONFIG['max_attempts']) {
                // PRODUCTION: Graceful failure
                http_response_code(503);
                die('Database service temporarily unavailable. Sovereign platform secure.');
            }
            
            // Exponential backoff
            usleep(pow(100000, $attempts));
        }
    }
}

// =====================================================
// PRODUCTION SECURE STATEMENT WRAPPER
class PDOStatementSecure extends PDOStatement {
    public function execute($params = null) {
        // PRODUCTION: Log suspicious queries
        $query = $this->queryString;
        if (stripos($query, 'union') !== false || stripos($query, 'select * from') !== false) {
            error_log("SECURITY: Suspicious query detected: " . substr($query, 0, 100));
        }
        return parent::execute($params);
    }
}

// =====================================================
// GLOBAL PRODUCTION PDO INSTANCE
$pdo = get_production_db();

// =====================================================
// PRODUCTION FUNCTIONS
function sovereign_query($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function username_exists($username) {
    return sovereign_query("SELECT id FROM users WHERE LOWER(username) = LOWER(?)", [$username])->rowCount() > 0;
}

function get_user_by_username($username_handle) {
    return sovereign_query("SELECT * FROM users WHERE username_handle = ?", [$username_handle])->fetch();
}

// =====================================================
// XAMPP PRODUCTION SETUP
/*
XAMPP USERS: Update these lines for your setup:
$DB_CONFIG = [
    'host' => 'localhost',
    'dbname' => 'sovereign_social',
    'username' => 'root',           // XAMPP default
    'password' => '',               // XAMPP default (empty)
];
*/
?>
