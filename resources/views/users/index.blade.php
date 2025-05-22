@extends('layouts.admin')

@section('title', 'Créer un administrateur d\'école')

@push('styles')
<link href="{{ asset('css/users.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="cours-header mb-4">
                <div class="cours-title">
                    <div class="title-content">
                        <i class="fas fa-users-cog"></i>
                        Gestion des administrateurs
                        @if($users->total() > 0)
                            <span class="cours-count">{{ $users->total() }} utilisateurs</span>
                        @endif
                    </div>
                    <a href="{{ route('users.create') }}" class="btn-add-cours">
                        <i class="fas fa-user-plus me-2"></i>
                        Ajouter un administrateur
                    </a>
                </div>
            </div>

            <!-- Filtres -->
            <div class="admin-table-container">
                <form method="GET" action="{{ route('users.index') }}">
                    <div class="admin-filter-bar">
                        <div class="filter-group">
                            <label for="role_filter" class="filter-label">Rôle:</label>
                            <select name="role" id="role_filter" class="filter-select">
                                <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Tous les rôles</option>
                                <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>Instructeur</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="ecole_filter" class="filter-label">École:</label>
                            <select name="ecole_id" id="ecole_filter" class="filter-select">
                                <option value="all" {{ request('ecole_id') == 'all' ? 'selected' : '' }}>Toutes les écoles</option>
                                @foreach($ecoles as $ecole)
                                    <option value="{{ $ecole->id }}" {{ request('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                        {{ $ecole->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="status_filter" class="filter-label">Statut:</label>
                            <select name="status" id="status_filter" class="filter-select">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                        
                        <div class="filter-group ms-auto">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Rechercher..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn-filter">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table des administrateurs -->
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="admin-table table">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>École</th>
                                    <th>Dernière connexion</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="admin-avatar me-3">
                                                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="admin-name">{{ $user->prenom }} {{ $user->nom }}</div>
                                                    <small class="text-muted">@{{ $user->username }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="role-badge {{ $user->role }}">
                                                @if($user->role == 'superadmin')
                                                    Super Admin
                                                @elseif($user->role == 'admin')
                                                    Administrateur
                                                @elseif($user->role == 'instructor')
                                                    Instructeur
                                                @else
                                                    {{ ucfirst($user->role) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->ecole)
                                                {{ $user->ecole->nom }}
                                            @elseif($user->role == 'superadmin')
                                                <span class="text-muted">Toutes les écoles</span>
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->last_login_at)
                                                {{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">Jamais connecté</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="status-indicator {{ $user->active ? 'status-active' : 'status-inactive' }}"></span>
                                                {{ $user->active ? 'Actif' : 'Inactif' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('users.show', $user->id) }}" 
                                                   class="btn-action btn-action-view" 
                                                   title="Voir le profil">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if(auth()->user()->id != $user->id && (auth()->user()->role == 'superadmin' || (auth()->user()->role == 'admin' && $user->role != 'superadmin')))
                                                    <a href="{{ route('users.edit', $user->id) }}" 
                                                       class="btn-action btn-action-edit" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('users.destroy', $user->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn-action btn-action-delete" 
                                                                title="Supprimer">
                                                            <i class="fas fa-trash"></i>
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
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- État vide -->
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <h4>Aucun administrateur trouvé</h4>
                        <p>
                            @if(request()->hasAny(['role', 'ecole_id', 'status', 'search']))
                                Aucun utilisateur ne correspond aux filtres sélectionnés.
                            @else
                                Il n'y a aucun utilisateur enregistré pour le moment.
                            @endif
                        </p>
                        <a href="{{ route('users.create') }}" class="btn-primary">
                            <i class="fas fa-user-plus me-2"></i>
                            Ajouter un administrateur
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{ asset('js/users.js') }}"></script>
@endpush
@endsection
