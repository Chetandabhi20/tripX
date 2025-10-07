// Theme switcher
function toggleTheme() {
    document.body.classList.toggle('dark-theme');
    localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
}

// Password strength meter
function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    return strength;
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value) {
            showError(input, 'This field is required');
            isValid = false;
        } else {
            clearError(input);
        }
    });

    // Password validation
    const password = form.querySelector('input[type="password"]');
    if (password && password.value) {
        const strength = checkPasswordStrength(password.value);
        if (strength < 3) {
            showError(password, 'Password is too weak');
            isValid = false;
        }
    }

    return isValid;
}

// Show/hide mobile menu
function toggleMobileMenu() {
    const nav = document.querySelector('.nav-links');
    nav.classList.toggle('show');
}

// Error handling
function showError(input, message) {
    const errorDiv = input.nextElementSibling || document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    if (!input.nextElementSibling) {
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
}

function clearError(input) {
    const errorDiv = input.nextElementSibling;
    if (errorDiv && errorDiv.className === 'error-message') {
        errorDiv.remove();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Apply saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }

    // Mobile menu toggle
    const menuButton = document.querySelector('.mobile-menu-btn');
    if (menuButton) {
        menuButton.addEventListener('click', toggleMobileMenu);
    }

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!validateForm(form.id)) {
                e.preventDefault();
            }
        });
    });
});