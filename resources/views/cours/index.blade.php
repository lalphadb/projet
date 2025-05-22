@extends('layouts.admin')

@section('title', 'Liste des cours')

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
                                    </td>
                                    <td>
                                        <div class="cours-schedule">
                                            <div class="cours-days">
                                                @if(is_array($coursItem->jours))
                                                    {{ implode(', ', array_map('ucfirst', $coursItem->jours)) }}
                                                @else
                                                    {{ $coursItem->jours }}
                                                @endif
                                            </div>
                                            <div class="cours-time">
                                                {{ $coursItem->heure_debut }} - {{ $coursItem->heure_fin }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($coursItem->session)
                                            <span class="cours-session">{{ $coursItem->session->nom }}</span>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="cours-places">
                                            @php
                                                $inscrits = $coursItem->inscriptions->count();
                                                $disponibles = $coursItem->places_max - $inscrits;
                                                $percentage = ($disponibles / $coursItem->places_max) * 100;
                                            @endphp
                                            <span class="places-disponibles 
                                                @if($percentage <= 10) places-danger
                                                @elseif($percentage <= 25) places-warning
                                                @endif">
                                                {{ $disponibles }}
                                            </span>
                                            <span class="places-total">/ {{ $coursItem->places_max }}</span>
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
                                                
                                                <form action="{{ route('cours.destroy', $coursItem) }}" 
                                                      method="POST" 
                                                      class="d-inline-block">
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
@endsection
