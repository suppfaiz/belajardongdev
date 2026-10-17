import './bootstrap';
import Alpine from 'alpinejs';
import confetti from 'canvas-confetti';

window.Alpine = Alpine;
window.confetti = confetti;

// Custom Alpine helpers
Alpine.data('bookingModal', () => ({
    isOpen: false,
    open() { this.isOpen = true; },
    close() { this.isOpen = false; }
}));

Alpine.start();
