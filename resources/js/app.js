import './bootstrap';

// Haptic feedback for clicks on mobile
document.addEventListener('click', () => {
    if ('vibrate' in navigator) {
        // Vibrate for 50ms (a short, haptic feedback)
        navigator.vibrate(50);
    }
});
