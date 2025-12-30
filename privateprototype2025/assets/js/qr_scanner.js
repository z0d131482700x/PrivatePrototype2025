// privateprototype2025/assets/js/qr_scanner.js - PRODUCTION QR Scanner
class QRScanner {
    constructor() {
        this.video = document.createElement('video');
        this.canvas = document.createElement('canvas');
        this.ctx = this.canvas.getContext('2d');
        this.stream = null;
        this.scanning = false;
    }
    
    async start(containerId) {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            this.video.srcObject = this.stream;
            this.video.play();
            
            const container = document.getElementById(containerId);
            container.appendChild(this.video);
            this.video.style.width = '100%';
            this.video.style.height = '100%';
            
            this.scanLoop();
        } catch (err) {
            console.error('QR Scanner Error:', err);
        }
    }
    
    scanLoop() {
        if (this.video.readyState === this.video.HAVE_ENOUGH_DATA) {
            this.canvas.width = this.video.videoWidth;
            this.canvas.height = this.video.videoHeight;
            this.ctx.drawImage(this.video, 0, 0);
            
            try {
                const result = jsQR(this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height));
                if (result) {
                    this.onScan(result.data);
                    return;
                }
            } catch (e) {}
        }
        if (this.scanning) requestAnimationFrame(() => this.scanLoop());
    }
    
    onScan(address) {
        // PRODUCTION: Send Bitcoin payment
        window.location.href = `/wallet/send.php?address=${encodeURIComponent(address)}`;
    }
}
