@extends('layouts.admin')

@section('title', 'Modifier le cours : ' . $cours->nom)

@push('styles')
<link href="{{ asset('css/cours/sessions-duplication.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('cours.show', $cours) }}" class="btn btn-secondary-glass me-3">
                    <i class="fas fa-arrow-left"></i> Retour au cours
                </a>
                <div>
                    <h1 class="h2 mb-1" style="color: #e2e8f0;">
                        <i class="fas fa-edit me-2"></i>
                        Modifier le cours
                    </h1>
                    <p class="text-muted mb-0">{{ $cours->nom }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerte réinscriptions actives -->
    @if($cours->session && $cours->session->inscriptions_actives)
    <div class="alert alert-info mb-4" style="background: rgba(66, 153, 225, 0.1); border: 1px solid rgba(66, 153, 225, 0.3); border-radius: 10px; color: #90cdf4;">
        <i class="fas fa-users me-2"></i>
        <strong>Réinscriptions actives</strong> pour cette session. Les membres peuvent se réinscrire à ce cours.
        <a href="{{ route('cours.inscriptions', $cours) }}" class="btn btn-sm btn-outline-info ms-3">
            Voir les inscriptions
        </a>
    </div>
    @endif

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);">
                <div class="card-header" style="background: rgba(45, 55, 72, 0.6); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                    <h5 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-edit me-2"></i>
                        Informations du cours
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cours.update', $cours) }}" id="formModifierCours">
                        @csrf
                        @method('PUT')

                        <!-- Informations de base -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="nom" class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                        Nom du cours *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" 
                                           value="{{ old('nom', $cours->nom) }}" 
                                           placeholder="Ex: Karaté Adultes Débutants"
                                           style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;"
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="places_max" class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                        Places maximum *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('places_max') is-invalid @enderror" 
                                           id="places_max" name="places_max" 
                                           value="{{ old('places_max', $cours->places_max) }}" 
                                           min="{{ $cours->inscriptions->count() }}" max="100"
                                           style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;"
                                           required>
                                    @error('places_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text" style="color: #a0aec0;">
                                        Minimum {{ $cours->inscriptions->count() }} (inscriptions actuelles)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Session et Statut -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="session_id" class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                        Session *
                                    </label>
                                    <select class="form-control @error('session_id') is-invalid @enderror" 
                                            id="session_id" name="session_id" 
                                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;"
                                            required>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}" 
                                                    {{ old('session_id', $cours->session_id) == $session->id ? 'selected' : '' }}
                                                    style="background: rgba(45, 55, 72, 1); color: #e2e8f0;">
                                                {{ $session->nom }}
                                                @if(!$session->inscriptions_actives)
                                                    (Inscriptions fermées)
                                                @endif
                                                - {{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('session_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="statut" class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                        Statut
                                    </label>
                                    <select class="form-control @error('statut') is-invalid @enderror" 
                                            id="statut" name="statut"
                                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                                        <option value="actif" {{ old('statut', $cours->statut) == 'actif' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Actif</option>
                                        <option value="inactif" {{ old('statut', $cours->statut) == 'inactif' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Inactif</option>
                                        <option value="complet" {{ old('statut', $cours->statut) == 'complet' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Complet</option>
                                        <option value="annule" {{ old('statut', $cours->statut) == 'annule' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Annulé</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-3">
                            <label for="description" class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3"
                                      placeholder="Décrivez le cours, les objectifs, le niveau requis..."
                                      style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">{{ old('description', $cours->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Instructeur, niveau et tarif -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="instructeur" class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                        Instructeur
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('instructeur') is-invalid @enderror" 
                                           id="instructeur" name="instructeur" 
                                           value="{{ old('instructeur', $cours->instructeur) }}" 
                                           placeholder="Nom de l'instructeur"
                                           style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                                    @error('instructeur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="niveau" class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                        Niveau
                                    </label>
                                    <select class="form-control @error('niveau') is-invalid @enderror" 
                                            id="niveau" name="niveau"
                                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                                        <option value="">Sélectionner</option>
                                        <option value="debutant" {{ old('niveau', $cours->niveau) == 'debutant' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Débutant</option>
                                        <option value="intermediaire" {{ old('niveau', $cours->niveau) == 'intermediaire' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Intermédiaire</option>
                                        <option value="avance" {{ old('niveau', $cours->niveau) == 'avance' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Avancé</option>
                                        <option value="tous_niveaux" {{ old('niveau', $cours->niveau) == 'tous_niveaux' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Tous niveaux</option>
                                    </select>
                                    @error('niveau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="tarif" class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                        Tarif mensuel ($)
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('tarif') is-invalid @enderror" 
                                           id="tarif" name="tarif" 
                                           value="{{ old('tarif', $cours->tarif) }}" 
                                           min="0" step="0.01"
                                           placeholder="Ex: 75.00"
                                           style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                                    @error('tarif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Plages horaires existantes -->
                        <div class="form-group mb-4">
                            <label class="form-label" style="color: #e2e8f0; font-weight: 600;">
                                <i class="fas fa-clock me-2"></i>Plages horaires *
                            </label>
                            <div id="plages-horaires-container">
                                @php
                                    $plagesExistantes = [];
                                    if ($cours->plages_horaires) {
                                        $plagesExistantes = is_string($cours->plages_horaires) 
                                            ? json_decode($cours->plages_horaires, true) 
                                            : $cours->plages_horaires;
                                    } elseif ($cours->jours && $cours->heure_debut && $cours->heure_fin) {
                                        // Fallback pour anciens cours
                                        $jours = is_string($cours->jours) ? json_decode($cours->jours, true) : $cours->jours;
                                        $plagesExistantes = [[
                                            'jours' => $jours ?? [],
                                            'heure_debut' => $cours->heure_debut,
                                            'heure_fin' => $cours->heure_fin
                                        ]];
                                    }
                                @endphp

                                @if(count($plagesExistantes) > 0)
                                    @foreach($plagesExistantes as $index => $plage)
                                    <div class="plage-horaire-item" style="background: rgba(45, 55, 72, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 20px; margin-bottom: 15px;">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 style="color: #48bb78; margin: 0;">
                                                <i class="fas fa-calendar-day me-2"></i>Plage horaire #{{ $index + 1 }}
                                            </h6>
                                            @if($index > 0)
                                            <button type="button" class="btn btn-sm btn-outline-danger supprimer-plage">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                        
                                        <!-- Jours de la semaine -->
                                        <div class="mb-3">
                                            <label style="color: #cbd5e0; font-weight: 600; display: block; margin-bottom: 10px;">
                                                Jours de la semaine
                                            </label>
                                            <div class="row">
                                                @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="plages_horaires[{{ $index }}][jours][]" 
                                                               value="{{ $jour }}" 
                                                               id="jour_{{ $index }}_{{ $jour }}"
                                                               {{ in_array($jour, $plage['jours'] ?? []) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="jour_{{ $index }}_{{ $jour }}" style="color: #a0aec0;">
                                                            {{ ucfirst($jour) }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Heures -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label style="color: #cbd5e0; font-weight: 600;">Heure début</label>
                                                <input type="time" 
                                                       class="form-control" 
                                                       name="plages_horaires[{{ $index }}][heure_debut]" 
                                                       value="{{ $plage['heure_debut'] ?? '' }}"
                                                       style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;"
                                                       required>
                                            </div>
                                            <div class="col-md-6">
                                                <label style="color: #cbd5e0; font-weight: 600;">Heure fin</label>
                                                <input type="time" 
                                                       class="form-control" 
                                                       name="plages_horaires[{{ $index }}][heure_fin]" 
                                                       value="{{ $plage['heure_fin'] ?? '' }}"
                                                       style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <!-- Plage par défaut si aucune existante -->
                                    <div class="plage-horaire-item" style="background: rgba(45, 55, 72, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 20px; margin-bottom: 15px;">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 style="color: #48bb78; margin: 0;">
                                                <i class="fas fa-calendar-day me-2"></i>Plage horaire #1
                                            </h6>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label style="color: #cbd5e0; font-weight: 600; display: block; margin-bottom: 10px;">Jours de la semaine</label>
                                            <div class="row">
                                                @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="plages_horaires[0][jours][]" 
                                                               value="{{ $jour }}" 
                                                               id="jour_0_{{ $jour }}">
                                                        <label class="form-check-label" for="jour_0_{{ $jour }}" style="color: #a0aec0;">
                                                            {{ ucfirst($jour) }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label style="color: #cbd5e0; font-weight: 600;">Heure début</label>
                                                <input type="time" class="form-control" name="plages_horaires[0][heure_debut]" 
                                                       style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label style="color: #cbd5e0; font-weight: 600;">Heure fin</label>
                                                <input type="time" class="form-control" name="plages_horaires[0][heure_fin]" 
                                                       style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;" required>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" class="btn btn-outline-success" id="ajouterPlageHoraire">
                                <i class="fas fa-plus me-2"></i>Ajouter une plage horaire
                            </button>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('cours.inscriptions', $cours) }}" class="btn btn-outline-info">
                                    <i class="fas fa-users me-2"></i>Voir les inscriptions ({{ $cours->inscriptions->count() }})
                                </a>
                            </div>
                            <div class="d-flex gap-3">
                                <a href="{{ route('cours.show', $cours) }}" class="btn btn-secondary-glass">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-success-gradient">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="col-lg-4">
            <div class="card" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);">
                <div class="card-header" style="background: rgba(45, 55, 72, 0.6);">
                    <h6 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques du cours
                    </h6>
                </div>
                <div class="card-body">
                    <div class="session-stats">
                        <div class="stat-card">
                            <span class="stat-number">{{ $cours->inscriptions->where('statut', 'actif')->count() }}</span>
                            <div class="stat-label">Inscrits Actifs</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number">{{ $cours->places_max - $cours->inscriptions->where('statut', 'actif')->count() }}</span>
                            <div class="stat-label">Places Libres</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number">{{ $cours->places_max > 0 ? round(($cours->inscriptions->where('statut', 'actif')->count() / $cours->places_max) * 100) : 0 }}%</span>
                            <div class="stat-label">Taux Remplissage</div>
                        </div>
                        @if($cours->tarif)
                        <div class="stat-card">
                            <span class="stat-number">{{ number_format($cours->tarif * $cours->inscriptions->where('statut', 'actif')->count(), 0) }}$</span>
                            <div class="stat-label">Revenus Mensuel</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card mt-3" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="card-header" style="background: rgba(45, 55, 72, 0.6);">
                    <h6 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('cours.inscriptions', $cours) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users me-2"></i>Gérer les inscriptions
                        </a>
                        <a href="{{ route('cours.duplicate', $cours) }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-copy me-2"></i>Dupliquer ce cours
                        </a>
                        @if($cours->session)
                        <a href="{{ route('cours.sessions.show', $cours->session) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-calendar me-2"></i>Voir la session
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/cours/sessions-duplication.js') }}"></script>
<script>
// Gestion des plages horaires multiples (identique à create)
document.addEventListener('DOMContentLoaded', function() {
    let plageIndex = {{ count($plagesExistantes) }};
    
    document.getElementById('ajouterPlageHoraire').addEventListener('click', function() {
        const container = document.getElementById('plages-horaires-container');
        const newPlage = document.createElement('div');
        newPlage.className = 'plage-horaire-item';
        newPlage.style.cssText = 'background: rgba(45, 55, 72, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 20px; margin-bottom: 15px;';
        
        newPlage.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 style="color: #48bb78; margin: 0;">
                    <i class="fas fa-calendar-day me-2"></i>Plage horaire #${plageIndex + 1}
                </h6>
                <button type="button" class="btn btn-sm btn-outline-danger supprimer-plage">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="mb-3">
                <label style="color: #cbd5e0; font-weight: 600; display: block; margin-bottom: 10px;">Jours de la semaine</label>
                <div class="row">
                    ${['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'].map(jour => `
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="plages_horaires[${plageIndex}][jours][]" 
                                       value="${jour}" 
                                       id="jour_${plageIndex}_${jour}">
                                <label class="form-check-label" for="jour_${plageIndex}_${jour}" style="color: #a0aec0;">
                                    ${jour.charAt(0).toUpperCase() + jour.slice(1)}
                                </label>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label style="color: #cbd5e0; font-weight: 600;">Heure début</label>
                    <input type="time" class="form-control" name="plages_horaires[${plageIndex}][heure_debut]" 
                           style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;" required>
                </div>
                <div class="col-md-6">
                    <label style="color: #cbd5e0; font-weight: 600;">Heure fin</label>
                    <input type="time" class="form-control" name="plages_horaires[${plageIndex}][heure_fin]" 
                           style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;" required>
                </div>
            </div>
        `;
        
        container.appendChild(newPlage);
        plageIndex++;
        
        // Ajouter event listener pour supprimer
        newPlage.querySelector('.supprimer-plage').addEventListener('click', function() {
            newPlage.remove();
        });
    });

// Event listeners pour supprimer les plages existantes
   document.querySelectorAll('.supprimer-plage').forEach(btn => {
       btn.addEventListener('click', function() {
           this.closest('.plage-horaire-item').remove();
       });
   });
});
</script>
@endpush
