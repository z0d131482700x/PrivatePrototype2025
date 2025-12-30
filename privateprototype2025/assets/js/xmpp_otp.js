// privateprototype2025/assets/js/xmpp_otp.js - PRODUCTION XMPP
class XMPPOtp {
    constructor(jid, token) {
        this.jid = jid;
        this.token = token;
        this.poll();
    }
    
    async poll() {
        try {
            const response = await fetch('/auth/xmpp_check.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ jid: this.jid, token: this.token })
            });
            const data = await response.json();
            if (data.otp) {
                document.getElementById('otp-input').value = data.otp;
                document.getElementById('otp-form').submit();
            }
        } catch (e) {}
        setTimeout(() => this.poll(), 3000);
    }
}
