<?php
// privateprototype2025/social/follow.php - PRODUCTION API
session_start();
require_once '../sql/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['sovereign_authenticated'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$target_user = $_POST['user_id'] ?? $_GET['user_id'] ?? 0;
$user_id = $_SESSION['user_id'];

if (!$target_user || $user_id == $target_user) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid target']);
    exit;
}

if ($action === 'follow') {
    $stmt = $pdo->prepare("
        INSERT INTO followers (follower_id, following_id, status) 
        VALUES (?, ?, 'pending') 
        ON DUPLICATE KEY UPDATE status = 'pending'
    ");
    $stmt->execute([$user_id, $target_user]);
    
    echo json_encode(['status' => 'success', 'action' => 'following']);
} elseif ($action === 'unfollow') {
    $stmt = $pdo->prepare("DELETE FROM followers WHERE follower_id = ? AND following_id = ?");
    $stmt->execute([$user_id, $target_user]);
    
    echo json_encode(['status' => 'success', 'action' => 'follow']);
}
?>
