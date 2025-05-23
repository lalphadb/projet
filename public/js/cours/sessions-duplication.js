// Sessions Duplication - JavaScript Interactif
document.addEventListener('DOMContentLoaded', function() {
    
    // Modal de duplication
    const modalDuplication = document.getElementById('modalDuplication');
    const formDuplication = document.getElementById('formDuplication');
    
    // Boutons d'action
    document.querySelectorAll('.btn-dupliquer').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const sessionId = this.dataset.sessionId;
            ouvrirModalDuplication(sessionId);
        });
    });
    
    // Gestion des réinscriptions
    document.querySelectorAll('.btn-reinscriptions').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const sessionId = this.dataset.sessionId;
            const action = this.dataset.action;
            toggleReinscriptions(sessionId, action);
        });
    });
    
    function ouvrirModalDuplication(sessionId) {
        // Pré-remplir les dates
        const aujourd = new Date();
        const moisProchain = new Date(aujourd.getFullYear(), aujourd.getMonth() + 1, 1);
        const finMois = new Date(moisProchain.getFullYear(), moisProchain.getMonth() + 3, 0);
        
        document.getElementById('nouvelle_date_debut').value = 
            moisProchain.toISOString().split('T')[0];
        document.getElementById('nouvelle_date_fin').value = 
            finMois.toISOString().split('T')[0];
    }
    
    function toggleReinscriptions(sessionId, action) {
        const btn = event.target.closest('.btn-reinscriptions');
        btn.classList.add('loading');
        
        const url = action === 'activer' 
            ? `/cours/sessions/${sessionId}/activer-reinscriptions`
            : `/cours/sessions/${sessionId}/fermer-reinscriptions`;
            
        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.classList.remove('loading');
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            btn.classList.remove('loading');
            console.error('Erreur:', error);
        });
    }
});
