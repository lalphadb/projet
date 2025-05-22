@extends('layouts.admin')

@section('title', 'Modifier le cours')

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
                    <i class="fas fa-edit"></i>
                    Modifier le cours : {{ $cours->nom }}
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('cours.show', $cours) }}" class="btn-secondary">
                        <i class="fas fa-eye me-2"></i>
                        Voir le cours
                    </a>
                    <a href="{{ route('cours.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations sur les inscriptions -->
        @if($cours->inscriptions->count() > 0)
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-3"></i>
                    <div>
                        <strong>Attention :</strong> Ce cours a actuellement {{ $cours->inscriptions->count() }} inscription(s).
                        Les modifications importantes (horaires, jours) peuvent affecter les membres inscrits.
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire de modification -->
        <div class="form-container">
            <form action="{{ route('cours.update', $cours) }}" method="POST" id="cours-form">
                @csrf
                @method('PUT')
                
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
                               value="{{ old('nom', $cours->nom) }}" 
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
                                <option value="{{ $session->id }}" 
                                        {{ (old('session_id', $cours->session_id) == $session->id) ? 'selected' : '' }}>
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
                                  placeholder="Décrivez le cours, les objectifs, le niveau requis...">{{ old('description', $cours->description) }}</textarea>
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
                                $coursJours = old('jours', is_array($cours->jours) ? $cours->jours : json_decode($cours->jours, true) ?? []);
                            @endphp
                            @foreach($jours as $jour)
                                <label class="jour-checkbox">
                                    <input type="checkbox" 
                                           name="jours[]" 
                                           value="{{ $jour }}" 
                                           {{ (is_array($coursJours) && in_array($jour, $coursJours)) ? 'checked' : '' }}>
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
                               value="{{ old('heure_debut', $cours->heure_debut) }}" 
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
                               value="{{ old('heure_fin', $cours->heure_fin) }}" 
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
                               value="{{ old('places_max', $cours->places_max) }}" 
                               min="{{ $cours->inscriptions->count() }}" 
                               max="100" 
                               required>
                        @error('places_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Places occupées : {{ $cours->inscriptions->count() }} / {{ $cours->places_max }}
                            @if($cours->inscriptions->count() > 0)
                                <br><span class="text-warning">⚠️ Minimum {{ $cours->inscriptions->count() }} (inscrits actuels)</span>
                            @endif
                        </small>
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
                               value="{{ old('instructeur', $cours->instructeur) }}" 
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
                            <option value="debutant" {{ old('niveau', $cours->niveau) == 'debutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermediaire" {{ old('niveau', $cours->niveau) == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="avance" {{ old('niveau', $cours->niveau) == 'avance' ? 'selected' : '' }}>Avancé</option>
                            <option value="tous_niveaux" {{ old('niveau', $cours->niveau) == 'tous_niveaux' ? 'selected' : '' }}>Tous niveaux</option>
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
                                   value="{{ old('tarif', $cours->tarif) }}" 
                                   step="0.01" 
                                   min="0" 
                                   placeholder="0.00">
                            <span class="input-group-text">$</span>
                        </div>
                        @error('tarif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div class="col-md-6">
                        <label for="statut" class="form-label">
                            <i class="fas fa-toggle-on me-2"></i>
                            Statut du cours
                        </label>
                        <select class="form-select @error('statut') is-invalid @enderror" 
                                id="statut" 
                                name="statut">
                            <option value="actif" {{ old('statut', $cours->statut ?? 'actif') == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ old('statut', $cours->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                            <option value="complet" {{ old('statut', $cours->statut) == 'complet' ? 'selected' : '' }}>Complet</option>
                            <option value="annule" {{ old('statut', $cours->statut) == 'annule' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <strong>Actif :</strong> Visible et ouvert aux inscriptions<br>
                            <strong>Inactif :</strong> Caché des listes publiques<br>
                            <strong>Complet :</strong> Visible mais fermé aux nouvelles inscriptions<br>
                            <strong>Annulé :</strong> Cours annulé, inscriptions fermées
                        </small>
                    </div>
                </div>

                <!-- Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-3 justify-content-between">
                            <div>
                                <button type="button" class="btn-secondary" onclick="showDuplicateModal()">
                                    <i class="fas fa-copy me-2"></i>
                                    Dupliquer pour une autre session
                                </button>
                            </div>
                            <div class="d-flex gap-3">
                                <a href="{{ route('cours.show', $cours) }}" class="btn-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques du cours -->
        <div class="form-container">
            <h5 class="mb-3">
                <i class="fas fa-chart-line me-2 text-info"></i>
                Statistiques du cours
            </h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $cours->inscriptions->count() }}</div>
                            <div class="stat-label">Inscrits</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">
                                {{ $cours->places_max > 0 ? round(($cours->inscriptions->count() / $cours->places_max) * 100) : 0 }}%
                            </div>
                            <div class="stat-label">Taux de remplissage</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">
                                @if(is_array($cours->jours))
                                    {{ count($cours->jours) }}
                                @else
                                    {{ count(json_decode($cours->jours, true) ?? []) }}
                                @endif
                            </div>
                            <div class="stat-label">Jours par semaine</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            @php
                                $debut = \Carbon\Carbon::createFromFormat('H:i', $cours->heure_debut);
                                $fin = \Carbon\Carbon::createFromFormat('H:i', $cours->heure_fin);
                                $duree = $fin->diffInMinutes($debut);
                            @endphp
                            <div class="stat-value">{{ $duree }}min</div>
                            <div class="stat-label">Durée par séance</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale de duplication -->
<div class="modal fade" id="duplicateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-copy me-2"></i>
                    Dupliquer le cours
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cours.duplicate', $cours) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-white-50 mb-3">
                        Sélectionnez la session de destination pour dupliquer ce cours avec tous ses paramètres.
                    </p>
                    <div class="mb-3">
                        <label for="target_session_id" class="form-label text-white">Session de destination</label>
                        <select class="form-select" id="target_session_id" name="target_session_id" required>
                            <option value="">Choisir une session</option>
                            @foreach($sessions as $session)
                                @if($session->id !== $cours->session_id)
                                    <option value="{{ $session->id }}">
                                        {{ $session->nom }} ({{ $session->mois }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="copy_inscriptions" name="copy_inscriptions">
                        <label class="form-check-label text-white" for="copy_inscriptions">
                            Copier également les inscriptions actuelles
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-copy me-2"></i>
                        Dupliquer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles pour les statistiques */
.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    height: 100%;
    transition: all 0.3s ease;
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-2px);
}

.stat-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(45deg, #17a2b8, #20c997);
    border-radius: 12px;
    color: white;
    font-size: 1.5rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}

.stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
    margin-top: 0.25rem;
}

/* Styles pour la modale Bootstrap */
.modal-content {
    background: #2c3e50 !important;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-header {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(220, 53, 69, 0.1));
}

.form-select {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
}

.form-select:focus {
    background-color: rgba(255, 255, 255, 0.15);
    border-color: #17a2b8;
    color: #fff;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.form-select option {
    background-color: #2c3e50;
    color: #fff;
}

.form-check-input:checked {
    background-color: #17a2b8;
    border-color: #17a2b8;
}

.alert-info {
    background: rgba(23, 162, 184, 0.1);
    border: 1px solid rgba(23, 162, 184, 0.3);
    color: #fff;
    border-radius: 12px;
}
</style>

<script>
function showDuplicateModal() {
    const modal = new bootstrap.Modal(document.getElementById('duplicateModal'));
    modal.show();
}
</script>
@endsection
