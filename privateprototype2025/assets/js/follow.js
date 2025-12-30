// privateprototype2025/assets/js/follow.js - PRODUCTION Follow System
class FollowSystem {
    constructor() {
        this.initButtons();
        this.checkFollowStatus();
    }
    
    initButtons() {
        document.querySelectorAll('.follow-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.toggleFollow(e.target.dataset.userId));
        });
    }
    
    async toggleFollow(userId) {
        const btn = document.querySelector(`[data-user-id="${userId}"]`);
        try {
            const response = await fetch('/social/follow.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            });
            const data = await response.json();
            
            if (data.success) {
                if (data.action === 'follow') {
                    btn.textContent = 'Following';
                    btn.classList.add('following');
                    this.showNotification('Followed @' + data.username);
                } else {
                    btn.textContent = 'Follow';
                    btn.classList.remove('following');
                    this.showNotification('Unfollowed');
                }
            }
        } catch (error) {
            console.error('Follow error:', error);
        }
    }
    
    showNotification(message) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed; top: 20px; right: 20px; background: var(--neon-green);
            color: #000; padding: 16px 24px; border-radius: 25px; font-weight: 600;
            box-shadow: 0 8px 32px rgba(0,255,136,0.4); z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    checkFollowStatus() {
        // Check initial follow status from data attributes
        document.querySelectorAll('.follow-btn').forEach(btn => {
            if (btn.dataset.status === 'following') {
                btn.textContent = 'Following';
                btn.classList.add('following');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => new FollowSystem());
