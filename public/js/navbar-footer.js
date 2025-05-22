// ===================================
// STUDIOS UNIS - NAVBAR & FOOTER JS
// Interactions pour navigation
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // === GESTION DU DROPDOWN ===
    const dropdownToggle = document.querySelector('.navbar-nav .dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (dropdownToggle && dropdownMenu) {
        // Fermer le dropdown en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Animation d'ouverture/fermeture
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const isOpen = dropdownMenu.classList.contains('show');
            
            if (isOpen) {
                dropdownMenu.style.animation = 'dropdownFadeOut 0.3s ease-out';
                setTimeout(() => {
                    dropdownMenu.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                }, 250);
            } else {
                dropdownMenu.classList.add('show');
                dropdownMenu.style.animation = 'dropdownFadeIn 0.3s ease-out';
                this.setAttribute('aria-expanded', 'true');
            }
        });
    }
    
    // === ANIMATION DES LIENS DE NAVIGATION ===
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // === GESTION DES NOTIFICATIONS ===
    const notificationBell = document.querySelector('.navbar-notification .nav-link');
    const notificationBadge = document.querySelector('.notification-badge');
    
    if (notificationBell && notificationBadge) {
        notificationBell.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Animation de clic
            notificationBadge.style.transform = 'scale(1.3)';
            setTimeout(() => {
                notificationBadge.style.transform = 'scale(1)';
            }, 200);
            
            // Ici vous pouvez ajouter la logique pour afficher les notifications
            console.log('Affichage des notifications');
        });
    }
    
    // === CONFIRMATION DE DÉCONNEXION ===
    const logoutForm = document.querySelector('form[action*="logout"]');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
            const confirmed = confirm('Êtes-vous sûr de vouloir vous déconnecter ?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    }
    
    // === MISE À JOUR DE L'HEURE (Footer) ===
    function updateTime() {
        const timeElements = document.querySelectorAll('.current-time');
        const now = new Date();
        const timeString = now.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        timeElements.forEach(element => {
            element.textContent = timeString;
        });
    }
    
    // Mettre à jour l'heure toutes les minutes
    setInterval(updateTime, 60000);
    updateTime(); // Mise à jour immédiate
    
    // === EFFET PARALLAX LÉGER POUR LE FOOTER ===
    const footer = document.querySelector('footer');
    if (footer) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3;
            footer.style.transform = `translateY(${rate}px)`;
        });
    }
    
    // === RACCOURCIS CLAVIER POUR NAVIGATION ===
    document.addEventListener('keydown', function(e) {
        // Ctrl + Shift + P pour profil
        if (e.ctrlKey && e.shiftKey && e.key === 'P') {
            e.preventDefault();
            const profileLink = document.querySelector('a[href*="profile"]');
            if (profileLink) {
                profileLink.click();
            }
        }
        
        // Ctrl + Shift + L pour déconnexion
        if (e.ctrlKey && e.shiftKey && e.key === 'L') {
            e.preventDefault();
            const logoutBtn = document.querySelector('button[type="submit"][class*="dropdown-item"]');
            if (logoutBtn && confirm('Déconnexion rapide - Êtes-vous sûr ?')) {
                logoutBtn.closest('form').submit();
            }
        }
    });
    
    // === INDICATEUR DE CONNEXION ===
    function showConnectionStatus() {
        const avatar = document.querySelector('.user-avatar');
        if (avatar) {
            // Ajouter un indicateur de statut
            const indicator = document.createElement('span');
            indicator.className = 'status-indicator';
            indicator.style.cssText = `
                position: absolute;
                bottom: 2px;
                right: 2px;
                width: 8px;
                height: 8px;
                background: #28a745;
                border-radius: 50%;
                border: 1px solid white;
                animation: statusPulse 2s infinite;
            `;
            
            avatar.style.position = 'relative';
            avatar.appendChild(indicator);
        }
    }
    
    showConnectionStatus();
    
    // === ANIMATION SMOOTH POUR LES DROPDOWNS ===
    const style = document.createElement('style');
    style.textContent = `
        @keyframes dropdownFadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
        
        @keyframes statusPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
            margin: 0.5rem 0 !important;
        }
    `;
    document.head.appendChild(style);
});

// === FONCTIONS UTILITAIRES ===
function showNotification(message, type = 'info') {
    // Ici vous pouvez implémenter un système de notification
    console.log(`${type.toUpperCase()}: ${message}`);
}

// === GESTION DU THÈME (si implémenté) ===
function toggleTheme() {
    document.body.classList.toggle('light-mode');
    localStorage.setItem('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark');
}
