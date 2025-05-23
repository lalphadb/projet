@extends('layouts.admin')

@section('title', 'Gestion des cours')

@push('styles')
<link href="{{ asset('css/cours/sessions-duplication.css') }}" rel="stylesheet">
<link href="{{ asset('css/reinscription/reinscription.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header avec filtres -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-3">
                <div>
                    <h1 class="h2 mb-1" style="color: #e2e8f0;">
                        <i class="fas fa-dumbbell me-2"></i>
                        Gestion des cours
                    </h1>
                    <p class="text-muted mb-0">
                        {{ $cours->total() }} cours total{{ $cours->total() > 1 ? 's' : '' }}
                        @auth
                            @if(auth()->user()->role !== 'superadmin')
                                - {{ auth()->user()->ecole->nom ?? 'École' }}
                            @else
                                - Toutes les écoles
                            @endif
                        @endauth
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('cours.create') }}" class="btn btn-success-gradient">
                <i class="fas fa-plus me-2"></i>
                Nouveau cours
            </a>
            <a href="{{ route('cours.sessions.index') }}" class="btn btn-outline-info ms-2">
                <i class="fas fa-calendar-alt me-2"></i>
                Sessions
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);">
        <div class="card-body">
            <form method="GET" action="{{ route('cours.index') }}" class="row g-3">
                <!-- Filtre École -->
                @if(auth()->user()->role === 'superadmin' && $ecoles->count() > 0)
                <div class="col-md-3">
                    <label for="ecole_id" class="form-label" style="color: #e2e8f0; font-weight: 600; font-size: 0.9rem;">
                        École
                    </label>
                    <select class="form-control" id="ecole_id" name="ecole_id" 
                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                        <option value="all">Toutes les écoles</option>
                        @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}
                                    style="background: rgba(45, 55, 72, 1);">
                                {{ $ecole->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Filtre Session -->
                @if($sessions->count() > 0)
                <div class="col-md-3">
                    <label for="session_id" class="form-label" style="color: #e2e8f0; font-weight: 600; font-size: 0.9rem;">
                        Session
                    </label>
                    <select class="form-control" id="session_id" name="session_id"
                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                        <option value="all">Toutes les sessions</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}
                                    style="background: rgba(45, 55, 72, 1);">
                                {{ $session->nom }}
                                @if($session->inscriptions_actives)
                                    ●
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Filtre Jour -->
                <div class="col-md-2">
                    <label for="jour" class="form-label" style="color: #e2e8f0; font-weight: 600; font-size: 0.9rem;">
                        Jour
                    </label>
                    <select class="form-control" id="jour" name="jour"
                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                        <option value="all">Tous les jours</option>
                        @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                            <option value="{{ $jour }}" {{ request('jour') == $jour ? 'selected' : '' }}
                                    style="background: rgba(45, 55, 72, 1);">
                                {{ ucfirst($jour) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tri -->
                <div class="col-md-2">
                    <label for="sort" class="form-label" style="color: #e2e8f0; font-weight: 600; font-size: 0.9rem;">
                        Trier par
                    </label>
                    <select class="form-control" id="sort" name="sort"
                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                        <option value="nom" {{ request('sort') == 'nom' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Nom</option>
                        <option value="session" {{ request('sort') == 'session' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Session</option>
                        <option value="places_max" {{ request('sort') == 'places_max' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Places</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }} style="background: rgba(45, 55, 72, 1);">Date création</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('cours.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des cours -->
    @if($cours->count() > 0)
        <div class="row">
            @foreach($cours as $coursItem)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="cours-card h-100" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 15px; backdrop-filter: blur(15px); transition: all 0.3s ease;" data-statut="{{ $coursItem->statut }}">
                        <!-- Header de la carte -->
                        <div class="card-header" style="background: rgba(45, 55, 72, 0.6); border-bottom: 1px solid rgba(255, 255, 255, 0.1); border-radius: 15px 15px 0 0; padding: 20px;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1" style="color: #e2e8f0; font-weight: 600;">
                                        {{ $coursItem->nom }}
                                    </h5>
                                    @if($coursItem->session)
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge px-2 py-1" 
                                                  style="background: {{ $coursItem->session->inscriptions_actives ? 'linear-gradient(135deg, #48bb78, #38b2ac)' : 'linear-gradient(135deg, #a0aec0, #718096)' }};">
                                                {{ $coursItem->session->nom }}
                                            </span>
                                            @if($coursItem->session->inscriptions_actives)
                                                <small class="text-success ms-2">
                                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                                    Réinscriptions ouvertes
                                                </small>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Statut du cours -->
                                <div class="text-end">
                                    @if($coursItem->statut === 'actif')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Actif
                                        </span>
                                    @elseif($coursItem->statut === 'complet')
                                        <span class="badge bg-warning px-3 py-2">
                                            <i class="fas fa-users me-1"></i>Complet
                                        </span>
                                    @elseif($coursItem->statut === 'inactif')
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="fas fa-pause me-1"></i>Inactif
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i>{{ ucfirst($coursItem->statut) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body" style="padding: 20px;">
                            <!-- Informations du cours -->
                            <div class="cours-details mb-3">
                                @if($coursItem->instructeur)
                                    <div class="cours-detail mb-2">
                                        <i class="fas fa-user-tie me-2" style="color: #667eea; width: 16px;"></i>
                                        <span style="color: #a0aec0;">{{ $coursItem->instructeur }}</span>
                                    </div>
                                @endif

                                @if($coursItem->niveau)
                                    <div class="cours-detail mb-2">
                                        <i class="fas fa-layer-group me-2" style="color: #667eea; width: 16px;"></i>
                                        <span style="color: #a0aec0;">{{ ucfirst(str_replace('_', ' ', $coursItem->niveau)) }}</span>
                                    </div>
                                @endif

                                @if($coursItem->tarif)
                                    <div class="cours-detail mb-2">
                                        <i class="fas fa-dollar-sign me-2" style="color: #48bb78; width: 16px;"></i>
                                        <span style="color: #48bb78; font-weight: 600;">{{ number_format($coursItem->tarif, 0) }}$ / mois</span>
                                    </div>
                                @endif

                                <!-- Places disponibles -->
                                <div class="cours-detail mb-3">
                                    <i class="fas fa-users me-2" style="color: #ed8936; width: 16px;"></i>
                                    <span style="color: #a0aec0;">
                                        {{ $coursItem->inscriptions->where('statut', 'actif')->count() }} / {{ $coursItem->places_max }} places
                                    </span>
                                    @php
                                        $pourcentage = $coursItem->places_max > 0 ? ($coursItem->inscriptions->where('statut', 'actif')->count() / $coursItem->places_max) * 100 : 0;
                                    @endphp
                                    <div class="progress mt-1" style="height: 4px; background: rgba(255, 255, 255, 0.1);">
                                        <div class="progress-bar" 
                                             style="background: {{ $pourcentage >= 90 ? 'linear-gradient(135deg, #f56565, #e53e3e)' : ($pourcentage >= 70 ? 'linear-gradient(135deg, #ed8936, #dd6b20)' : 'linear-gradient(135deg, #48bb78, #38b2ac)') }}; width: {{ $pourcentage }}%;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Horaires -->
                            @if($coursItem->plages_horaires)
                                @php
                                    $plages = is_string($coursItem->plages_horaires) 
                                        ? json_decode($coursItem->plages_horaires, true) 
                                        : $coursItem->plages_horaires;
                                @endphp
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-clock me-2" style="color: #667eea;"></i>
                                        <small style="color: #cbd5e0; font-weight: 600;">Horaires</small>
                                    </div>
                                    <div class="horaires-container">
                                        @foreach($plages as $plage)
                                            @foreach($plage['jours'] ?? [] as $jour)
                                                <span class="cours-horaire me-1 mb-1">
                                                    {{ ucfirst($jour) }} {{ $plage['heure_debut'] }}-{{ $plage['heure_fin'] }}
                                                </span>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Description -->
                            @if($coursItem->description)
                                <div class="mb-3">
                                    <p style="color: #a0aec0; font-size: 0.9rem; line-height: 1.4; margin: 0;">
                                        {{ Str::limit($coursItem->description, 100) }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Footer avec actions -->
                        <div class="card-footer" style="background: rgba(45, 55, 72, 0.3); border-top: 1px solid rgba(255, 255, 255, 0.1); border-radius: 0 0 15px 15px; padding: 15px 20px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="{{ route('cours.show', $coursItem) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('cours.edit', $coursItem) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('cours.inscriptions', $coursItem) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-users"></i>
                                    </a>
                                </div>
                                
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="background: rgba(45, 55, 72, 0.95); border: 1px solid rgba(255, 255, 255, 0.1);">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('cours.duplicate', $coursItem) }}" style="color: #a0aec0;">
                                                <i class="fas fa-copy me-2"></i>Dupliquer
                                            </a>
                                        </li>
                                        @if($coursItem->session)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('cours.sessions.show', $coursItem->session) }}" style="color: #a0aec0;">
                                                <i class="fas fa-calendar me-2"></i>Voir la session
                                            </a>
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                                        <li>
                                            <form method="POST" action="{{ route('cours.destroy', $coursItem) }}" 
                                                  onsubmit="return confirm('Supprimer ce cours et toutes ses inscriptions ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" style="color: #f56565;">
                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $cours->appends(request()->query())->links() }}
        </div>
    @else
        <!-- État vide -->
        <div class="text-center py-5" style="color: #a0aec0;">
            <div class="card" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);">
                <div class="card-body py-5">
                    <i class="fas fa-inbox fa-4x mb-4" style="color: #4a5568;"></i>
                    <h4 style="color: #e2e8f0;">Aucun cours trouvé</h4>
                    <p class="mb-4">
                        @if(request()->hasAny(['ecole_id', 'session_id', 'jour']))
                            Aucun cours ne correspond à vos critères de recherche.
                        @else
                            Vous n'avez pas encore de cours configurés.
                        @endif
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('cours.create') }}" class="btn btn-success-gradient">
                            <i class="fas fa-plus me-2"></i>Créer le premier cours
                        </a>
                        @if(request()->hasAny(['ecole_id', 'session_id', 'jour']))
                            <a href="{{ route('cours.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Effacer les filtres
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/cours/sessions-duplication.js') }}"></script>
<script>
// Auto-submit des filtres
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('#ecole_id, #session_id, #jour, #sort');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush
