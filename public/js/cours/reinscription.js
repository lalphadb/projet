// Réinscription Membre - JavaScript Interactif
document.addEventListener('DOMContentLoaded', function() {
    
    const coursOptions = document.querySelectorAll('.cours-option');
    const btnConfirmer = document.getElementById('btnConfirmer');
    const totalMontant = document.getElementById('totalMontant');
    
    let coursSelectionnes = [];
    let montantTotal = 0;
    
    // Gestion sélection cours
    coursOptions.forEach(option => {
        const checkbox = option.querySelector('input[type="checkbox"]');
        const tarif = parseFloat(option.dataset.tarif || 0);
        
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                option.classList.add('selected');
                coursSelectionnes.push({
                    id: option.dataset.coursId,
                    nom: option.dataset.coursNom,
                    tarif: tarif
                });
            } else {
                option.classList.remove('selected');
                coursSelectionnes = coursSelectionnes.filter(c => c.id !== option.dataset.coursId);
            }
            mettreAJourTotal();
        });
        
        option.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });
    
    function mettreAJourTotal() {
        montantTotal = coursSelectionnes.reduce((total, cours) => total + cours.tarif, 0);
        totalMontant.textContent = montantTotal.toFixed(2) + ' $';
        
        btnConfirmer.disabled = coursSelectionnes.length === 0;
        btnConfirmer.textContent = coursSelectionnes.length === 0 
            ? 'Sélectionnez des cours' 
            : `Confirmer (${coursSelectionnes.length} cours)`;
    }
    
    // Confirmation de réinscription
    btnConfirmer.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (coursSelectionnes.length === 0) return;
        
        this.classList.add('loading');
        this.disabled = true;
        
        const formData = new FormData();
        formData.append('membre_id', document.getElementById('membreId').value);
        formData.append('session_id', document.getElementById('sessionId').value);
        coursSelectionnes.forEach(cours => {
            formData.append('cours_choisis[]', cours.id);
        });
        
        fetch('/reinscription/confirmer', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Erreur lors de la réinscription');
                this.classList.remove('loading');
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            this.classList.remove('loading');
            this.disabled = false;
        });
    });
    
    mettreAJourTotal();
});
