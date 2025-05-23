@extends('layouts.admin')

@section('title', 'Gestion des sessions')

@push('styles')
<link href="{{ asset('css/cours/sessions-duplication.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('cours.index') }}" class="btn btn-secondary-glass me-3">
                    <i class="fas fa-arrow-left"></i> Retour aux cours
                </a>
                <div>
                    <h1 class="h2 mb-1" style="color: #e2e8f0;">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Gestion des sessions
                    </h1>
                    <p class="text-muted mb-0">
                        {{ $sessions->total() }} session{{ $sessions->total() > 1 ? 's' : '' }} total{{ $sessions->total() > 1 ? 'es' : 'e' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('cours.sessions.create') }}" class="btn btn-success-gradient">
                <i class="fas fa-plus me-2"></i>
                Nouvelle session
            </a>
        </div>
    </div>

    <!-- Filtres -->
    @if(auth()->user()->role === 'superadmin' && $ecoles->count() > 0)
    <div class="card mb-4" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);">
        <div class="card-body">
            <form method="GET" action="{{ route('cours.sessions.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="ecole_id" class="form-label" style="color: #e2e8f0; font-weight: 600;">École</label>
                    <select class="form-control" id="ecole_id" name="ecole_id" 
                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                        <option value="all">Toutes les écoles</option>
                        @foreach($ecoles as $ecole)
                            <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}
                                    style="background: rgba(45, 55, 72, 1);">{{ $ecole->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="annee" class="form-label" style="color: #e2e8f0; font-weight: 600;">Année</label>
                    <select class="form-control" id="annee" name="annee"
                            style="background: rgba(45, 55, 72, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); color: #e2e8f0;">
                        <option value="all">Toutes</option>
                        @for($year = date('Y'); $year >= date('Y')-2; $year--)
                            <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}
                                    style="background: rgba(45, 55, 72, 1);">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Liste des sessions -->
    @if($sessions->count() > 0)
        <div class="row">
            @foreach($sessions as $session)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="cours-card h-100" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 15px; backdrop-filter: blur(15px);">
                        <!-- Header -->
                        <div class="card-header" style="background: rgba(45, 55, 72, 0.6); border-radius: 15px 15px 0 0; padding: 20px;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 style="color: #e2e8f0; font-weight: 600; margin-bottom: 8px;">
                                        {{ $session->nom }}
                                    </h5>
                                    @if($session->mois)
                                        <small style="color: #a0aec0;">{{ $session->mois }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    @if($session->inscriptions_actives)
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Ouvertes
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="fas fa-pause me-1"></i>Fermées
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Corps -->
                        <div class="card-body" style="padding: 20px;">
                            <!-- Dates -->
                            <div class="mb-3">
                                <div class="cours-detail mb-2">
                                    <i class="fas fa-calendar-day me-2" style="color: #667eea; width: 16px;"></i>
                                    <span style="color: #a0aec0;">
                                        {{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                @if($session->date_limite_inscription)
                                <div class="cours-detail mb-2">
                                    <i class="fas fa-clock me-2" style="color: #ed8936; width: 16px;"></i>
                                    <span style="color: #a0aec0;">
                                        Limite: {{ \Carbon\Carbon::parse($session->date_limite_inscription)->format('d/m/Y') }}
                                    </span>
                                </div>
                                @endif

                                @if($session->ecole && auth()->user()->role === 'superadmin')
                                <div class="cours-detail mb-2">
                                    <i class="fas fa-school me-2" style="color: #48bb78; width: 16px;"></i>
                                    <span style="color: #a0aec0;">{{ $session->ecole->nom }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Stats -->
                            <div class="session-stats">
                                <div class="stat-card">
                                    <span class="stat-number">{{ $session->cours_count ?? 0 }}</span>
                                    <div class="stat-label">Cours</div>
                                </div>
                                <div class="stat-card">
                                    <span class="stat-number">
                                        {{ $session->cours->sum(function($cours) { return $cours->inscriptions->count(); }) }}
                                    </span>
                                    <div class="stat-label">Inscrits</div>
                                </div>
                            </div>

                            @if($session->description)
                            <div class="mt-3">
                                <p style="color: #a0aec0; font-size: 0.9rem; margin: 0;">
                                    {{ Str::limit($session->description, 80) }}
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="card-footer" style="background: rgba(45, 55, 72, 0.3); border-radius: 0 0 15px 15px; padding: 15px 20px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="{{ route('cours.sessions.show', $session) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('cours.sessions.edit', $session) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                                
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="background: rgba(45, 55, 72, 0.95);">
                                        <li>
                                            <button type="button" class="dropdown-item btn-dupliquer" 
                                                    data-bs-toggle="modal" data-bs-target="#modalDuplication"
                                                    data-session-id="{{ $session->id }}" style="color: #a0aec0;">
                                                <i class="fas fa-copy me-2"></i>Dupliquer
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                                        <li>
                                            <form method="POST" action="{{ route('cours.sessions.destroy', $session) }}" 
                                                  onsubmit="return confirm('Supprimer cette session ?')">
                                                @csrf @method('DELETE')
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
            {{ $sessions->appends(request()->query())->links() }}
        </div>
    @else
        <!-- État vide -->
        <div class="text-center py-5">
            <div class="card" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="card-body py-5">
                    <i class="fas fa-calendar-times fa-4x mb-4" style="color: #4a5568;"></i>
                    <h4 style="color: #e2e8f0;">Aucune session trouvée</h4>
                    <p class="mb-4" style="color: #a0aec0;">Créez votre première session pour organiser vos cours.</p>
                    <a href="{{ route('cours.sessions.create') }}" class="btn btn-success-gradient">
                        <i class="fas fa-plus me-2"></i>Créer la première session
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Duplication (réutilisé du show.blade.php) -->
<div class="modal fade modal-duplication" id="modalDuplication" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-copy me-2"></i>Dupliquer la session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form method="POST" id="formDuplication">
                @csrf
                <div class="form-duplication">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouveau_nom">Nom de la nouvelle session *</label>
                                <input type="text" class="form-control" id="nouveau_nom" name="nouveau_nom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouveau_mois">Période</label>
                                <input type="text" class="form-control" id="nouveau_mois" name="nouveau_mois">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouvelle_date_debut">Date de début *</label>
                                <input type="date" class="form-control" id="nouvelle_date_debut" name="nouvelle_date_debut" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouvelle_date_fin">Date de fin *</label>
                                <input type="date" class="form-control" id="nouvelle_date_fin" name="nouvelle_date_fin" required>
                            </div>
                        </div>
                    </div>
                    <div class="options-duplication">
                        <h6>Options</h6>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="copier_cours" name="copier_cours" value="1" checked>
                            <label class="custom-control-label" for="copier_cours">Copier tous les cours</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="activer_reinscriptions" name="activer_reinscriptions" value="1">
                            <label class="custom-control-label" for="activer_reinscriptions">Activer les réinscriptions</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-glass" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success-gradient">
                        <i class="fas fa-copy me-2"></i>Dupliquer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/cours/sessions-duplication.js') }}"></script>
@endpush
