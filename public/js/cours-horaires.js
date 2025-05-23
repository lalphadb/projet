// COURS HORAIRES MULTIPLES - JS
let horaireIndex = 0;

document.addEventListener('DOMContentLoaded', function() {
    horaireIndex = document.querySelectorAll('.horaire-item').length;
    setupEventListeners();
});

function setupEventListeners() {
    document.addEventListener('change', function(e) {
        if (e.target.matches('select[name*="[jour]"], input[name*="[heure_debut]"], input[name*="[heure_fin]"]')) {
            updatePreview(e.target.closest('.horaire-item'));
        }
    });
}

function ajouterHoraire() {
    const container = document.getElementById('horaires-container');
    const nouvelHoraire = document.createElement('div');
    nouvelHoraire.className = 'horaire-item horaire-new';
    nouvelHoraire.setAttribute('data-index', horaireIndex);
    
    nouvelHoraire.innerHTML = `
        <div class="horaire-header">
            <span class="horaire-title">Plage horaire #${horaireIndex + 1}</span>
            <button type="button" class="btn-remove-horaire" onclick="supprimerHoraire(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <select name="horaires[${horaireIndex}][jour]" class="form-select" required>
                    <option value="">Choisir</option>
                    <option value="lundi">Lundi</option>
                    <option value="mardi">Mardi</option>
                    <option value="mercredi">Mercredi</option>
                    <option value="jeudi">Jeudi</option>
                    <option value="vendredi">Vendredi</option>
                    <option value="samedi">Samedi</option>
                    <option value="dimanche">Dimanche</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="time" name="horaires[${horaireIndex}][heure_debut]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="time" name="horaires[${horaireIndex}][heure_fin]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="horaires[${horaireIndex}][salle]" class="form-control" placeholder="Salle">
            </div>
        </div>
        <div class="horaire-preview" style="display: none;"></div>
    `;
    
    container.appendChild(nouvelHoraire);
    horaireIndex++;
}

function supprimerHoraire(button) {
    const horaireItem = button.closest('.horaire-item');
    horaireItem.remove();
}

function updatePreview(horaireItem) {
    const jour = horaireItem.querySelector('select[name*="[jour]"]')?.value;
    const debut = horaireItem.querySelector('input[name*="[heure_debut]"]')?.value;
    const fin = horaireItem.querySelector('input[name*="[heure_fin]"]')?.value;
    const preview = horaireItem.querySelector('.horaire-preview');
    
    if (jour && debut && fin) {
        const jours = {lundi: 'Lundi', mardi: 'Mardi', mercredi: 'Mercredi', jeudi: 'Jeudi', vendredi: 'Vendredi', samedi: 'Samedi', dimanche: 'Dimanche'};
        preview.textContent = `${jours[jour]} de ${debut} à ${fin}`;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}
