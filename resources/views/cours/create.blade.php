@extends('layouts.admin')

@section('title', 'Créer un cours')

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
                               value="{{ old('nom') }}" 
                               placeholder="Ex: Yoga débutant, Pilates avancé..."
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
                                <option value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>
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
                                  placeholder="Décrivez le cours, les objectifs, le niveau requis...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jours de la semaine -->
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-calendar-week me-2"></i>
                            Jours de cours *
                        </label>
                        <div class="jours-selector">
                            @php
                                $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
                                $joursLabels = [
                                    'lundi' => 'Lundi',
                                    'mardi' => 'Mardi', 
                                    'mercredi' => 'Mercredi',
                                    'jeudi' => 'Jeudi',
                                    'vendredi' => 'Vendredi',
                                    'samedi' => 'Samedi',
                                    'dimanche' => 'Dimanche'
                                ];
                            @endphp
                            @foreach($jours as $jour)
                                <label class="jour-checkbox">
                                    <input type="checkbox" 
                                           name="jours[]" 
                                           value="{{ $jour }}" 
                                           {{ (is_array(old('jours')) && in_array($jour, old('jours'))) ? 'checked' : '' }}>
                                    <span>{{ $joursLabels[$jour] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('jours')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="jours-preview"></div>
                    </div>

                    <!-- Horaires -->
                    <div class="col-md-6">
                        <label for="heure_debut" class="form-label">
                            <i class="fas fa-clock me-2"></i>
                            Heure de début *
                        </label>
                        <input type="time" 
                               class="form-control @error('heure_debut') is-invalid @enderror" 
                               id="heure_debut" 
                               name="heure_debut" 
                               value="{{ old('heure_debut') }}" 
                               required>
                        @error('heure_debut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="heure_fin" class="form-label">
                            <i class="fas fa-clock me-2"></i>
                            Heure de fin *
                        </label>
                        <input type="time" 
                               class="form-control @error('heure_fin') is-invalid @enderror" 
                               id="heure_fin" 
                               name="heure_fin" 
                               value="{{ old('heure_fin') }}" 
                               required>
                        @error('heure_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                               value="{{ old('places_max', 20) }}" 
                               min="1" 
                               max="100" 
                               required>
                        @error('places_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Entre 1 et 100 places</small>
                    </div>

                    <!-- Instructeur (optionnel) -->
                    <div class="col-md-6">
                        <label for="instructeur" class="form-label">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Instructeur
                        </label>
                        <input type="text" 
                               class="form-control @error('instructeur') is-invalid @enderror" 
                               id="instructeur" 
                               name="instructeur" 
                               value="{{ old('instructeur') }}" 
                               placeholder="Nom de l'instructeur">
                        @error('instructeur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Niveau (optionnel) -->
                    <div class="col-md-6">
                        <label for="niveau" class="form-label">
                            <i class="fas fa-layer-group me-2"></i>
                            Niveau
                        </label>
                        <select class="form-select @error('niveau') is-invalid @enderror" 
                                id="niveau" 
                                name="niveau">
                            <option value="">Sélectionner un niveau</option>
                            <option value="debutant" {{ old('niveau') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermediaire" {{ old('niveau') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="avance" {{ old('niveau') == 'avance' ? 'selected' : '' }}>Avancé</option>
                            <option value="tous_niveaux" {{ old('niveau') == 'tous_niveaux' ? 'selected' : '' }}>Tous niveaux</option>
                        </select>
                        @error('niveau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tarif (optionnel) -->
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
                                   value="{{ old('tarif') }}" 
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

        <!-- Aide contextuelle -->
        <div class="form-container">
            <h5 class="mb-3">
                <i class="fas fa-info-circle me-2 text-info"></i>
                Aide pour créer un cours
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="help-card">
                        <h6><i class="fas fa-lightbulb me-2"></i>Conseils pour le nom</h6>
                        <ul class="mb-0">
                            <li>Soyez spécifique (ex: "Yoga Hatha débutant")</li>
                            <li>Mentionnez le niveau si pertinent</li>
                            <li>Gardez un nom court et descriptif</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="help-card">
                        <h6><i class="fas fa-calendar me-2"></i>Planification</h6>
                        <ul class="mb-0">
                            <li>Choisissez des créneaux récurrents</li>
                            <li>Prévoyez du temps pour l'installation</li>
                            <li>Considérez les pauses entre cours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques pour le formulaire */
.help-card {
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    height: 100%;
}

.help-card h6 {
    color: #17a2b8;
    margin-bottom: 1rem;
    font-weight: 600;
}

.help-card ul {
    color: rgba(255, 255, 255, 0.8);
    list-style: none;
    padding: 0;
}

.help-card li {
    margin-bottom: 0.5rem;
    padding-left: 1.5rem;
    position: relative;
}

.help-card li::before {
    content: "→";
    position: absolute;
    left: 0;
    color: #17a2b8;
    font-weight: bold;
}

.input-group-text {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.8);
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
}

.text-danger {
    color: #dc3545 !important;
}
</style>
@endsection
