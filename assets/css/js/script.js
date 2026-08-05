// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.success-msg, .error-msg');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// Confirm delete actions
function confirmAction(message) {
    return confirm(message || 'Are you sure?');
}

// Set min date for date inputs to today
document.querySelectorAll('input[type="date"]').forEach(input => {
    if (!input.min) {
        input.min = new Date().toISOString().split('T')[0];
    }
});

// Form validation feedback
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = '⏳ Processing...';
        }
    });
});
