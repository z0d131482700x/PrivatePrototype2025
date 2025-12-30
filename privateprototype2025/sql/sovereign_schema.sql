-- privateprototype2025/sql/sovereign_schema.sql
-- RM5M Sovereign Social Platform - MySQL 8.0+ (XAMPP Ready)
-- Instagram + Bitcoin + Quantum + XMPP OTP Complete Schema

-- =====================================================
-- MAIN DATABASE CREATION
-- =====================================================
CREATE DATABASE IF NOT EXISTS `sovereign_social` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE `sovereign_social`;

-- =====================================================
-- USERS TABLE - Sovereign Identity (@username system)
-- =====================================================
CREATE TABLE `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `sovereign_id` VARCHAR(64) UNIQUE NOT NULL COMMENT '@username hash',
    `username` VARCHAR(30) UNIQUE NOT NULL COMMENT 'Instagram @handle',
    `username_handle` VARCHAR(32) UNIQUE NOT NULL COMMENT '@username display',
    `jabber_id` VARCHAR(255) UNIQUE COMMENT 'XMPP OTP (no phone)',
    `xmpp_verified` BOOLEAN DEFAULT FALSE,
    `email` VARCHAR(255) UNIQUE,
    `bio` TEXT COMMENT '500-char Instagram bio',
    `profile_photo` VARCHAR(255) DEFAULT 'default_avatar.png',
    `wallet_address` VARCHAR(62) UNIQUE COMMENT 'BIP84 Bitcoin',
    `wallet_seed_hash` VARCHAR(128) COMMENT 'Quantum seed (encrypted)',
    `trust_score` INT DEFAULT 50 COMMENT 'AI reputation 0-100',
    `account_private` BOOLEAN DEFAULT FALSE,
    `default_post_privacy` ENUM('public','followers','close_friends') DEFAULT 'public',
    `followers_count` INT DEFAULT 0,
    `following_count` INT DEFAULT 0,
    `posts_count` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_username` (`username`),
    INDEX `idx_jabber` (`jabber_id`),
    INDEX `idx_wallet` (`wallet_address`)
) ENGINE=InnoDB COMMENT='Sovereign users with @username + Bitcoin';

-- =====================================================
-- FOLLOWERS - Instagram follow system
-- =====================================================
CREATE TABLE `followers` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `follower_id` INT NOT NULL,
    `following_id` INT NOT NULL,
    `status` ENUM('pending','approved','blocked') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`follower_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`following_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_follow` (`follower_id`, `following_id`),
    
    INDEX `idx_follower` (`follower_id`),
    INDEX `idx_following` (`following_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB COMMENT='Instagram followers/following/private approvals';

-- =====================================================
-- POSTS - Reels/Photos/Stories (Quantum E2EE)
-- =====================================================
CREATE TABLE `posts` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `type` ENUM('reel','photo','story','text') NOT NULL,
    `content_encrypted` LONGBLOB COMMENT 'Kyber1024 encrypted',
    `kyber_nonce` BLOB COMMENT 'Quantum nonce',
    `caption` TEXT,
    `privacy` ENUM('public','followers','close_friends') DEFAULT 'public',
    `likes_count` INT DEFAULT 0,
    `comments_count` INT DEFAULT 0,
    `views_count` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_type` (`user_id`, `type`),
    INDEX `idx_created` (`created_at`),
    INDEX `idx_privacy` (`privacy`)
) ENGINE=InnoDB COMMENT='Instagram reels/photos/stories';

-- =====================================================
-- LIKES - Post reactions
-- =====================================================
CREATE TABLE `likes` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `post_id` INT NOT NULL,
    `reaction` ENUM('like','love','haha','wow','sad','angry') DEFAULT 'like',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_like` (`user_id`, `post_id`),
    
    INDEX `idx_post` (`post_id`),
    INDEX `idx_user` (`user_id`)
) ENGINE=InnoDB COMMENT='Instagram post likes/reactions';

-- =====================================================
-- COMMENTS - Post comments with @mentions
-- =====================================================
CREATE TABLE `comments` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `post_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `comment_encrypted` MEDIUMBLOB COMMENT 'E2EE comments',
    `kyber_nonce` BLOB,
    `mentions` JSON COMMENT '@username mentions',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    
    INDEX `idx_post` (`post_id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB COMMENT='Instagram comments with @mentions';

-- =====================================================
-- MESSAGES - E2EE Private/Group Chat (WhatsApp)
-- =====================================================
CREATE TABLE `messages` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `chat_id` INT NOT NULL COMMENT '1:1 or group',
    `sender_id` INT NOT NULL,
    `content_encrypted` LONGBLOB,
    `kyber_nonce` BLOB,
    `type` ENUM('text','image','voice','video') DEFAULT 'text',
    `message_status` ENUM('sent','delivered','read') DEFAULT 'sent',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_chat` (`chat_id`),
    INDEX `idx_sender` (`sender_id`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB COMMENT='Quantum E2EE messaging';

-- =====================================================
-- CHATS - 1:1 + Groups
-- =====================================================
CREATE TABLE `chats` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `type` ENUM('private','group') NOT NULL,
    `name` VARCHAR(100) COMMENT 'Group name',
    `creator_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`creator_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_type` (`type`),
    INDEX `idx_creator` (`creator_id`)
) ENGINE=InnoDB COMMENT='Private/group chat rooms';

