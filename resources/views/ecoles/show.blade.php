@extends('layouts.admin')

@section('title', $ecole->nom . ' - Détails de l\'école')

@push('styles')
<link href="{{ asset('css/ecoles.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="ecole-header mb-4">
                <div class="ecole-title">
                    <div class="title-content">
                        <i class="fas fa-school"></i>
                        Détails de l'école
                    </div>
                    <a href="{{ route('ecoles.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Détails de l'école -->
            <div class="ecole-container fade-in">
                <div class="ecole-detail-header">
                    <div class="ecole-logo">
                        <i class="fas fa-school"></i>
                    </div>
                    
                    <div class="ecole-info">
                        <div class="ecole-name">
                            {{ $ecole->nom }}
                            <span class="ecole-card-status {{ $ecole->active ? 'status-active' : 'status-inactive' }}">
                                {{ $ecole->active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-3 mt-3">
                            @if($ecole->adresse)
                            <div class="ecole-card-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $ecole->adresse }}, {{ $ecole->ville }}</span>
                            </div>
                            @endif
                            
                            @if($ecole->telephone)
                            <div class="ecole-card-info">
                                <i class="fas fa-phone-alt"></i>
                                <span>{{ $ecole->telephone }}</span>
                            </div>
                            @endif
                            
                            @if($ecole->responsable)
                            <div class="ecole-card-info">
                                <i class="fas fa-user-tie"></i>
                                <span>{{ $ecole->responsable }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-2 mt-3">
                            @if(auth()->user()->isSuperAdmin() || (auth()->user()->isAdmin() && auth()->user()->ecole_id == $ecole->id))
                            <a href="{{ route('ecoles.edit', $ecole->id) }}" class="btn-primary">
                                <i class="fas fa-edit me-2"></i>
                                Modifier
                            </a>
                            @endif
                            
                            @if(auth()->user()->isSuperAdmin())
                            <form action="{{ route('ecoles.toggle-status', $ecole->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                @if($ecole->active)
                                <button type="submit" class="btn-warning">
                                    <i class="fas fa-ban me-2"></i>
                                    Désactiver
                                </button>
                                @else
                                <button type="submit" class="btn-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Réactiver
                                </button>
                                @endif
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Statistiques -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="ecole-stat">
                            <div class="stat-value">{{ $ecole->membres_count ?? $ecole->membres()->count() }}</div>
                            <div class="stat-label">Membres</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="ecole-stat">
                            <div class="stat-value">{{ $ecole->cours_count ?? $ecole->cours()->count() }}</div>
                            <div class="stat-label">Cours</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="ecole-stat">
                            <div class="stat-value">{{ $ecole->sessions_count ?? $ecole->sessions()->count() }}</div>
                            <div class="stat-label">Sessions</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="ecole-stat">
                            <div class="stat-value">{{ $ecole->users_count ?? $ecole->users()->count() }}</div>
                            <div class="stat-label">Administrateurs</div>
                        </div>
                    </div>
                </div>
                
                <!-- Détails et Liens Rapides -->
                <div class="ecole-details">
                    <!-- Information sur l'école -->
                    <div class="detail-card">
                        <h5 class="detail-card-title">
                            <i class="fas fa-info-circle"></i>
                            Informations générales
                        </h5>
                        <ul class="detail-list">
                            <li class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>
                                    <span class="detail-item-label">Créée le:</span>
                                    {{ $ecole->created_at->format('d/m/Y') }}
                                </span>
                            </li>
                            <li class="detail-item">
                                <i class="fas fa-edit"></i>
                                <span>
                                    <span class="detail-item-label">Dernière modification:</span>
                                    {{ $ecole->updated_at->format('d/m/Y') }}
                                </span>
                            </li>
                            <li class="detail-item">
                                <i class="fas fa-map-marked-alt"></i>
                                <span>
                                    <span class="detail-item-label">Adresse complète:</span>
                                    {{ $ecole->adresse ? $ecole->adresse . ', ' : '' }}
                                    {{ $ecole->ville ? $ecole->ville . ', ' : '' }}
                                    {{ $ecole->province }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Liens rapides -->
                    <div class="detail-card">
                        <h5 class="detail-card-title">
                            <i class="fas fa-link"></i>
                            Liens rapides
                        </h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('membres.index', ['ecole_id' => $ecole->id]) }}" class="btn-secondary text-center p-2">
                                <i class="fas fa-users me-2"></i>
                                Voir les membres
                            </a>
                            <a href="{{ route('cours.index', ['ecole_id' => $ecole->id]) }}" class="btn-secondary text-center p-2">
                                <i class="fas fa-chalkboard me-2"></i>
                                Voir les cours
                            </a>
                            @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('users.index', ['ecole_id' => $ecole->id]) }}" class="btn-secondary text-center p-2">
                                <i class="fas fa-user-shield me-2"></i>
                                Administrateurs
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Liste des administrateurs/instructeurs -->
            @if($ecole->users->count() > 0)
            <div class="ecole-container mt-4 fade-in">
                <div class="form-header">
                    <h5 class="text-white">
                        <i class="fas fa-user-shield me-2"></i>
                        Administrateurs et instructeurs
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                @if(auth()->user()->isSuperAdmin())
                                <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ecole->users as $user)
                            <tr>
                                <td>{{ $user->prenom }} {{ $user->nom }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="role-badge {{ $user->role }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-flex align-items-center">
                                        <span class="status-indicator {{ $user->active ? 'status-active' : 'status-inactive' }}"></span>
                                        {{ $user->active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                @if(auth()->user()->isSuperAdmin())
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('users.show', $user->id) }}" class="btn-action btn-action-view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn-action btn-action-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            <!-- Espace supplémentaire pour éviter que le footer chevauche le contenu -->
            <div class="mb-large"></div>
        </div>
    </div>
</div>
@endsection
