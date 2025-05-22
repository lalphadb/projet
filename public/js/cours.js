/**
 * ===== JAVASCRIPT MODULE COURS - STUDIOS UNISDB =====
 * Interactions et fonctionnalités pour le module cours
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialisation des fonctionnalités
    initializeCoursModule();
    
    function initializeCoursModule() {
        // Gestion des filtres
        initializeFilters();
        
        // Gestion des confirmations de suppression
        initializeDeleteConfirmations();
        
        // Gestion des tooltips
        initializeTooltips();
        
        // Gestion de la mise à jour des places
        initializePlacesUpdater();
        
        // Gestion des sessions automatiques
        initializeSessionManager();
        
        // Animation des états vides
        initializeEmptyStateAnimations();
        
        // Gestion du tri des colonnes
        initializeTableSorting();
        
        // Gestion de la recherche en temps réel
        initializeSearchFilter();
    }
    
    /**
     * Gestion des filtres
     */
    function initializeFilters() {
        const filterForm = document.querySelector('.filter-form');
        const filterSelects = document.querySelectorAll('.filter-select');
        
        if (filterForm) {
            // Auto-submit des filtres (optionnel)
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    // Optionnel : soumettre automatiquement le formulaire
                    // filterForm.submit();
                });
            });
            
            // Réinitialisation des filtres
            const resetBtn = document.createElement('button');
            resetBtn.type = 'button';
            resetBtn.className = 'btn-filter btn-reset';
            resetBtn.innerHTML = '<i class="fas fa-times me-1"></i> Réinitialiser';
            resetBtn.addEventListener('click', function() {
                filterSelects.forEach(select => {
                    if (select.name === 'ecole_id') {
                        select.value = 'all';
                    } else {
                        select.selectedIndex = 0;
                    }
                });
                filterForm.submit();
            });
            
            // Ajouter le bouton de réinitialisation si des filtres sont actifs
            const hasActiveFilters = Array.from(filterSelects).some(select => 
                select.value && select.value !== 'all' && select.selectedIndex !== 0
            );
            
            if (hasActiveFilters) {
                const filterActions = filterForm.querySelector('div:last-child');
                if (filterActions) {
                    filterActions.appendChild(resetBtn);
                }
            }
        }
    }
    
    /**
     * Gestion des confirmations de suppression
     */
    function initializeDeleteConfirmations() {
        const deleteButtons = document.querySelectorAll('.btn-action-delete');
        
        deleteButtons.forEach(button => {
            button.closest('form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const coursName = this.closest('tr').querySelector('.cours-name').textContent;
                
                // Créer une modale de confirmation personnalisée
                showDeleteModal(coursName, () => {
                    this.submit();
                });
            });
        });
    }
    
    /**
     * Afficher une modale de confirmation de suppression
     */
    function showDeleteModal(coursName, onConfirm) {
        const modal = document.createElement('div');
        modal.className = 'delete-modal-overlay';
        modal.innerHTML = `
            <div class="delete-modal">
                <div class="delete-modal-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>Confirmer la suppression</h4>
                </div>
                <div class="delete-modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le cours <strong>"${coursName}"</strong> ?</p>
                    <p class="warning-text">Cette action est irréversible et supprimera également toutes les inscriptions associées.</p>
                </div>
                <div class="delete-modal-actions">
                    <button class="btn-cancel">Annuler</button>
                    <button class="btn-confirm">Supprimer</button>
                </div>
            </div>
        `;
        
        // Styles pour la modale
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        `;
        
        const modalContent = modal.querySelector('.delete-modal');
        modalContent.style.cssText = `
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border-radius: 15px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease;
        `;
        
        // Événements
        modal.querySelector('.btn-cancel').addEventListener('click', () => {
            document.body.removeChild(modal);
        });
        
        modal.querySelector('.btn-confirm').addEventListener('click', () => {
            document.body.removeChild(modal);
            onConfirm();
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });
        
        document.body.appendChild(modal);
    }
    
    /**
     * Gestion des tooltips améliorés
     */
    function initializeTooltips() {
        const tooltipElements = document.querySelectorAll('.action-tooltip');
        
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                // Animation d'entrée du tooltip
                const tooltip = this.querySelector('::after');
                if (tooltip) {
                    tooltip.style.animation = 'tooltipFadeIn 0.3s ease';
                }
            });
        });
    }
    
    /**
     * Mise à jour en temps réel des places disponibles
     */
    function initializePlacesUpdater() {
        // Fonction pour mettre à jour les places lors d'une inscription/désinscription
        window.updatePlacesDisponibles = function(coursId, newCount, maxPlaces) {
            const placesCell = document.querySelector(`[data-cours-id="${coursId}"] .places-disponibles`);
            if (placesCell) {
                placesCell.textContent = newCount;
                
                // Changer la couleur selon le nombre de places
                placesCell.classList.remove('places-warning', 'places-danger');
                const percentage = (newCount / maxPlaces) * 100;
                
                if (percentage <= 10) {
                    placesCell.classList.add('places-danger');
                } else if (percentage <= 25) {
                    placesCell.classList.add('places-warning');
                }
                
                // Animation de mise à jour
                placesCell.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    placesCell.style.transform = 'scale(1)';
                }, 300);
            }
        };
    }
    
    /**
     * Gestionnaire des sessions automatiques
     */
    function initializeSessionManager() {
        // Fonction pour générer automatiquement les sessions
        window.generateSessions = function(year) {
            const sessions = [
                { nom: `Hiver ${year}`, mois: 'Jan-Mar', debut: `${year}-01-01`, fin: `${year}-03-31` },
                { nom: `Printemps ${year}`, mois: 'Avr-Juin', debut: `${year}-04-01`, fin: `${year}-06-30` },
                { nom: `Été ${year}`, mois: 'Juil-Sep', debut: `${year}-07-01`, fin: `${year}-09-30` },
                { nom: `Automne ${year}`, mois: 'Oct-Déc', debut: `${year}-10-01`, fin: `${year}-12-31` }
            ];
            
            return sessions;
        };
        
        // Fonction pour déterminer la session suivante
        window.getNextSession = function() {
            const now = new Date();
            const year = now.getFullYear();
            const month = now.getMonth() + 1;
            
            if (month <= 3) return { nom: `Printemps ${year}`, mois: 'Avr-Juin' };
            if (month <= 6) return { nom: `Été ${year}`, mois: 'Juil-Sep' };
            if (month <= 9) return { nom: `Automne ${year}`, mois: 'Oct-Déc' };
            return { nom: `Hiver ${year + 1}`, mois: 'Jan-Mar' };
        };
    }
    
    /**
     * Animations pour les états vides
     */
    function initializeEmptyStateAnimations() {
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            // Animation de l'icône
            const icon = emptyState.querySelector('i');
            if (icon) {
                setInterval(() => {
                    icon.style.transform = 'rotate(360deg)';
                    setTimeout(() => {
                        icon.style.transform = 'rotate(0deg)';
                    }, 1000);
                }, 5000);
            }
        }
    }
    
    /**
     * Gestion du tri des colonnes
     */
    function initializeTableSorting() {
        const sortableHeaders = document.querySelectorAll('.cours-table thead th a');
        
        sortableHeaders.forEach(header => {
            header.addEventListener('click', function(e) {
                // Animation de chargement
                const icon = this.querySelector('i[class*="fa-sort"]');
                if (icon) {
                    icon.style.animation = 'spin 0.5s linear';
                }
            });
        });
    }
    
    /**
     * Gestion de la recherche en temps réel
     */
    function initializeSearchFilter() {
        // Si un champ de recherche est ajouté plus tard
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.toLowerCase();
                
                searchTimeout = setTimeout(() => {
                    filterTableRows(query);
                }, 300);
            });
        }
    }
    
    /**
     * Filtrer les rangées du tableau
     */
    function filterTableRows(query) {
        const rows = document.querySelectorAll('.cours-table tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = text.includes(query);
            
            row.style.display = matches ? '' : 'none';
            
            if (matches) {
                row.style.animation = 'fadeInUp 0.3s ease';
            }
        });
    }
    
    /**
     * Gestion des jours multiples dans les formulaires
     */
    function initializeJoursSelector() {
        const joursCheckboxes = document.querySelectorAll('input[name="jours[]"]');
        const joursPreview = document.querySelector('.jours-preview');
        
        if (joursCheckboxes.length > 0) {
            joursCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateJoursPreview();
                });
            });
            
            // Mise à jour initiale
            updateJoursPreview();
        }
        
        function updateJoursPreview() {
            const selectedJours = Array.from(joursCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
                
            if (joursPreview) {
                if (selectedJours.length > 0) {
                    joursPreview.innerHTML = `
                        <span class="jours-selected">
                            <i class="fas fa-calendar-check me-2"></i>
                            ${selectedJours.map(jour => ucfirst(jour)).join(', ')}
                        </span>
                    `;
                } else {
                    joursPreview.innerHTML = `
                        <span class="jours-empty text-muted">
                            <i class="fas fa-calendar-times me-2"></i>
                            Aucun jour sélectionné
                        </span>
                    `;
                }
            }
        }
    }
    
    /**
     * Validation des formulaires
     */
    function initializeFormValidation() {
        const coursForm = document.querySelector('#cours-form');
        
        if (coursForm) {
            coursForm.addEventListener('submit', function(e) {
                let isValid = true;
                const errors = [];
                
                // Validation du nom
                const nom = document.querySelector('input[name="nom"]');
                if (nom && nom.value.trim().length < 3) {
                    errors.push('Le nom du cours doit contenir au moins 3 caractères.');
                    isValid = false;
                }
                
                // Validation des jours
                const joursSelected = document.querySelectorAll('input[name="jours[]"]:checked');
                if (joursSelected.length === 0) {
                    errors.push('Vous devez sélectionner au moins un jour.');
                    isValid = false;
                }
                
                // Validation des heures
                const heureDebut = document.querySelector('input[name="heure_debut"]');
                const heureFin = document.querySelector('input[name="heure_fin"]');
                if (heureDebut && heureFin) {
                    if (heureDebut.value >= heureFin.value) {
                        errors.push('L\'heure de fin doit être supérieure à l\'heure de début.');
                        isValid = false;
                    }
                }
                
                // Validation du nombre de places
                const placesMax = document.querySelector('input[name="places_max"]');
                if (placesMax && (placesMax.value < 1 || placesMax.value > 100)) {
                    errors.push('Le nombre de places doit être entre 1 et 100.');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    showValidationErrors(errors);
                }
            });
        }
    }
    
    /**
     * Afficher les erreurs de validation
     */
    function showValidationErrors(errors) {
        const errorContainer = document.querySelector('.validation-errors') || createErrorContainer();
        
        errorContainer.innerHTML = `
            <div class="alert alert-danger">
                <h6><i class="fas fa-exclamation-circle me-2"></i>Erreurs de validation</h6>
                <ul class="mb-0">
                    ${errors.map(error => `<li>${error}</li>`).join('')}
                </ul>
            </div>
        `;
        
        errorContainer.scrollIntoView({ behavior: 'smooth' });
    }
    
    /**
     * Créer un conteneur pour les erreurs
     */
    function createErrorContainer() {
        const container = document.createElement('div');
        container.className = 'validation-errors';
        
        const form = document.querySelector('#cours-form');
        if (form) {
            form.insertBefore(container, form.firstChild);
        }
        
        return container;
    }
    
    /**
     * Fonction utilitaire pour mettre en majuscule la première lettre
     */
    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    /**
     * Fonction utilitaire pour afficher des notifications
     */
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
            <span>${message}</span>
            <button class="btn-close"><i class="fas fa-times"></i></button>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
            max-width: 400px;
        `;
        
        const closeBtn = notification.querySelector('.btn-close');
        closeBtn.addEventListener('click', () => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        });
        
        document.body.appendChild(notification);
        
        // Auto-fermeture après 5 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                closeBtn.click();
            }
        }, 5000);
    };
    
    // Initialiser les fonctionnalités supplémentaires
    initializeJoursSelector();
    initializeFormValidation();
});

