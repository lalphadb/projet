// === FICHIER: public/js/auth.js ===

document.addEventListener('DOMContentLoaded', function() {
    // === GESTION DU FORMULAIRE DE CONNEXION ===
    const form = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    if (form && loginBtn) {
        // Animation du bouton au clic
        form.addEventListener('submit', function(e) {
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            
            // Simulation de chargement (pour démo)
            setTimeout(() => {
                if (!form.checkValidity()) {
                    loginBtn.classList.remove('loading');
                    loginBtn.disabled = false;
                }
            }, 2000);
        });
    }
    
    // === EFFETS VISUELS POUR LES CHAMPS ===
    const inputs = document.querySelectorAll('.form-control-modern');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
    
    // === ANIMATION DES PARTICULES AU SURVOL ===
    const particles = document.querySelectorAll('.particle');
    if (particles.length > 0) {
        document.addEventListener('mousemove', function(e) {
            particles.forEach((particle, index) => {
                const rect = particle.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                if (x >= 0 && x <= rect.width && y >= 0 && y <= rect.height) {
                    particle.style.transform = `scale(1.5)`;
                    particle.style.opacity = '0.8';
                } else {
                    particle.style.transform = `scale(1)`;
                    particle.style.opacity = '0.1';
                }
            });
        });
    }
    
    // === VALIDATION EN TEMPS RÉEL ===
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const isValid = this.checkValidity();
            this.style.borderColor = isValid ? 'rgba(255, 255, 255, 0.2)' : '#ff6b6b';
        });
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const isValid = this.value.length >= 6;
            this.style.borderColor = isValid ? 'rgba(255, 255, 255, 0.2)' : '#ff6b6b';
        });
    }
});

// === FONCTION DE NOTIFICATION PERSONNALISÉE ===
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert-custom position-fixed`;
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

// === ANIMATION CSS POUR LES NOTIFICATIONS ===
if (!document.getElementById('notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-modern.loading .btn-text {
            opacity: 0;
        }

        .btn-modern.loading .loading-spinner {
            display: block;
        }
    `;
    document.head.appendChild(style);
}
