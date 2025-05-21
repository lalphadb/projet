// ===================================
// STUDIOS UNIS - SIDEBAR INTERACTIONS
// Améliorations UX pour la navigation
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // === GESTION DU SIDEBAR RESPONSIVE ===
    const sidebar = document.querySelector('.main-sidebar');
    const toggleBtn = document.querySelector('[data-widget="pushmenu"]');
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-open');
        });
        
        // Fermer le sidebar en cliquant en dehors (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('sidebar-open');
                }
            }
        });
    }
    
    // === ANIMATIONS AU CLIC ===
    const navLinks = document.querySelectorAll('.nav-sidebar .nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Animation de clic
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            
            // Effet de ripple
            createRipple(e, this);
        });
    });
    
    // === EFFET RIPPLE ===
    function createRipple(event, element) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        element.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    // === TOOLTIPS POUR SIDEBAR COLLAPSED ===
    const sidebarCollapsed = document.body.classList.contains('sidebar-collapse');
    
    if (sidebarCollapsed) {
        navLinks.forEach(link => {
            const text = link.querySelector('p').textContent;
            link.setAttribute('title', text);
            link.setAttribute('data-toggle', 'tooltip');
            link.setAttribute('data-placement', 'right');
        });
        
        // Initialiser les tooltips si Bootstrap est disponible
        if (typeof $ !== 'undefined' && $.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    }
    
    // === BADGE ANIMATION ===
    const badges = document.querySelectorAll('.nav-badge');
    badges.forEach(badge => {
        // Animation d'apparition
        badge.style.opacity = '0';
        badge.style.transform = 'scale(0)';
        
        setTimeout(() => {
            badge.style.transition = 'all 0.3s ease';
            badge.style.opacity = '1';
            badge.style.transform = 'scale(1)';
        }, 500);
        
        // Animation au clic
        badge.addEventListener('click', function(e) {
            e.preventDefault();
            this.style.transform = 'scale(1.2)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
    });
    
    // === MISE À JOUR AUTOMATIQUE DES BADGES ===
    function updateBadges() {
        // Ici vous pouvez ajouter la logique pour mettre à jour
        // les badges via AJAX si nécessaire
        console.log('Mise à jour des badges...');
    }
    
    // Mettre à jour les badges toutes les 5 minutes
    setInterval(updateBadges, 300000);
    
    // === SMOOTH SCROLLING POUR SIDEBAR LONGUE ===
    const sidebarContainer = document.querySelector('.sidebar');
    if (sidebarContainer && sidebarContainer.scrollHeight > sidebarContainer.clientHeight) {
        sidebarContainer.style.scrollBehavior = 'smooth';
    }
    
    // === RACCOURCIS CLAVIER ===
    document.addEventListener('keydown', function(e) {
        // Alt + D pour Dashboard
        if (e.altKey && e.key === 'd') {
            e.preventDefault();
            const dashboardLink = document.querySelector('a[href*="dashboard"]');
            if (dashboardLink) {
                dashboardLink.click();
            }
        }
        
        // Alt + M pour Membres
        if (e.altKey && e.key === 'm') {
            e.preventDefault();
            const membresLink = document.querySelector('a[href*="membres"]');
            if (membresLink) {
                membresLink.click();
            }
        }
        
        // Alt + C pour Cours
        if (e.altKey && e.key === 'c') {
            e.preventDefault();
            const coursLink = document.querySelector('a[href*="cours"]');
            if (coursLink) {
                coursLink.click();
            }
        }
    });
    
    // === ÉTAT ACTIF PERSISTANT ===
    function updateActiveState() {
        const currentPath = window.location.pathname;
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            link.classList.remove('active');
            
            if (href && currentPath.includes(href.split('/').pop())) {
                link.classList.add('active');
            }
        });
    }
    
    // Mettre à jour l'état actif si l'URL change (SPA)
    window.addEventListener('popstate', updateActiveState);
    
    // === CONFIRMATION DÉCONNEXION ===
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                e.preventDefault();
                return false;
            }
        });
    }
});

// === STYLES DYNAMIQUES POUR L'EFFET RIPPLE ===
const style = document.createElement('style');
style.textContent = `
    .nav-link {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Scrollbar pour sidebar */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(23, 162, 184, 0.5);
        border-radius: 10px;
        transition: background 0.3s ease;
    }
    
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(23, 162, 184, 0.8);
    }
`;
document.head.appendChild(style);