// Styles CSS additionnels ajoutés dynamiquement
const additionalStyles = `
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes slideInRight {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}

@keyframes slideOutRight {
    from { transform: translateX(0); }
    to { transform: translateX(100%); }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.delete-modal-header {
    text-align: center;
    color: #dc3545;
    margin-bottom: 1.5rem;
}

.delete-modal-header i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.delete-modal-header h4 {
    color: #fff;
    margin: 0;
}

.delete-modal-body p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 1rem;
    line-height: 1.6;
}

.warning-text {
    color: #ffc107 !important;
    font-size: 0.9rem;
    font-style: italic;
}

.delete-modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

.btn-cancel, .btn-confirm {
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel {
    background: #6c757d;
    color: #fff;
}

.btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.btn-confirm {
    background: #dc3545;
    color: #fff;
}

.btn-confirm:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.btn-reset {
    background: linear-gradient(45deg, #6c757d, #495057);
    margin-left: 1rem;
}

.btn-reset:hover {
    background: linear-gradient(45deg, #495057, #343a40);
}

.jours-preview {
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.jours-selected {
    color: #28a745;
    font-weight: 600;
}

.jours-empty {
    color: rgba(255, 255, 255, 0.5);
    font-style: italic;
}

.validation-errors {
    margin-bottom: 2rem;
}

.validation-errors .alert {
    background: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.3);
    color: #fff;
    border-radius: 12px;
}

.validation-errors .alert h6 {
    color: #dc3545;
    margin-bottom: 1rem;
}

.validation-errors .alert ul li {
    margin-bottom: 0.5rem;
}
`;

// Injecter les styles
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);
