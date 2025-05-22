@extends('layouts.admin')

@section('title', 'Créer une session')

@push('styles')
<link href="{{ asset('css/cours.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/cours.js') }}"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="cours-container">
        <!-- En-tête -->
        <div class="cours-header">
            <div class="cours-title">
                <div class="title-content">
                    <i class="fas fa-calendar-plus"></i>
                    Créer une nouvelle session
                </div>
                <a href="{{ route('cours.sessions.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour aux sessions
                </a>
            </div>
        </div>

        <!-- Formulaire de création -->
        <div class="form-container">
            <form action="{{ route('cours.sessions.store') }}" method="POST" id="session-form">
                @csrf
                
                <div class="row g-4">
                    <!-- Nom de la session -->
                    <div class="col-md-8">
                        <label for="nom" class="form-label">
                            <i class="fas fa-tag me-2"></i>
                            Nom de la session *
                        </label>
                        <input type="text" 
                               class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom') }}" 
                               placeholder="Ex: Hiver 2025, Printemps 2025..."
                               required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Le nom apparaîtra dans les listes et les cours.</small>
                    </div>

                    <!-- Sélecteur rapide de saison -->
                    <div class="col-md-4">
                        <label for="saison_rapide" class="form-label">
                            <i class="fas fa-magic me-2"></i>
                            Sélection rapide
                        </label>
                        <select class="form-select" id="saison_rapide" onchange="remplirSaison()">
                            <option value="">Sélection manuelle</option>
                            <option value="hiver">Hiver {{ date('Y') }}</option>
                            <option value="printemps">Printemps {{ date('Y') }}</option>
                            <option value="ete">Été {{ date('Y') }}</option>
                            <option value="automne">Automne {{ date('Y') }}</option>
                            <option value="hiver_next">Hiver {{ date('Y') + 1 }}</option>
                        </select>
                        <small class="text-muted">Remplit automatiquement les champs.</small>
                    </div>

                    <!-- Description courte (mois) -->
                    <div class="col-12">
                        <label for="mois" class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Description courte
                        </label>
                        <input type="text" 
                               class="form-control @error('mois') is-invalid @enderror" 
                               id="mois" 
                               name="mois" 
                               value="{{ old('mois') }}" 
                               placeholder="Ex: Jan-Mar, Avr-Juin, Juil-Sep, Oct-Déc">
                        @error('mois')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Description courte qui apparaîtra à côté du nom.</small>
                    </div>

                    <!-- Dates de la session -->
                    <div class="col-md-6">
                        <label for="date_debut" class="form-label">
                            <i class="fas fa-calendar-day me-2"></i>
                            Date de début *
                        </label>
                        <input type="date" 
                               class="form-control @error('date_debut') is-invalid @enderror" 
                               id="date_debut" 
                               name="date_debut" 
                               value="{{ old('date_debut') }}" 
                               required>
                        @error('date_debut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="date_fin" class="form-label">
                            <i class="fas fa-calendar-day me-2"></i>
                            Date de fin *
                        </label>
                        <input type="date" 
                               class="form-control @error('date_fin') is-invalid @enderror" 
                               id="date_fin" 
                               name="date_fin" 
                               value="{{ old('date_fin') }}" 
                               required>
                        @error('date_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Informations calculées -->
                    <div class="col-12">
                        <div class="session-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Informations calculées</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Durée :</span>
                                    <span class="info-value" id="duree-session">-</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Nombre de semaines :</span>
                                    <span class="info-value" id="semaines-session">-</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Saison :</span>
                                    <span class="info-value" id="saison-detectee">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes et description -->
                    <div class="col-12">
                        <label for="description" class="form-label">
                            <i class="fas fa-sticky-note me-2"></i>
                            Notes et description
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Notes internes, objectifs de la session, événements spéciaux...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('cours.sessions.index') }}" class="btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Créer la session
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
<style>
/* Styles spécifiques pour le formulaire de session */
.session-info {
    background: rgba(23, 162, 184, 0.1);
    border: 1px solid rgba(23, 162, 184, 0.3);
    border-radius: 10px;
    padding: 1.5rem;
}

.session-info h6 {
    color: #17a2b8;
    margin-bottom: 1rem;
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.info-label {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
}

.info-value {
    color: #fff;
    font-weight: 600;
}

/* Responsive pour le formulaire */
@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Synchronisation des dates pour les calculs
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    
    // Calcul automatique de la durée
    function calculerDuree() {
        if (dateDebut.value && dateFin.value) {
            const debut = new Date(dateDebut.value);
            const fin = new Date(dateFin.value);
            
            if (fin > debut) {
                const diffTime = Math.abs(fin - debut);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const diffWeeks = Math.ceil(diffDays / 7);
                
                document.getElementById('duree-session').textContent = diffDays + ' jours';
                document.getElementById('semaines-session').textContent = diffWeeks + ' semaines';
                
                // Détecter la saison
                const moisDebut = debut.getMonth();
                let saison = '';
                if (moisDebut >= 11 || moisDebut <= 1) saison = 'Hiver';
                else if (moisDebut >= 2 && moisDebut <= 4) saison = 'Printemps';
                else if (moisDebut >= 5 && moisDebut <= 7) saison = 'Été';
                else if (moisDebut >= 8 && moisDebut <= 10) saison = 'Automne';
                
                document.getElementById('saison-detectee').textContent = saison;
            } else {
                document.getElementById('duree-session').textContent = 'Dates invalides';
                document.getElementById('semaines-session').textContent = '-';
                document.getElementById('saison-detectee').textContent = '-';
            }
        } else {
            document.getElementById('duree-session').textContent = '-';
            document.getElementById('semaines-session').textContent = '-';
            document.getElementById('saison-detectee').textContent = '-';
        }
    }
    
    // Écouteurs d'événements
    dateDebut.addEventListener('change', calculerDuree);
    dateFin.addEventListener('change', calculerDuree);
    
    // Calcul initial
    calculerDuree();
});

// Fonction pour remplir automatiquement selon la saison
function remplirSaison() {
    const select = document.getElementById('saison_rapide');
    const nom = document.getElementById('nom');
    const mois = document.getElementById('mois');
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    
    if (!select.value) return;
    
    const annee = select.value.includes('next') ? new Date().getFullYear() + 1 : new Date().getFullYear();
    const saison = select.value.replace('_next', '');
    
    switch(saison) {
        case 'hiver':
            nom.value = `Hiver ${annee}`;
            mois.value = 'Jan-Mar';
            dateDebut.value = `${annee}-01-01`;
            dateFin.value = `${annee}-03-31`;
            break;
        case 'printemps':
            nom.value = `Printemps ${annee}`;
            mois.value = 'Avr-Juin';
            dateDebut.value = `${annee}-04-01`;
            dateFin.value = `${annee}-06-30`;
            break;
        case 'ete':
            nom.value = `Été ${annee}`;
            mois.value = 'Juil-Sep';
            dateDebut.value = `${annee}-07-01`;
            dateFin.value = `${annee}-09-30`;
            break;
        case 'automne':
            nom.value = `Automne ${annee}`;
            mois.value = 'Oct-Déc';
            dateDebut.value = `${annee}-10-01`;
            dateFin.value = `${annee}-12-31`;
            break;
    }
    
    // Recalculer après remplissage
    dateDebut.dispatchEvent(new Event('change'));
}
</script>
@endsection
