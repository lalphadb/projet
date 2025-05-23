@extends('layouts.admin')

@section('title', 'Liste des cours')

@push('styles')
<link href="{{ asset('css/cours.css') }}" rel="stylesheet">
<style>
/* Styles pour les horaires multiples */
.horaires-multiples {
    display: flex;
    flex-direction: column;  
    gap: 0.25rem;
}

.horaire-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.jour {
    color: #17a2b8;
    font-weight: 600;
    min-width: 60px;
}

.heure {
    color: #fff;
    font-weight: 500;
}

.salle {
    color: rgba(255, 255, 255, 0.7);
    font-style: italic;
}

.horaires-plus {
    margin-top: 0.25rem;
    text-align: center;
}

.cours-schedule {
    min-width: 140px;
}

/* Indicateur de cours avec horaires multiples */
.cours-multiples-badge {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-weight: 600;
    display: inline-block;
    margin-top: 0.25rem;
}
</style>
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
                    <i class="fas fa-graduation-cap"></i>
                    Gestion des cours
                    @if($cours->total() > 0)
                        <span class="cours-count">{{ $cours->total() }} cours</span>
                    @endif
                </div>
                @if(auth()->user()->role !== 'superadmin' || auth()->user()->role === 'admin')
                    <a href="{{ route('cours.create') }}" class="btn-add-cours">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter un cours
                    </a>
                @endif
            </div>
        </div>

        <!-- Section de filtres -->
        <div class="filters-section">
            <form method="GET" action="{{ route('cours.index') }}" class="filter-form">
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
                    <label class="filter-label">Filtrer par session</label>
                    <select name="session_id" class="filter-select">
                        <option value="all">Toutes les sessions</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                {{ $session->nom }} ({{ $session->mois }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Filtrer par jour</label>
                    <select name="jour" class="filter-select">
                        <option value="all">Tous les jours</option>
                        <option value="lundi" {{ request('jour') == 'lundi' ? 'selected' : '' }}>Lundi</option>
                        <option value="mardi" {{ request('jour') == 'mardi' ? 'selected' : '' }}>Mardi</option>
                        <option value="mercredi" {{ request('jour') == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                        <option value="jeudi" {{ request('jour') == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                        <option value="vendredi" {{ request('jour') == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                        <option value="samedi" {{ request('jour') == 'samedi' ? 'selected' : '' }}>Samedi</option>
                        <option value="dimanche" {{ request('jour') == 'dimanche' ? 'selected' : '' }}>Dimanche</option>
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

        <!-- Tableau des cours -->
        <div class="cours-table-container">
            @if($cours->count())
                <div class="table-responsive">
                    <table class="cours-table table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @php $dir = request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                                <th>
                                    <a href="{{ route('cours.index', array_merge(request()->except('page'), ['sort' => 'nom', 'direction' => $dir])) }}">
                                        Nom du cours
                                        {!! request('sort') === 'nom' ? ($dir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort text-muted"></i>' !!}
                                    </a>
                                </th>
                                <th>Horaires</th>
                                <th>
                                    <a href="{{ route('cours.index', array_merge(request()->except('page'), ['sort' => 'session', 'direction' => $dir])) }}">
                                        Session
                                        {!! request('sort') === 'session' ? ($dir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort text-muted"></i>' !!}
                                    </a>
                                </th>
                                <th>Places</th>
                                @if(auth()->user()->role === 'superadmin')
                                    <th>École</th>
                                @endif
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cours as $coursItem)
                                <tr data-cours-id="{{ $coursItem->id }}">
                                    <td>
                                        <span class="cours-id">#{{ $coursItem->id }}</span>
                                    </td>
                                    <td>
                                        <div class="cours-name">{{ $coursItem->nom }}</div>
                                        @if($coursItem->description)
                                            <div class="cours-description">{{ Str::limit($coursItem->description, 50) }}</div>
                                        @endif
                                        @if($coursItem->niveau)
                                            <span class="badge badge-modern niveau-{{ $coursItem->niveau }}">
                                                {{ ucfirst($coursItem->niveau) }}
                                            </span>
                                        @endif
                                        @if($coursItem->instructeur)
                                            <div class="cours-instructeur">
                                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                                {{ $coursItem->instructeur }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="cours-schedule">
                                            @if($coursItem->horairesActifs && $coursItem->horairesActifs->count() > 0)
                                                <div class="horaires-multiples">
                                                    @foreach($coursItem->horairesActifs->take(2) as $horaire)
                                                        <div class="horaire-item">
                                                            <span class="jour">{{ $horaire->getJourFormate() }}</span>
                                                            <span class="heure">{{ $horaire->getHoraireFormate() }}</span>
                                                            @if($horaire->salle)
                                                                <small class="salle">({{ $horaire->salle }})</small>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                    @if($coursItem->horairesActifs->count() > 2)
                                                        <div class="horaires-plus">
                                                            <small class="text-info">+{{ $coursItem->horairesActifs->count() - 2 }} autre(s)</small>
                                                        </div>
                                                    @endif
                                                    @if($coursItem->horairesActifs->count() > 1)
                                                        <span class="cours-multiples-badge">
                                                            {{ $coursItem->horairesActifs->count() }} créneaux
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                {{-- Fallback pour anciens cours --}}
                                                <div class="cours-days">
                                                    @if(is_array($coursItem->jours))
                                                        {{ implode(', ', array_map('ucfirst', $coursItem->jours)) }}
                                                    @else
                                                        {{ $coursItem->getJoursFormateLegacy() }}
                                                    @endif
                                                </div>
                                                @if($coursItem->heure_debut && $coursItem->heure_fin)
                                                    <div class="cours-time">
                                                        {{ $coursItem->heure_debut }} - {{ $coursItem->heure_fin }}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($coursItem->session)
                                            <span class="cours-session">{{ $coursItem->session->nom }}</span>
                                            @if($coursItem->session->mois)
                                                <div class="session-periode">{{ $coursItem->session->mois }}</div>
                                            @endif
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="cours-places">
                                            @php
                                                $inscrits = $coursItem->inscriptions->count();
                                                $disponibles = $coursItem->places_max - $inscrits;
                                                $percentage = $coursItem->places_max > 0 ? ($disponibles / $coursItem->places_max) * 100 : 0;
                                            @endphp
                                            <div class="places-info">
                                                <span class="places-disponibles 
                                                    @if($percentage <= 10) places-danger
                                                    @elseif($percentage <= 25) places-warning
                                                    @endif">
                                                    {{ $disponibles }}
                                                </span>
                                                <span class="places-total">/ {{ $coursItem->places_max }}</span>
                                            </div>
                                            <div class="places-bar">
                                                <div class="places-fill" style="width: {{ 100 - $percentage }}%"></div>
                                            </div>
                                            @if($coursItem->getDureeHebdomadaire && $coursItem->getDureeHebdomadaire() > 0)
                                                <small class="duree-info">
                                                    {{ $coursItem->getDureeHebdomadaire() }}min/semaine
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    @if(auth()->user()->role === 'superadmin')
                                        <td>
                                            @if($coursItem->ecole)
                                                <span class="cours-school">{{ $coursItem->ecole->nom }}</span>
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('cours.show', $coursItem) }}" 
                                               class="btn-action btn-action-view action-tooltip" 
                                               data-tooltip="Voir le cours">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('cours.inscriptions', $coursItem) }}" 
                                               class="btn-action btn-action-inscriptions action-tooltip" 
                                               data-tooltip="Gérer les inscriptions">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            
                                            @if(auth()->user()->role !== 'superadmin')
                                                <a href="{{ route('cours.edit', $coursItem) }}" 
                                                   class="btn-action btn-action-edit action-tooltip" 
                                                   data-tooltip="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button type="button" 
                                                        class="btn-action btn-action-duplicate action-tooltip" 
                                                        data-tooltip="Dupliquer"
                                                        onclick="dupliquerCours({{ $coursItem->id }})">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                
                                                <form action="{{ route('cours.destroy', $coursItem) }}" 
                                                      method="POST" 
                                                      class="d-inline-block"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours et tous ses horaires ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn-action btn-action-delete action-tooltip" 
                                                            data-tooltip="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($cours->hasPages())
                    <div class="pagination-wrapper">
                        {{ $cours->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- État vide -->
                <div class="empty-state">
                    <i class="fas fa-graduation-cap"></i>
                    <h4>Aucun cours trouvé</h4>
                    <p>
                        @if(request()->hasAny(['ecole_id', 'session_id', 'jour']) && (request('ecole_id') !== 'all' || request('session_id') !== 'all' || request('jour') !== 'all'))
                            Aucun cours ne correspond aux filtres sélectionnés.
                        @else
                            Il n'y a aucun cours enregistré pour le moment.
                        @endif
                    </p>
                    @if(auth()->user()->role !== 'superadmin')
                        <a href="{{ route('cours.create') }}" class="btn-add-cours">
                            <i class="fas fa-plus me-2"></i>
                            Créer le premier cours
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Styles additionnels pour l'affichage amélioré */
.cours-description {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.cours-instructeur {
    color: #ffc107;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.niveau-debutant {
    background: linear-gradient(45deg, #28a745, #20c997);
}

.niveau-intermediaire {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
}

.niveau-avance {
    background: linear-gradient(45deg, #dc3545, #e83e8c);
}

.niveau-tous_niveaux {
    background: linear-gradient(45deg, #17a2b8, #6f42c1);
}

.session-periode {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.8rem;
}

.places-info {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.places-bar {
    width: 60px;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 0.25rem;
}

.places-fill {
    height: 100%;
    background: linear-gradient(90deg, #28a745, #20c997);
    transition: width 0.3s ease;
}

.places-danger .places-fill {
    background: linear-gradient(90deg, #dc3545, #c82333);
}

.places-warning .places-fill {
    background: linear-gradient(90deg, #ffc107, #e0a800);
}

.duree-info {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.75rem;
}

.btn-action-duplicate {
    background: linear-gradient(135deg, #6f42c1, #9b59b6);
    color: white;
}

.btn-action-duplicate:hover {
    background: linear-gradient(135deg, #9b59b6, #6f42c1);
    transform: translateY(-2px);
}
</style>

<script>
function dupliquerCours(coursId) {
    window.location.href = `{{ route('cours.create') }}?duplicate=${coursId}`;
}

// Animation des tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('.action-tooltip');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            if (tooltipText) {
                // Créer et afficher le tooltip
                const tooltipEl = document.createElement('div');
                tooltipEl.className = 'custom-tooltip';
                tooltipEl.textContent = tooltipText;
                document.body.appendChild(tooltipEl);
                
                // Positionner le tooltip
                const rect = this.getBoundingClientRect();
                tooltipEl.style.position = 'fixed';
                tooltipEl.style.top = (rect.top - 35) + 'px';
                tooltipEl.style.left = (rect.left + rect.width / 2 - tooltipEl.offsetWidth / 2) + 'px';
                tooltipEl.style.background = 'rgba(0, 0, 0, 0.8)';
                tooltipEl.style.color = 'white';
                tooltipEl.style.padding = '0.5rem';
                tooltipEl.style.borderRadius = '4px';
                tooltipEl.style.fontSize = '0.8rem';
                tooltipEl.style.zIndex = '1000';
                tooltipEl.style.opacity = '0';
                tooltipEl.style.transition = 'opacity 0.3s ease';
                
                setTimeout(() => tooltipEl.style.opacity = '1', 10);
                
                this._tooltip = tooltipEl;
            }
        });
        
        tooltip.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
});
</script>
@endsection
