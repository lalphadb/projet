@extends('layouts.admin')

@section('title', 'Gestion des Présences')

@section('content')
<div class="container-fluid">
    <div class="members-list-container">
        <!-- En-tête moderne -->
        <div class="members-header">
            <div class="members-title">
                <div class="title-content">
                    <i class="fas fa-calendar-check"></i>
                    Gestion des Présences
                    @if($presences->total() > 0)
                        <span class="members-count">{{ $presences->total() }} présence(s)</span>
                    @endif
                </div>
                <a href="{{ route('presences.create') }}" class="btn-add-member">
                    <i class="fas fa-plus me-2"></i>
                    Nouvelle Présence
                </a>
            </div>
        </div>

        <!-- Section de filtres -->
        <div class="filters-section">
            <form method="GET" action="{{ route('presences.index') }}" class="filter-form">
                <div class="filter-group">
                    <label class="filter-label">Cours</label>
                    <select name="cours_id" class="filter-select">
                        <option value="">Tous les cours</option>
                        @foreach($cours as $c)
                            <option value="{{ $c->id }}" 
                                {{ request('cours_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Statut</label>
                    <select name="status" class="filter-select">
                        <option value="">Tous</option>
                        @foreach($statuts as $key => $label)
                            <option value="{{ $key }}" 
                                {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Date</label>
                    <input type="date" name="date_presence" 
                           class="filter-select" 
                           value="{{ request('date_presence') }}">
                </div>
                
                <div>
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter me-1"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau des présences -->
        <div class="members-table-container">
            @if($presences->count())
                <div class="table-responsive">
                    <table class="members-table table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Membre</th>
                                <th>Cours</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presences as $presence)
                                <tr>
                                    <td>
                                        <div class="member-name">
                                            {{ $presence->date_presence->format('d/m/Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $presence->date_presence->isoFormat('dddd') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="member-name">
                                            {{ $presence->membre->nom }} {{ $presence->membre->prenom }}
                                        </div>
                                        @if($presence->membre->email)
                                            <small class="member-email">
                                                {{ $presence->membre->email }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="member-school">{{ $presence->cours->nom }}</span>
                                        @if($presence->cours->ecole)
                                            <small class="text-muted">
                                                {{ $presence->cours->ecole->nom }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-indicator 
                                            {{ $presence->status === 'present' ? 'status-active' : 
                                               ($presence->status === 'absent' ? 'status-inactive' : 'status-warning') }}">
                                        </span>
                                        {{ $presence->status_label }}
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('presences.show', $presence) }}" 
                                               class="btn-action btn-action-view action-tooltip" 
                                               data-tooltip="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('presences.edit', $presence) }}" 
                                               class="btn-action btn-action-edit action-tooltip" 
                                               data-tooltip="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('presences.destroy', $presence) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Confirmer la suppression ?')">
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
                
                @if($presences->hasPages())
                    <div class="pagination-wrapper">
                        {{ $presences->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Aucune présence enregistrée</h4>
                    <p>
                        @if(request('status') || request('cours_id'))
                            Aucune présence ne correspond à vos filtres.
                        @else
                            Commencez par enregistrer des présences.
                        @endif
                    </p>
                    <a href="{{ route('presences.create') }}" class="btn-add-member">
                        <i class="fas fa-plus me-2"></i>
                        Nouvelle présence
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
