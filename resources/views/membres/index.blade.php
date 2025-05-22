@extends('layouts.admin')

@section('title', 'Liste des membres')

@section('content')
<div class="container-fluid">
    <div class="members-list-container">
        <!-- En-tête moderne -->
        <div class="members-header">
            <div class="members-title">
                <div class="title-content">
                    <i class="fas fa-users"></i>
                    Gestion des membres
                    @if($membres->total() > 0)
                        <span class="members-count">{{ $membres->total() }} membre(s)</span>
                    @endif
                </div>
                <a href="{{ route('membres.create') }}" class="btn-add-member">
                    <i class="fas fa-user-plus me-2"></i>
                    Ajouter un membre
                </a>
            </div>
        </div>

        <!-- Section de filtres (pour superadmin uniquement) -->
        @if(auth()->user()->role === 'superadmin')
            <div class="filters-section">
                <form method="GET" action="{{ route('membres.index') }}" class="filter-form">
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
                    
                    <div>
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter me-1"></i>
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Tableau des membres -->
        <div class="members-table-container">
            @if($membres->count())
                <div class="table-responsive">
                    <table class="members-table table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @php $dir = request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                                <th>
                                    <a href="{{ route('membres.index', array_merge(request()->except('page'), ['sort' => 'nom', 'direction' => $dir])) }}">
                                        Nom complet
                                        {!! request('sort') === 'nom' ? ($dir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort text-muted"></i>' !!}
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('membres.index', array_merge(request()->except('page'), ['sort' => 'email', 'direction' => $dir])) }}">
                                        Adresse courriel
                                        {!! request('sort') === 'email' ? ($dir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort text-muted"></i>' !!}
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('membres.index', array_merge(request()->except('page'), ['sort' => 'telephone', 'direction' => $dir])) }}">
                                        Téléphone
                                        {!! request('sort') === 'telephone' ? ($dir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort text-muted"></i>' !!}
                                    </a>
                                </th>
                                @if(auth()->user()->role === 'superadmin')
                                    <th>
                                        <a href="{{ route('membres.index', array_merge(request()->except('page'), ['sort' => 'ecole', 'direction' => $dir])) }}">
                                            École
                                            {!! request('sort') === 'ecole' ? ($dir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort text-muted"></i>' !!}
                                        </a>
                                    </th>
                                @endif
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($membres as $membre)
                                <tr>
                                    <td>
                                        <span class="member-id">#{{ $membre->id }}</span>
                                    </td>
                                    <td>
                                        <div class="member-name">
                                            {{ $membre->prenom }} {{ $membre->nom }}
                                        </div>
                                        @if($membre->date_naissance)
                                            <small class="text-muted">
                                                Né(e) le {{ \Carbon\Carbon::parse($membre->date_naissance)->format('d/m/Y') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($membre->email)
                                            <span class="member-email">{{ $membre->email }}</span>
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($membre->telephone)
                                            <span class="member-phone">{{ $membre->telephone }}</span>
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </td>
                                    @if(auth()->user()->role === 'superadmin')
                                        <td>
                                            @if($membre->ecole)
                                                <span class="member-school">{{ $membre->ecole->nom }}</span>
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('membres.show', $membre) }}" 
                                               class="btn-action btn-action-view action-tooltip" 
                                               data-tooltip="Voir la fiche">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('membres.edit', $membre) }}" 
                                               class="btn-action btn-action-edit action-tooltip" 
                                               data-tooltip="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('membres.destroy', $membre) }}" 
                                                  method="POST" 
                                                  class="d-inline-block" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $membre->prenom }} {{ $membre->nom }} ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn-action btn-action-delete action-tooltip" 
                                                        data-tooltip="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($membres->hasPages())
                    <div class="pagination-wrapper">
                        {{ $membres->links() }}
                    </div>
                @endif
            @else
                <!-- État vide -->
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h4>Aucun membre trouvé</h4>
                    <p>
                        @if(request('ecole_id') && request('ecole_id') !== 'all')
                            Aucun membre n'est enregistré pour cette école.
                        @else
                            Il n'y a aucun membre enregistré pour le moment.
                        @endif
                    </p>
                    <a href="{{ route('membres.create') }}" class="btn-add-member">
                        <i class="fas fa-user-plus me-2"></i>
                        Ajouter le premier membre
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* === STYLES ADDITIONNELS POUR CETTE PAGE === */
.text-muted {
    color: rgba(255, 255, 255, 0.5) !important;
}

/* Animation au survol des rangées */
.members-table tbody tr {
    transition: all 0.3s ease;
}

.members-table tbody tr:hover {
    background: linear-gradient(90deg, 
        rgba(23, 162, 184, 0.08) 0%, 
        rgba(23, 162, 184, 0.03) 100%);
    box-shadow: inset 3px 0 0 #17a2b8;
}

/* Amélioration des icônes de tri */
.fa-sort, .fa-sort-up, .fa-sort-down {
    font-size: 0.8rem;
    margin-left: 0.5rem;
}

/* Responsive pour les actions */
@media (max-width: 576px) {
    .action-buttons {
        justify-content: flex-start;
        flex-wrap: wrap;
    }
}
</style>
@endsection
