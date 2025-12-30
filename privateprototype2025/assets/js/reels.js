// privateprototype2025/assets/js/reels.js - PRODUCTION TikTok Swipe
class ReelsPlayer {
    constructor() {
        this.currentIndex = 0;
        this.reels = document.querySelectorAll('.reel-container');
        this.hammer = new Hammer(document.body);
        this.initGestures();
    }
    
    initGestures() {
        this.hammer.on('swipeup', () => this.nextReel());
        this.hammer.on('swipedown', () => this.prevReel());
    }
    
    nextReel() {
        this.reels[this.currentIndex].style.display = 'none';
        this.currentIndex = (this.currentIndex + 1) % this.reels.length;
        this.reels[this.currentIndex].style.display = 'block';
        this.reels[this.currentIndex].querySelector('video').play();
    }
}
