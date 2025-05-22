/**
 * Studios Unis - Gestion des écoles
 * JavaScript pour le module d'administration des écoles
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Confirmation pour les suppressions et actions dangereuses
    $('form[data-confirm]').on('submit', function(e) {
        const message = $(this).data('confirm') || 'Êtes-vous sûr de vouloir effectuer cette action ?';
        if (!confirm(message)) {
            e.preventDefault();
            return false;
        }
    });

    // Animation pour les statistiques
    const statElements = document.querySelectorAll('.ecole-stat .stat-value');
    
    if (statElements.length > 0) {
        statElements.forEach(function(statElement) {
            const finalValue = parseInt(statElement.textContent, 10);
            
            if (!isNaN(finalValue)) {
                let currentValue = 0;
                const increment = Math.max(1, Math.floor(finalValue / 20));
                const duration = 1000; // ms
                const interval = duration / (finalValue / increment);
                
                const counter = setInterval(function() {
                    currentValue += increment;
                    
                    if (currentValue >= finalValue) {
                        clearInterval(counter);
                        statElement.textContent = finalValue;
                    } else {
                        statElement.textContent = currentValue;
                    }
                }, interval);
            }
        });
    }
    
    // Filtres dynamiques pour la liste des écoles
    const filterInputs = document.querySelectorAll('[data-filter]');
    
    if (filterInputs.length > 0) {
        filterInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                applyFilters();
            });
        });
    }
    
    function applyFilters() {
        // Récupérer tous les filtres actifs
        const activeFilters = {};
        
        filterInputs.forEach(function(input) {
            const filterName = input.getAttribute('data-filter');
            const filterValue = input.value;
            
            if (filterValue) {
                activeFilters[filterName] = filterValue;
            }
        });
        
        // Appliquer les filtres sur les cartes d'écoles
        const ecoleCards = document.querySelectorAll('.ecole-card');
        
        if (ecoleCards.length > 0) {
            ecoleCards.forEach(function(card) {
                let shouldShow = true;
                
                // Vérifier chaque filtre actif
                for (const [filterName, filterValue] of Object.entries(activeFilters)) {
                    const cardValue = card.getAttribute('data-' + filterName);
                    
                    // Si la carte n'a pas la valeur du filtre, ne pas l'afficher
                    if (cardValue !== filterValue) {
                        if (filterName === 'status' && filterValue === 'all') {
                            // Exception pour le filtre "tous" du statut
                            continue;
                        }
                        
                        shouldShow = false;
                        break;
                    }
                }
                
                // Afficher ou masquer la carte
                card.closest('.col-md-6').style.display = shouldShow ? 'block' : 'none';
            });
        }
    }
});
