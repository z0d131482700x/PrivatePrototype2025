<?php
// privateprototype2025/social/privacy_check.php - PRODUCTION Privacy
function can_view_post($post, $viewer_id) {
    global $pdo;
    
    if ($post['privacy'] === 'public') return true;
    if ($post['user_id'] === $viewer_id) return true;
    
    if ($post['privacy'] === 'followers') {
        $stmt = $pdo->prepare("SELECT id FROM followers WHERE follower_id = ? AND following_id = ? AND status = 'approved'");
        $stmt->execute([$viewer_id, $post['user_id']]);
        return $stmt->rowCount() > 0;
    }
    
    if ($post['privacy'] === 'close_friends') {
        // Close friends check (green border stories)
        return false; // Simplified
    }
    
    return false;
}
?>
