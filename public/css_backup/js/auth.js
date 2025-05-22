// ===================================
// STUDIOS UNIS - PAGE AUTHENTIFICATION
// auth.js pour la page de connexion
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // === GESTION DU FORMULAIRE DE CONNEXION ===
    const form = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    if (form && loginBtn) {
        // Animation du bouton au clic
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                return;
            }
            
            loginBtn.classList.add('btn-loading');
            loginBtn.disabled = true;
            
            // Simulation de chargement
            setTimeout(() => {
                if (!form.checkValidity()) {
                    loginBtn.classList.remove('btn-loading');
                    loginBtn.disabled = false;
                }
            }, 2000);
        });
    }
    
    // === AMÉLIORATION UX DES CHAMPS ===
    const inputs = document.querySelectorAll('.form-control-stack');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateX(2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateX(0)';
        });
        
        // Validation en temps réel
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.style.borderColor = '#28a745';
            } else if (this.value.length > 0) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#6c757d';
            }
        });
    });
    
    // === ANIMATION DES BOUTONS ===
    const buttons = document.querySelectorAll('.btn-secondary-stack, .btn-primary-stack');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // === GESTION DES CHECKBOXES ===
    const checkboxes = document.querySelectorAll('.checkbox-custom');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            }
        });
    });
    
    // === AMÉLIORATION DES LIENS ===
    const links = document.querySelectorAll('.forgot-link, .policy-link, .footer-link');
    links.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(2px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});

// === FONCTION DE NOTIFICATION PERSONNALISÉE ===
function showAuthNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert-modern position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.5s ease-out;
    `;
    
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    notification.innerHTML = `
        <i class="fas ${icon} mr-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.5s ease-in';
        setTimeout(() => notification.remove(), 500);
    }, 4000);
}

// === VALIDATION AVANCÉE DES FORMULAIRES ===
function validateAuthForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            field.style.borderColor = '#28a745';
        }
    });
    
    // Validation email
    const emailField = form.querySelector('input[type="email"]');
    if (emailField && emailField.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value)) {
            emailField.style.borderColor = '#dc3545';
            isValid = false;
        }
    }
    
    // Validation mot de passe
    const passwordField = form.querySelector('input[type="password"]');
    if (passwordField && passwordField.value && passwordField.value.length < 6) {
        passwordField.style.borderColor = '#dc3545';
        isValid = false;
    }
    
    return isValid;
}

// === GESTION DES ERREURS ===
function handleAuthError(error) {
    console.error('Erreur d\'authentification:', error);
    showAuthNotification('Une erreur est survenue. Veuillez réessayer.', 'error');
}

// === PRÉCHARGEMENT DES RESSOURCES ===
function preloadAuthResources() {
    // Précharger les icônes FontAwesome utilisées
    const iconClasses = [
        'fas fa-sign-in-alt',
        'fas fa-user-plus',
        'fas fa-user-check',
        'fas fa-key',
        'fas fa-info-circle',
        'fas fa-exclamation-circle'
    ];
    
    iconClasses.forEach(iconClass => {
        const icon = document.createElement('i');
        icon.className = iconClass;
        icon.style.display = 'none';
        document.body.appendChild(icon);
    });
}

// === INITIALISATION ===
// Précharger les ressources au chargement de la page
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', preloadAuthResources);
} else {
    preloadAuthResources();
}
