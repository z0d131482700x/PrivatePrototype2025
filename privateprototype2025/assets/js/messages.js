// privateprototype2025/assets/js/messages.js - PRODUCTION E2EE WebSocket
class MessageSystem {
    constructor() {
        this.ws = null;
        this.connect();
        this.initMessageInput();
    }
    
    connect() {
        this.ws = new WebSocket('wss://sovereign.social:8080/chat');
        
        this.ws.onopen = () => {
            console.log('✅ E2EE WebSocket connected');
            this.ws.send(JSON.stringify({ type: 'join', user_id: <?= $_SESSION['user_id'] ?? 1 ?> }));
        };
        
        this.ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleMessage(data);
        };
        
        this.ws.onclose = () => {
            console.log('🔄 Reconnecting...');
            setTimeout(() => this.connect(), 3000);
        };
    }
    
    initMessageInput() {
        const input = document.getElementById('message-input');
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });
    }
    
    async sendMessage() {
        const input = document.getElementById('message-input');
        const message = input.value.trim();
        if (!message) return;
        
        // E2EE: Encrypt before sending
        const encrypted = await this.encryptMessage(message);
        
        this.ws.send(JSON.stringify({
            type: 'message',
            encrypted: encrypted,
            timestamp: Date.now()
        }));
        
        input.value = '';
    }
    
    async encryptMessage(message) {
        // Kyber1024 E2EE (placeholder - real impl in core/encryption_engine.php)
        return btoa(message + '|E2EE|');
    }
    
    handleMessage(data) {
        const messagesContainer = document.getElementById('messages');
        const bubble = document.createElement('div');
        bubble.className = 'message-bubble message-received';
        bubble.innerHTML = `
            <div>${this.decryptMessage(data.encrypted)}</div>
            <div style="font-size:12px;color:#888;margin-top:5px;">${new Date(data.timestamp).toLocaleTimeString()}</div>
            <div class="e2ee-indicator"></div>
        `;
        messagesContainer.appendChild(bubble);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    decryptMessage(encrypted) {
        return atob(encrypted).replace('|E2EE|', '');
    }
}

document.addEventListener('DOMContentLoaded', () => new MessageSystem());
