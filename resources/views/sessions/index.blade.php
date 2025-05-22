@extends('layouts.admin')

@section('title', 'Gestion des sessions')

@push('styles')
<link href="{{ asset('css/cours.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/cours.js') }}"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="cours-list-container">
        <!-- En-tête moderne -->
        <div class="cours-header">
            <div class="cours-title">
                <div class="title-content">
                    <i class="fas fa-calendar-alt"></i>
                    Gestion des sessions
                    @if($sessions->total() > 0)
                        <span class="cours-count">{{ $sessions->total() }} session(s)</span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-secondary" onclick="generateSessionsForYear()">
                        <i class="fas fa-magic me-2"></i>
                        Générer sessions automatiquement
                    </button>
                    <a href="{{ route('cours.sessions.create') }}" class="btn-add-cours">
                        <i class="fas fa-plus me-2"></i>
                        Créer une session
                    </a>
                </div>
            </div>
        </div>

        <!-- Section de filtres -->
        <div class="filters-section">
            <form method="GET" action="{{ route('cours.sessions.index') }}" class="filter-form">
                @if(auth()->user()->role === 'superadmin')
                    <div class="filter-group">
                        <label class="filter-label">Filtrer par école</label>
                        <select name="ecole_id" class="filter-select">
                            <option value="all">Toutes les écoles</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                    {{ $ecole->nom }}
                                    @if($ecole->ville)
                                        ({{ $ecole->ville }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                <div class="filter-group">
                    <label class="filter-label">Filtrer par année</label>
                    <select name="annee" class="filter-select">
                        <option value="all">Toutes les années</option>
                        @for($year = date('Y') - 1; $year <= date('Y') + 2; $year++)
                            <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Filtrer par saison</label>
                    <select name="saison" class="filter-select">
                        <option value="all">Toutes les saisons</option>
                        <option value="hiver" {{ request('saison') == 'hiver' ? 'selected' : '' }}>Hiver</option>
                        <option value="printemps" {{ request('saison') == 'printemps' ? 'selected' : '' }}>Printemps</option>
                        <option value="ete" {{ request('saison') == 'ete' ? 'selected' : '' }}>Été</option>
                        <option value="automne" {{ request('saison') == 'automne' ? 'selected' : '' }}>Automne</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter me-1"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau des sessions -->
        <div class="cours-table-container">
            @if($sessions->count())
                <div class="table-responsive">
                    <table class="cours-table table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @php $dir = request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                                <th>
                                    <a href="{{ route('cours.sessions.index', array_merge(request()->except('page'), ['sort' => 'nom', 'direction' => $dir])) }}">
                                        Nom de la session
                                        {!! request('sort') === 'nom' ? ($dir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort text-muted"></i>' !!}
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('cours.sessions.index', array_merge(request()->except('page'), ['sort' => 'date_debut', 'direction' => $dir])) }}">
                                        Période
                                        {!! request('sort') === 'date_debut' ? ($dir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort text-muted"></i>' !!}
                                    </a>
                                </th>
                                <th>Cours</th>
                                <th>Statut</th>
                                @if(auth()->user()->role === 'superadmin')
                                    <th>École</th>
                                @endif
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                                <tr>
                                    <td>
                                        <span class="cours-id">#{{ $session->id }}</span>
                                    </td>
                                    <td>
                                        <div class="session-name">{{ $session->nom }}</div>
                                        @if($session->mois)
                                            <div class="session-mois">{{ $session->mois }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="session-period">
                                            <div class="period-dates">
                                                <i class="fas fa-calendar-day me-1"></i>
                                                {{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }}
                                                <i class="fas fa-arrow-right mx-2"></i>
                                                {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }}
                                            </div>
                                            <div class="period-duration">
                                                @php
                                                    $debut = \Carbon\Carbon::parse($session->date_debut);
                                                    $fin = \Carbon\Carbon::parse($session->date_fin);
                                                    $duree = $debut->diffInDays($fin);
                                                @endphp
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $duree }} jours
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="session-stats">
                                            <div class="stat-item">
                                                <span class="stat-number">{{ $session->cours->count() }}</span>
                                                <span class="stat-text">cours</span>
                                            </div>
                                            @if($session->cours->count() > 0)
                                                <div class="stat-item">
                                                    <span class="stat-number">{{ $session->cours->sum(function($cours) { return $cours->inscriptions->count(); }) }}</span>
                                                    <span class="stat-text">inscrits</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $debut = \Carbon\Carbon::parse($session->date_debut);
                                            $fin = \Carbon\Carbon::parse($session->date_fin);
                                        @endphp
                                        @if($now->lt($debut))
                                            <span class="status-indicator status-upcoming">
                                                <i class="fas fa-hourglass-start"></i>
                                                À venir
                                            </span>
                                        @elseif($now->between($debut, $fin))
                                            <span class="status-indicator status-active">
                                                <i class="fas fa-play-circle"></i>
                                                En cours
                                            </span>
                                        @else
                                            <span class="status-indicator status-completed">
                                                <i class="fas fa-check-circle"></i>
                                                Terminée
                                            </span>
                                        @endif
                                    </td>
                                    @if(auth()->user()->role === 'superadmin')
                                        <td>
                                            @if($session->ecole)
                                                <span class="cours-school">{{ $session->ecole->nom }}</span>
                                            @else
                                                <span class="text-muted">Non assignée</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('cours.sessions.show', $session) }}" 
                                               class="btn-action btn-action-view action-tooltip" 
                                               data-tooltip="Voir la session">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('cours.index', ['session_id' => $session->id]) }}" 
                                               class="btn-action btn-action-inscriptions action-tooltip" 
                                               data-tooltip="Voir les cours">
                                                <i class="fas fa-graduation-cap"></i>
                                            </a>
                                            
                                            @if(auth()->user()->role !== 'superadmin')
                                                <a href="{{ route('cours.sessions.edit', $session) }}" 
                                                   class="btn-action btn-action-edit action-tooltip" 
                                                   data-tooltip="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if($session->cours->count() == 0)
                                                    <form action="{{ route('cours.sessions.destroy', $session) }}" 
                                                          method="POST" 
                                                          class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn-action btn-action-delete action-tooltip" 
                                                                data-tooltip="Supprimer"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette session ?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn-action btn-action-delete action-tooltip" 
                                                            data-tooltip="Impossible de supprimer (contient des cours)"
                                                            disabled>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($sessions->hasPages())
                    <div class="pagination-wrapper">
                        {{ $sessions->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- État vide -->
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Aucune session trouvée</h4>
                    <p>
                        @if(request()->hasAny(['ecole_id', 'annee', 'saison']) && (request('ecole_id') !== 'all' || request('annee') !== 'all' || request('saison') !== 'all'))
                            Aucune session ne correspond aux filtres sélectionnés.
                        @else
                            Il n'y a aucune session créée pour le moment.
                        @endif
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn-secondary" onclick="generateSessionsForYear()">
                            <i class="fas fa-magic me-2"></i>
                            Générer les 4 sessions de l'année
                        </button>
                        <a href="{{ route('cours.sessions.create') }}" class="btn-add-cours">
                            <i class="fas fa-plus me-2"></i>
                            Créer manuellement
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modale de génération automatique -->
<div class="modal fade" id="generateSessionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-magic me-2"></i>
                    Générer les sessions automatiquement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cours.sessions.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-white-50 mb-3">
                        Cette action créera automatiquement les 4 sessions standard (Hiver, Printemps, Été, Automne) pour l'année sélectionnée.
                    </p>
                    
                    <div class="mb-3">
                        <label for="year" class="form-label text-white">Année</label>
                        <select class="form-select" id="year" name="year" required>
                            @for($year = date('Y'); $year <= date('Y') + 2; $year++)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Sessions à créer</label>
                        <div class="sessions-preview">
                            <div class="session-preview-item">
                                <input type="checkbox" name="sessions[]" value="hiver" id="hiver" checked>
                                <label for="hiver" class="text-white">
                                    <strong>Hiver</strong> - Janvier à Mars
                                </label>
                            </div>
                            <div class="session-preview-item">
                                <input type="checkbox" name="sessions[]" value="printemps" id="printemps" checked>
                                <label for="printemps" class="text-white">
                                    <strong>Printemps</strong> - Avril à Juin
                                </label>
                            </div>
                            <div class="session-preview-item">
                                <input type="checkbox" name="sessions[]" value="ete" id="ete" checked>
                                <label for="ete" class="text-white">
                                    <strong>Été</strong> - Juillet à Septembre
                                </label>
                            </div>
                            <div class="session-preview-item">
                                <input type="checkbox" name="sessions[]" value="automne" id="automne" checked>
                                <label for="automne" class="text-white">
                                    <strong>Automne</strong> - Octobre à Décembre
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note :</strong> Les sessions existantes pour cette année ne seront pas remplacées.
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-magic me-2"></i>
                        Générer les sessions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques pour les sessions */
.session-name {
    font-weight: 600;
    color: #fff;
    font-size: 1rem;
}

.session-mois {
    color: #ffc107;
    font-size: 0.85rem;
    font-weight: 500;
}

.session-period {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.period-dates {
    color: #17a2b8;
    font-weight: 500;
    font-size: 0.9rem;
}

.period-duration {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.8rem;
}

.session-stats {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.stat-number {
    font-weight: 700;
    color: #28a745;
    font-size: 1.1rem;
}

.stat-text {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
}

/* Statuts des sessions */
.status-upcoming {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-active {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.status-completed {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.3);
}

/* Preview des sessions à générer */
.sessions-preview {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.session-preview-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 6px;
    transition: all 0.3s ease;
}

.session-preview-item:hover {
    background: rgba(255, 255, 255, 0.08);
}

.session-preview-item input[type="checkbox"] {
    accent-color: #17a2b8;
    transform: scale(1.2);
}

.session-preview-item label {
    cursor: pointer;
    margin: 0;
    flex: 1;
}

/* Bouton désactivé */
.btn-action:disabled {
    opacity: 0.3;
    cursor: not-allowed;
    transform: none !important;
}

.btn-action:disabled:hover {
    transform: none !important;
    box-shadow: none !important;
}

/* Alert adapté au thème dark */
.alert-warning {
    background: rgba(255, 193, 7, 0.1);
    border: 1px solid rgba(255, 193, 7, 0.3);
    color: #fff;
    border-radius: 8px;
}

/* Styles pour les modales Bootstrap */
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
</style>

<script>
function generateSessionsForYear() {
    const modal = new bootstrap.Modal(document.getElementById('generateSessionsModal'));
    modal.show();
}

// Mise à jour de l'aperçu en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('year');
    if (yearSelect) {
        yearSelect.addEventListener('change', function() {
            // Optionnel : mettre à jour les dates d'aperçu
            console.log('Année sélectionnée:', this.value);
        });
    }
});
</script>
@endsection
