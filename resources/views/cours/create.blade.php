@extends('layouts.admin')

@section('title', 'Créer un cours')

@push('styles')
<link href="{{ asset('css/cours.css') }}" rel="stylesheet">
<style>
/* Styles pour les horaires multiples */
.horaires-container {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.horaire-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    position: relative;
}

.horaire-item:last-child {
    margin-bottom: 0;
}

.horaire-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.horaire-title {
    color: #17a2b8;
    font-weight: 600;
    font-size: 1rem;
}

.btn-remove-horaire {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-remove-horaire:hover {
    background: #c82333;
    transform: scale(1.1);
}

.btn-add-horaire {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-add-horaire:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.horaire-preview {
    background: rgba(23, 162, 184, 0.1);
    border: 1px solid rgba(23, 162, 184, 0.3);
    border-radius: 6px;
    padding: 0.75rem;
    margin-top: 1rem;
    color: #17a2b8;
    font-weight: 500;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="cours-container">
        <!-- En-tête -->
        <div class="cours-header">
            <div class="cours-title">
                <div class="title-content">
                    <i class="fas fa-plus-circle"></i>
                    Créer un nouveau cours
                </div>
                <a href="{{ route('cours.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>

        <!-- Formulaire de création -->
        <div class="form-container">
            <form action="{{ route('cours.store') }}" method="POST" id="cours-form">
                @csrf
                
                <div class="row g-4">
                    <!-- Nom du cours -->
                    <div class="col-md-8">
                        <label for="nom" class="form-label">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Nom du cours *
                        </label>
                        <input type="text" 
                               class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom', $coursOriginal->nom ?? '') }}" 
                               placeholder="Ex: Karaté débutant, Judo avancé..."
                               required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Session -->
                    <div class="col-md-4">
                        <label for="session_id" class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Session *
                        </label>
                        <select class="form-select @error('session_id') is-invalid @enderror" 
                                id="session_id" 
                                name="session_id" 
                                required>
                            <option value="">Sélectionner une session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" 
                                        {{ old('session_id', $coursOriginal->session_id ?? '') == $session->id ? 'selected' : '' }}>
                                    {{ $session->nom }} ({{ $session->mois }})
                                </option>
                            @endforeach
                        </select>
                        @error('session_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-2"></i>
                            Description
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Décrivez le cours, les objectifs, le niveau requis...">{{ old('description', $coursOriginal->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SECTION HORAIRES MULTIPLES -->
                <div class="horaires-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Plages Horaires *
                        </h5>
                        <button type="button" class="btn-add-horaire" onclick="ajouterHoraire()">
                            <i class="fas fa-plus"></i>
                            Ajouter une plage
                        </button>
                    </div>

                    <div id="horaires-container">
                        @if($coursOriginal && $coursOriginal->horaires->count() > 0)
                            @foreach($coursOriginal->horaires as $index => $horaire)
                                <div class="horaire-item" data-index="{{ $index }}">
                                    <div class="horaire-header">
                                        <span class="horaire-title">Plage horaire #{{ $index + 1 }}</span>
                                        @if($index > 0)
                                            <button type="button" class="btn-remove-horaire" onclick="supprimerHoraire(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label text-white">Jour *</label>
                                            <select name="horaires[{{ $index }}][jour]" class="form-select" required>
                                                <option value="">Choisir</option>
                                                <option value="lundi" {{ $horaire->jour == 'lundi' ? 'selected' : '' }}>Lundi</option>
                                                <option value="mardi" {{ $horaire->jour == 'mardi' ? 'selected' : '' }}>Mardi</option>
                                                <option value="mercredi" {{ $horaire->jour == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                                                <option value="jeudi" {{ $horaire->jour == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                                                <option value="vendredi" {{ $horaire->jour == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                                                <option value="samedi" {{ $horaire->jour == 'samedi' ? 'selected' : '' }}>Samedi</option>
                                                <option value="dimanche" {{ $horaire->jour == 'dimanche' ? 'selected' : '' }}>Dimanche</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label text-white">Heure début *</label>
                                            <input type="time" name="horaires[{{ $index }}][heure_debut]" 
                                                   class="form-control" value="{{ $horaire->heure_debut }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label text-white">Heure fin *</label>
                                            <input type="time" name="horaires[{{ $index }}][heure_fin]" 
                                                   class="form-control" value="{{ $horaire->heure_fin }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label text-white">Salle</label>
                                            <input type="text" name="horaires[{{ $index }}][salle]" 
                                                   class="form-control" value="{{ $horaire->salle }}" 
                                                   placeholder="Ex: Dojo A">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-white">Notes</label>
                                            <input type="text" name="horaires[{{ $index }}][notes]" 
                                                   class="form-control" value="{{ $horaire->notes }}" 
                                                   placeholder="Informations spécifiques à ce créneau...">
                                        </div>
                                    </div>
                                    
                                    <div class="horaire-preview" style="display: none;"></div>
                                </div>
                            @endforeach
                        @else
                            <!-- Premier horaire par défaut -->
                            <div class="horaire-item" data-index="0">
                                <div class="horaire-header">
                                    <span class="horaire-title">Plage horaire #1</span>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Jour *</label>
                                        <select name="horaires[0][jour]" class="form-select" required>
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
                                        <label class="form-label text-white">Heure début *</label>
                                        <input type="time" name="horaires[0][heure_debut]" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Heure fin *</label>
                                        <input type="time" name="horaires[0][heure_fin]" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Salle</label>
                                        <input type="text" name="horaires[0][salle]" class="form-control" placeholder="Ex: Dojo A">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-white">Notes</label>
                                        <input type="text" name="horaires[0][notes]" class="form-control" 
                                               placeholder="Informations spécifiques à ce créneau...">
                                    </div>
                                </div>
                                
                                <div class="horaire-preview" style="display: none;"></div>
                            </div>
                        @endif
                    </div>

                    @error('horaires')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Autres champs du cours -->
                <div class="row g-4">
                    <!-- Nombre de places -->
                    <div class="col-md-6">
                        <label for="places_max" class="form-label">
                            <i class="fas fa-users me-2"></i>
                            Nombre de places maximum *
                        </label>
                        <input type="number" 
                               class="form-control @error('places_max') is-invalid @enderror" 
                               id="places_max" 
                               name="places_max" 
                               value="{{ old('places_max', $coursOriginal->places_max ?? 20) }}" 
                               min="1" 
                               max="100" 
                               required>
                        @error('places_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Instructeur -->
                    <div class="col-md-6">
                        <label for="instructeur" class="form-label">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Instructeur
                        </label>
                        <input type="text" 
                               class="form-control @error('instructeur') is-invalid @enderror" 
                               id="instructeur" 
                               name="instructeur" 
                               value="{{ old('instructeur', $coursOriginal->instructeur ?? '') }}" 
                               placeholder="Nom de l'instructeur">
                        @error('instructeur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Niveau -->
                    <div class="col-md-6">
                        <label for="niveau" class="form-label">
                            <i class="fas fa-layer-group me-2"></i>
                            Niveau
                        </label>
                        <select class="form-select @error('niveau') is-invalid @enderror" 
                                id="niveau" 
                                name="niveau">
                            <option value="">Sélectionner un niveau</option>
                            <option value="debutant" {{ old('niveau', $coursOriginal->niveau ?? '') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermediaire" {{ old('niveau', $coursOriginal->niveau ?? '') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="avance" {{ old('niveau', $coursOriginal->niveau ?? '') == 'avance' ? 'selected' : '' }}>Avancé</option>
                            <option value="tous_niveaux" {{ old('niveau', $coursOriginal->niveau ?? '') == 'tous_niveaux' ? 'selected' : '' }}>Tous niveaux</option>
                        </select>
                        @error('niveau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tarif -->
                    <div class="col-md-6">
                        <label for="tarif" class="form-label">
                            <i class="fas fa-dollar-sign me-2"></i>
                            Tarif par séance
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('tarif') is-invalid @enderror" 
                                   id="tarif" 
                                   name="tarif" 
                                   value="{{ old('tarif', $coursOriginal->tarif ?? '') }}" 
                                   step="0.01" 
                                   min="0" 
                                   placeholder="0.00">
                            <span class="input-group-text">$</span>
                        </div>
                        @error('tarif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('cours.index') }}" class="btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Créer le cours
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let horaireIndex = {{ $coursOriginal && $coursOriginal->horaires->count() > 0 ? $coursOriginal->horaires->count() : 1 }};

function ajouterHoraire() {
    const container = document.getElementById('horaires-container');
    const nouvelHoraire = document.createElement('div');
    nouvelHoraire.className = 'horaire-item';
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
                <label class="form-label text-white">Jour *</label>
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
                <label class="form-label text-white">Heure début *</label>
                <input type="time" name="horaires[${horaireIndex}][heure_debut]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label text-white">Heure fin *</label>
                <input type="time" name="horaires[${horaireIndex}][heure_fin]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label text-white">Salle</label>
                <input type="text" name="horaires[${horaireIndex}][salle]" class="form-control" placeholder="Ex: Dojo A">
            </div>
            <div class="col-12">
                <label class="form-label text-white">Notes</label>
                <input type="text" name="horaires[${horaireIndex}][notes]" class="form-control" 
                       placeholder="Informations spécifiques à ce créneau...">
            </div>
        </div>
        
        <div class="horaire-preview" style="display: none;"></div>
    `;
    
    container.appendChild(nouvelHoraire);
    horaireIndex++;
    
    // Animation d'apparition
    nouvelHoraire.style.opacity = '0';
    nouvelHoraire.style.transform = 'translateY(20px)';
    setTimeout(() => {
        nouvelHoraire.style.transition = 'all 0.3s ease';
        nouvelHoraire.style.opacity = '1';
        nouvelHoraire.style.transform = 'translateY(0)';
    }, 10);
}

function supprimerHoraire(button) {
    const horaireItem = button.closest('.horaire-item');
    horaireItem.style.transition = 'all 0.3s ease';
    horaireItem.style.opacity = '0';
    horaireItem.style.transform = 'translateX(-100%)';
    
    setTimeout(() => {
        horaireItem.remove();
        mettreAJourNumeros();
    }, 300);
}

function mettreAJourNumeros() {
    const horaires = document.querySelectorAll('.horaire-item');
    horaires.forEach((horaire, index) => {
        const titre = horaire.querySelector('.horaire-title');
        titre.textContent = `Plage horaire #${index + 1}`;
    });
}

// Validation en temps réel
document.addEventListener('change', function(e) {
    if (e.target.matches('select[name*="[jour]"], input[name*="[heure_debut]"], input[name*="[heure_fin]"]')) {
        const horaireItem = e.target.closest('.horaire-item');
        const jour = horaireItem.querySelector('select[name*="[jour]"]').value;
        const debut = horaireItem.querySelector('input[name*="[heure_debut]"]').value;
        const fin = horaireItem.querySelector('input[name*="[heure_fin]"]').value;
        
        const preview = horaireItem.querySelector('.horaire-preview');
        
        if (jour && debut && fin) {
            const jourLabels = {
                'lundi': 'Lundi', 'mardi': 'Mardi', 'mercredi': 'Mercredi',
                'jeudi': 'Jeudi', 'vendredi': 'Vendredi', 'samedi': 'Samedi', 'dimanche': 'Dimanche'
            };
            
            preview.textContent = `${jourLabels[jour]} de ${debut} à ${fin}`;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }
});
</script>
@endsection
