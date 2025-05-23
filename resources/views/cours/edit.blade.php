@extends('layouts.admin')

@section('title', 'Modifier le cours')

@push('styles')
<link href="{{ asset('css/cours.css') }}" rel="stylesheet">
<style>
/* Styles pour les horaires multiples - Edition */
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
    transition: all 0.3s ease;
}

.horaire-item:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(23, 162, 184, 0.3);
}

.horaire-item:last-child {
    margin-bottom: 0;
}

.horaire-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.horaire-title {
    color: #17a2b8;
    font-weight: 600;
    font-size: 1rem;
}

.horaire-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
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

.horaire-existing {
    border-left: 4px solid #28a745;
}

.horaire-new {
    border-left: 4px solid #ffc107;
}

.horaire-modified {
    border-left: 4px solid #17a2b8;
}

.change-indicator {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ffc107;
    color: #000;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.inscriptions-warning {
    background: rgba(255, 193, 7, 0.1);
    border: 1px solid rgba(255, 193, 7, 0.3);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    color: #ffc107;
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

        <!-- Avertissement inscriptions -->
        @if($cours->inscriptions->count() > 0)
            <div class="inscriptions-warning">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                    <div>
                        <strong>Attention :</strong> Ce cours a actuellement {{ $cours->inscriptions->count() }} inscription(s).
                        Les modifications d'horaires peuvent affecter les membres inscrits.
                        <div class="mt-2">
                            <small>
                                <i class="fas fa-lightbulb me-1"></i>
                                Conseil : Prévenez les membres avant de modifier les horaires ou créez un nouveau cours.
                            </small>
                        </div>
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
                                        {{ old('session_id', $cours->session_id) == $session->id ? 'selected' : '' }}>
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
                </div>

                <!-- SECTION HORAIRES MULTIPLES -->
                <div class="horaires-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Plages Horaires *
                            <small class="text-muted ms-2">({{ $cours->horaires->count() }} actuellement)</small>
                        </h5>
                        <button type="button" class="btn-add-horaire" onclick="ajouterHoraire()">
                            <i class="fas fa-plus"></i>
                            Ajouter une plage
                        </button>
                    </div>

                    <div id="horaires-container">
                        @foreach($cours->horaires as $index => $horaire)
                            <div class="horaire-item horaire-existing" data-index="{{

<div class="horaire-item horaire-existing" data-index="{{ $index }}" data-horaire-id="{{ $horaire->id }}">
                                <div class="horaire-header">
                                    <div class="horaire-status">
                                        <span class="horaire-title">Plage horaire #{{ $index + 1 }}</span>
                                        <span class="badge badge-success">Existant</span>
                                    </div>
                                    @if($cours->horaires->count() > 1)
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
                                            <option value="lundi" {{ old("horaires.{$index}.jour", $horaire->jour) == 'lundi' ? 'selected' : '' }}>Lundi</option>
                                            <option value="mardi" {{ old("horaires.{$index}.jour", $horaire->jour) == 'mardi' ? 'selected' : '' }}>Mardi</option>
                                            <option value="mercredi" {{ old("horaires.{$index}.jour", $horaire->jour) == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                                            <option value="jeudi" {{ old("horaires.{$index}.jour", $horaire->jour) == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                                            <option value="vendredi" {{ old("horaires.{$index}.jour", $horaire->jour) == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                                            <option value="samedi" {{ old("horaires.{$index}.jour", $horaire->jour) == 'samedi' ? 'selected' : '' }}>Samedi</option>
                                            <option value="dimanche" {{ old("horaires.{$index}.jour", $horaire->jour) == 'dimanche' ? 'selected' : '' }}>Dimanche</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Heure début *</label>
                                        <input type="time" name="horaires[{{ $index }}][heure_debut]" 
                                               class="form-control" 
                                               value="{{ old("horaires.{$index}.heure_debut", $horaire->heure_debut) }}" 
                                               required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Heure fin *</label>
                                        <input type="time" name="horaires[{{ $index }}][heure_fin]" 
                                               class="form-control" 
                                               value="{{ old("horaires.{$index}.heure_fin", $horaire->heure_fin) }}" 
                                               required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Salle</label>
                                        <input type="text" name="horaires[{{ $index }}][salle]" 
                                               class="form-control" 
                                               value="{{ old("horaires.{$index}.salle", $horaire->salle) }}" 
                                               placeholder="Ex: Dojo A">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-white">Notes</label>
                                        <input type="text" name="horaires[{{ $index }}][notes]" 
                                               class="form-control" 
                                               value="{{ old("horaires.{$index}.notes", $horaire->notes) }}" 
                                               placeholder="Informations spécifiques à ce créneau...">
                                    </div>
                                </div>
                                
                                <div class="horaire-preview">
                                    {{ $horaire->getHoraireComplet() }}
                                    @if($horaire->salle)
                                        - Salle: {{ $horaire->salle }}
                                    @endif
                                    @if($horaire->notes)
                                        <br><small>{{ $horaire->notes }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
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
                            <div class="d-flex gap-2">
                                <button type="button" class="btn-secondary" onclick="showDuplicateModal()">
                                    <i class="fas fa-copy me-2"></i>
                                    Dupliquer pour une autre session
                                </button>
                                <button type="button" class="btn-info" onclick="previsualiserChangements()">
                                    <i class="fas fa-eye me-2"></i>
                                    Prévisualiser
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
                            <div class="stat-value">{{ $cours->horaires->count() }}</div>
                            <div class="stat-label">Plages horaires</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $cours->getDureeHebdomadaire() }}min</div>
                            <div class="stat-label">Durée par semaine</div>
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
                        Sélectionnez la session de destination pour dupliquer ce cours avec tous ses horaires.
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
                            Copier également les inscriptions actuelles ({{ $cours->inscriptions->count() }} membre(s))
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

<!-- Modale de prévisualisation -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-eye me-2"></i>
                    Prévisualisation des modifications
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="preview-content"></div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
let horaireIndex = {{ $cours->horaires->count() }};
let originalData = @json($cours->horaires->map(function($h) { 
    return [
        'jour' => $h->jour,
        'heure_debut' => $h->heure_debut,
        'heure_fin' => $h->heure_fin,
        'salle' => $h->salle,
        'notes' => $h->notes
    ];
}));

function ajouterHoraire() {
    const container = document.getElementById('horaires-container');
    const nouvelHoraire = document.createElement('div');
    nouvelHoraire.className = 'horaire-item horaire-new';
    nouvelHoraire.setAttribute('data-index', horaireIndex);
    
    nouvelHoraire.innerHTML = `
        <div class="change-indicator">N</div>
        <div class="horaire-header">
            <div class="horaire-status">
                <span class="horaire-title">Plage horaire #${horaireIndex + 1}</span>
                <span class="badge badge-warning">Nouveau</span>
            </div>
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

function showDuplicateModal() {
    const modal = new bootstrap.Modal(document.getElementById('duplicateModal'));
    modal.show();
}

function previsualiserChangements() {
    const content = document.getElementById('preview-content');
    const formData = new FormData(document.getElementById('cours-form'));
    
    let previewHtml = '<h6 class="text-white mb-3">Résumé des modifications :</h6>';
    
    // Nom du cours
    const nouveauNom = formData.get('nom');
    if (nouveauNom !== '{{ $cours->nom }}') {
        previewHtml += `<div class="alert alert-info">
            <strong>Nom:</strong> {{ $cours->nom }} → ${nouveauNom}
        </div>`;
    }
    
    // Horaires
    previewHtml += '<h6 class="text-white mt-3 mb-2">Horaires:</h6>';
    const horaires = [];
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('horaires[')) {
            // Traiter les horaires...
        }
    }
    
    previewHtml += '<div class="alert alert-success">Prévisualisation générée</div>';
    
    content.innerHTML = previewHtml;
    
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

// Validation en temps réel et détection des changements
document.addEventListener('change', function(e) {
    const target = e.target;
    
    if (target.matches('select[name*="[jour]"], input[name*="[heure_debut]"], input[name*="[heure_fin]"], input[name*="[salle]"], input[name*="[notes]"]')) {
        const horaireItem = target.closest('.horaire-item');
        const index = horaireItem.getAttribute('data-index');
        
        // Mettre à jour la prévisualisation
        const jour = horaireItem.querySelector('select[name*="[jour]"]').value;
        const debut = horaireItem.querySelector('input[name*="[heure_debut]"]').value;
        const fin = horaireItem.querySelector('input[name*="[heure_fin]"]').value;
        const salle = horaireItem.querySelector('input[name*="[salle]"]').value;
        const notes = horaireItem.querySelector('input[name*="[notes]"]').value;
        
        const preview = horaireItem.querySelector('.horaire-preview');
        
        if (jour && debut && fin) {
            const jourLabels = {
                'lundi': 'Lundi', 'mardi': 'Mardi', 'mercredi': 'Mercredi',
                'jeudi': 'Jeudi', 'vendredi': 'Vendredi', 'samedi': 'Samedi', 'dimanche': 'Dimanche'
            };
            
            let previewText = `${jourLabels[jour]} de ${debut} à ${fin}`;
            if (salle) previewText += ` - Salle: ${salle}`;
            if (notes) previewText += `<br><small>${notes}</small>`;
            
            preview.innerHTML = previewText;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
        
        // Détecter les changements pour les horaires existants
        if (horaireItem.classList.contains('horaire-existing') && originalData[index]) {
            const original = originalData[index];
            const changed = (
                jour !== original.jour ||
                debut !== original.heure_debut ||
                fin !== original.heure_fin ||
                salle !== (original.salle || '') ||
                notes !== (original.notes || '')
            );
            
            if (changed) {
                horaireItem.classList.remove('horaire-existing');
                horaireItem.classList.add('horaire-modified');
                
                let indicator = horaireItem.querySelector('.change-indicator');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.className = 'change-indicator';
                    indicator.textContent = 'M';
                    horaireItem.appendChild(indicator);
                }
                
                const badge = horaireItem.querySelector('.badge');
                badge.className = 'badge badge-info';
                badge.textContent = 'Modifié';
            } else {
                horaireItem.classList.remove('horaire-modified');
                horaireItem.classList.add('horaire-existing');
                
                const indicator = horaireItem.querySelector('.change-indicator');
                if (indicator) indicator.remove();
                
                const badge = horaireItem.querySelector('.badge');
                badge.className = 'badge badge-success';
                badge.textContent = 'Existant';
            }
        }
    }
});

// Confirmation avant soumission si des changements importants
document.getElementById('cours-form').addEventListener('submit', function(e) {
    const hasModifiedHoraires = document.querySelectorAll('.horaire-modified, .horaire-new').length > 0;
    const hasInscriptions = {{ $cours->inscriptions->count() }};
    
    if (hasModifiedHoraires && hasInscriptions > 0) {
        if (!confirm(`Attention: Ce cours a ${hasInscriptions} inscription(s). Les modifications d'horaires peuvent affecter les membres inscrits. Continuer ?`)) {
            e.preventDefault();
        }
    }
});
</script>

<!-- Styles pour les badges et indicateurs -->
<style>
.badge-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    color: #000;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-info {
    background: linear-gradient(45deg, #17a2b8, #6f42c1);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

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
</style>
@endsection
