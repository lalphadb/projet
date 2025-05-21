// ===================================
// STUDIOS UNIS - FORMULAIRES EDIT MEMBRES
// Scripts pour amélioration UX
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // === GESTION DU FORMULAIRE ===
    const memberForm = document.getElementById('memberForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (memberForm && submitBtn) {
        // Animation du bouton au submit
        memberForm.addEventListener('submit', function(e) {
            if (memberForm.checkValidity()) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            }
        });
    }
    
    // === AMÉLIORATION DES CHAMPS ===
    const formControls = document.querySelectorAll('.form-control-enhanced, .form-select-enhanced');
    
    formControls.forEach(control => {
        // Effet focus
        control.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateX(3px)';
        });
        
        control.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateX(0)';
        });
        
        // Validation en temps réel
        control.addEventListener('input', function() {
            validateField(this);
        });
    });
    
    // === VALIDATION DES CHAMPS ===
    function validateField(field) {
        const isValid = field.checkValidity();
        const value = field.value.trim();
        
        // Supprime les classes de validation existantes
        field.classList.remove('is-invalid', 'is-valid');
        
        if (value === '') {
            // Champ vide - pas de validation visuelle
            return;
        }
        
        if (isValid) {
            field.classList.add('is-valid');
            field.style.borderColor = '#28a745';
        } else {
            field.classList.add('is-invalid');
            field.style.borderColor = '#dc3545';
        }
        
        // Validation spéciale pour l'email
        if (field.type === 'email' && value !== '') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(value)) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                field.style.borderColor = '#28a745';
            } else {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                field.style.borderColor = '#dc3545';
            }
        }
        
        // Validation spéciale pour le téléphone
        if (field.name === 'telephone' && value !== '') {
            const phoneRegex = /^[\d\s\-\(\)\+\.]{10,}$/;
            if (phoneRegex.test(value)) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                field.style.borderColor = '#28a745';
            } else {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                field.style.borderColor = '#dc3545';
            }
        }
    }
    
    // === AUTO-COMPLÉTION INTELLIGENTE ===
    const prenomField = document.querySelector('input[name="prenom"]');
    const nomField = document.querySelector('input[name="nom"]');
    
    if (prenomField && nomField) {
        // Capitalisation automatique
        [prenomField, nomField].forEach(field => {
            field.addEventListener('blur', function() {
                this.value = capitalizeWords(this.value);
            });
        });
    }
    
    // === GESTION CODE POSTAL ===
    const codePostalField = document.querySelector('input[name="code_postal"]');
    if (codePostalField) {
        codePostalField.addEventListener('input', function() {
            // Format canadien : A1A 1A1
            let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (value.length > 3) {
                value = value.substring(0, 3) + ' ' + value.substring(3, 6);
            }
            this.value = value;
        });
    }
    
    // === GESTION TÉLÉPHONE ===
    const telephoneField = document.querySelector('input[name="telephone"]');
    if (telephoneField) {
        telephoneField.addEventListener('input', function() {
            // Format: 123-456-7890
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 6) {
                value = value.substring(0, 3) + '-' + value.substring(3, 6) + '-' + value.substring(6, 10);
            } else if (value.length >= 3) {
                value = value.substring(0, 3) + '-' + value.substring(3);
            }
            this.value = value;
        });
    }
    
    // === CONFIRMATION DE NAVIGATION ===
    let formModified = false;
    
    formControls.forEach(control => {
        control.addEventListener('input', function() {
            formModified = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formModified && !submitBtn?.classList.contains('loading')) {
            e.preventDefault();
            e.returnValue = 'Vous avez des modifications non sauvegardées. Voulez-vous vraiment quitter cette page ?';
            return e.returnValue;
        }
    });
    
    // === SAUVEGARDE LOCALE (DRAFT) ===
    function saveDraft() {
        if (!formModified) return;
        
        const formData = {};
        formControls.forEach(control => {
            if (control.name && control.value) {
                formData[control.name] = control.value;
            }
        });
        
        localStorage.setItem('member_form_draft', JSON.stringify(formData));
    }
    
    function loadDraft() {
        const draft = localStorage.getItem('member_form_draft');
        if (draft && confirm('Voulez-vous restaurer vos modifications précédentes ?')) {
            const formData = JSON.parse(draft);
            Object.keys(formData).forEach(name => {
                const field = document.querySelector(`[name="${name}"]`);
                if (field && !field.value) {
                    field.value = formData[name];
                    formModified = true;
                }
            });
        }
    }
    
    // Sauvegarde automatique toutes les 30 secondes
    setInterval(saveDraft, 30000);
    
    // Supprime le draft au submit réussi
    if (memberForm) {
        memberForm.addEventListener('submit', function() {
            localStorage.removeItem('member_form_draft');
        });
    }
    
    // === ANIMATIONS DE CHARGEMENT ===
    setTimeout(() => {
        document.querySelectorAll('.form-group-enhanced').forEach((group, index) => {
            group.style.animationDelay = `${index * 0.1}s`;
        });
    }, 100);
});

// === FONCTIONS UTILITAIRES ===
function capitalizeWords(str) {
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
}

function showFormNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        border-radius: 10px;
        animation: slideInRight 0.5s ease-out;
    `;
    
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    notification.innerHTML = `
        <i class="fas ${icon} mr-2"></i>
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.5s ease-in';
        setTimeout(() => notification.remove(), 500);
    }, 4000);
}

// === VALIDATION AVANCÉE ===
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            validateField(field);
        }
    });
    
    if (!isValid) {
        showFormNotification('Veuillez remplir tous les champs obligatoires.', 'danger');
        // Scroll vers le premier champ invalide
        const firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.focus();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    return isValid;
}
