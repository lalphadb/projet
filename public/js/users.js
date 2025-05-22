/**
 * Scripts pour la gestion des utilisateurs
 */

// Fonction pour basculer la visibilité du mot de passe
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Fonction pour sélectionner un rôle
function selectRole(role) {
    // Mettre à jour la valeur cachée
    document.getElementById('role_input').value = role;
    
    // Mettre à jour les classes visuelles
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    document.querySelector(`.role-card[onclick="selectRole('${role}')"]`).classList.add('selected');
}

// Initialisation des recherches de membres (pour la page de promotion)
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si les éléments existent sur la page avant d'ajouter les événements
    const searchInput = document.getElementById('member_search');
    if (!searchInput) return;
    
    const searchResults = document.getElementById('searchResults');
    const membreIdInput = document.getElementById('membre_id');
    const selectedMemberInfo = document.getElementById('selectedMemberInfo');
    const selectedMemberName = document.getElementById('selectedMemberName');
    const selectedMemberEmail = document.getElementById('selectedMemberEmail');
    const selectedMemberSchool = document.getElementById('selectedMemberSchool');
    
    let typingTimer;
    const doneTypingInterval = 500; // 500ms
    
    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        
        if (searchInput.value.length < 3) {
            searchResults.innerHTML = '';
            searchResults.classList.remove('active');
            return;
        }
        
        typingTimer = setTimeout(() => {
            // Recherche avec AJAX
            fetch(`/membres/search?q=${searchInput.value}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                
                if (data.length === 0) {
                    searchResults.innerHTML = '<div class="p-3 text-center text-muted">Aucun résultat trouvé</div>';
                } else {
                    data.forEach(membre => {
                        const item = document.createElement('div');
                        item.className = 'member-item';
                        item.innerHTML = `
                            <div class="member-info">
                                <div class="member-name">${membre.prenom} ${membre.nom}</div>
                                <div class="member-school">${membre.ecole ? membre.ecole.nom : 'Aucune école'}</div>
                            </div>
                            <div class="member-meta">${membre.email || 'Aucun email'}</div>
                        `;
                        
                        item.addEventListener('click', () => {
                            // Mettre à jour la valeur cachée
                            membreIdInput.value = membre.id;
                            
                            // Mettre à jour l'affichage
                            searchInput.value = `${membre.prenom} ${membre.nom}`;
                            selectedMemberName.textContent = `${membre.prenom} ${membre.nom}`;
                            selectedMemberEmail.textContent = membre.email || 'Aucun email';
                            selectedMemberSchool.textContent = membre.ecole ? `École: ${membre.ecole.nom}` : 'Aucune école assignée';
                            selectedMemberInfo.style.display = 'block';
                            
                            // Fermer les résultats
                            searchResults.classList.remove('active');
                        });
                        
                        searchResults.appendChild(item);
                    });
                }
                
                searchResults.classList.add('active');
            })
            .catch(error => {
                console.error('Erreur:', error);
                searchResults.innerHTML = '<div class="p-3 text-center text-danger">Erreur lors de la recherche</div>';
                searchResults.classList.add('active');
            });
        }, doneTypingInterval);
    });
    
    // Cacher les résultats lorsqu'on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('active');
        }
    });
});