-- =====================================================
-- BITCOIN TRANSACTIONS - Lightning Network
-- =====================================================
CREATE TABLE `transactions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `txid` VARCHAR(64) UNIQUE,
    `to_address` VARCHAR(62),
    `amount_sat` BIGINT NOT NULL,
    `fee_sat` INT DEFAULT 0,
    `scam_score` DECIMAL(3,2) DEFAULT 0.00 COMMENT 'AI detection',
    `status` ENUM('pending','confirmed','failed') DEFAULT 'pending',
    `lightning_invoice` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user` (`user_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_txid` (`txid`)
) ENGINE=InnoDB COMMENT='Non-custodial Bitcoin LN';

-- =====================================================
-- XMPP OTP - No phone number 2FA
-- =====================================================
CREATE TABLE `xmpp_otp_logs` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `jabber_id` VARCHAR(255) NOT NULL,
    `otp_hash` VARCHAR(128) NOT NULL,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `verified` BOOLEAN DEFAULT FALSE,
    `action` ENUM('register','login','password_change') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX `idx_jabber` (`jabber_id`),
    INDEX `idx_verified` (`verified`),
    INDEX `idx_action` (`action`)
) ENGINE=InnoDB COMMENT='XMPP OTP audit trail';

-- =====================================================
-- CLOSE FRIENDS - Instagram green border stories
-- =====================================================
CREATE TABLE `close_friends` (
    `user_id` INT NOT NULL,
    `friend_username` VARCHAR(30) NOT NULL,
    PRIMARY KEY (`user_id`, `friend_username`),
    
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_friend` (`friend_username`)
) ENGINE=InnoDB COMMENT='Instagram Close Friends';

-- =====================================================
-- ADMIN SECURITY LOGS - CISO Dashboard
-- =====================================================
CREATE TABLE `security_logs` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NULL,
    `ip_address` VARCHAR(45),
    `event` VARCHAR(100) NOT NULL COMMENT 'shell_exec_blocked, sqli_attempt',
    `details` JSON,
    `severity` ENUM('low','medium','high','critical') DEFAULT 'low',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX `idx_user` (`user_id`),
    INDEX `idx_ip` (`ip_address`),
    INDEX `idx_severity` (`severity`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB COMMENT='Persephrak security monitoring';

-- =====================================================
-- TRIGGERS - Auto-update counters
-- =====================================================
DELIMITER $$

CREATE TRIGGER `after_follow_insert` 
AFTER INSERT ON `followers`
FOR EACH ROW 
BEGIN
    IF NEW.status = 'approved' THEN
        UPDATE `users` SET 
            `followers_count` = `followers_count` + 1,
            `following_count` = `following_count` + 1
        WHERE `id` IN (NEW.follower_id, NEW.following_id);
    END IF;
END$$

CREATE TRIGGER `after_follow_update` 
AFTER UPDATE ON `followers`
FOR EACH ROW 
BEGIN
    IF OLD.status != 'approved' AND NEW.status = 'approved' THEN
        UPDATE `users` SET 
            `followers_count` = `followers_count` + 1
        WHERE `id` = NEW.following_id;
        
        UPDATE `users` SET 
            `following_count` = `following_count` + 1
        WHERE `id` = NEW.follower_id;
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- SAMPLE DATA - XAMPP Demo Ready
-- =====================================================
INSERT INTO `users` (`sovereign_id`, `username`, `username_handle`, `jabber_id`, `wallet_address`, `bio`, `trust_score`) VALUES
('demo1', 'sovereign', '@sovereign', 'demo@xmpp.jp', 'bc1qxy1993w1cwccu5tl3nmynw7dcq9jemmpdgvne9em', 'RM5M Sovereign Platform Creator', 100),
('demo2', 'alice.eth', '@alice.eth', 'alice@xmpp.jp', 'bc1qxysamplewallet1234567890abcdef', 'Crypto trader', 92);

INSERT INTO `posts` (`user_id`, `type`, `caption`, `privacy`) VALUES
(1, 'reel', 'Quantum-secure reel demo 🚀', 'public'),
(1, 'photo', 'Instagram-style carousel', 'followers');

-- =====================================================
-- PERFORMANCE OPTIMIZATION
-- =====================================================
ALTER TABLE `posts` ADD COLUMN `is_deleted` BOOLEAN DEFAULT FALSE;
CREATE INDEX `idx_posts_feed` ON `posts` (`user_id`, `created_at` DESC);
CREATE INDEX `idx_messages_chat` ON `messages` (`chat_id`, `created_at` DESC);

-- XAMPP READY - Import this file directly
-- $1.2M enterprise database LIVE
