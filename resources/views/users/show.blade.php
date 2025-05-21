@extends('layouts.admin')

@section('title', 'Détails de l\'administrateur')

@push('styles')
<style>
    .user-profile-container {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        gap: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 2rem;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #17a2b8, #20c997);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 2.5rem;
    }
    
    .profile-info {
        flex: 1;
    }
    
    .profile-name {
        font-size: 1.8rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    
    .profile-role {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 500;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }
    
    .profile-role.admin {
        background: linear-gradient(135deg, #17a2b8, #20c997);
        color: white;
    }
    
    .profile-role.instructor {
        background: linear-gradient(135deg, #fd7e14, #ffc107);
        color: white;
    }
    
    .profile-role.superadmin {
        background: linear-gradient(135deg, #6f42c1, #e83e8c);
        color: white;
    }
    
    .profile-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        color: #17a2b8;
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 0.75rem;
    }
    
    .section-title i {
        margin-right: 0.5rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .info-item {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 10px;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .info-item:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-3px);
    }
    
    .info-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        color: #fff;
        font-weight: 500;
    }
    
    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }
    
    .activity-icon.login {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .activity-icon.action {
        background: linear-gradient(135deg, #17a2b8, #20c997);
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        color: #fff;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .activity-time {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
    }
    
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .status-active {
        background-color: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .status-inactive {
        background-color: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="cours-header mb-4">
                <div class="cours-title">
                    <div class="title-content">
                        <i class="fas fa-user-shield"></i>
                        Détails de l'administrateur
                    </div>
                    <div class="d-flex gap-2">
                        @if(Auth::user()->role === 'superadmin' || Auth::user()->id === $user->id)
                            <a href="{{ route('users.edit', $user) }}" class="btn-secondary">
                                <i class="fas fa-user-edit me-2"></i>
                                Modifier
                            </a>
                        @endif
                        <a href="{{ route('users.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profil utilisateur -->
            <div class="user-profile-container">
                <div class="profile-header">
                    <div class="profile-avatar">
                        {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name">{{ $user->prenom }} {{ $user->nom }}</h2>
                        <span class="profile-role {{ $user->role }}">
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
                        <div class="d-flex align-items-center gap-3">
                            <span class="status-indicator {{ $user->active ? 'status-active' : 'status-inactive' }}">
                                <i class="fas fa-{{ $user->active ? 'check-circle' : 'times-circle' }}"></i>
                                {{ $user->active ? 'Actif' : 'Inactif' }}
                            </span>
                            
                            @if($user->ecole)
                                <span class="text-muted">
                                    <i class="fas fa-school me-2"></i>
                                    {{ $user->ecole->nom }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Informations personnelles -->
                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Informations personnelles
                    </h3>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope me-2"></i>
                                Adresse email
                            </div>
                            <div class="info-value">
                                <a href="mailto:{{ $user->email }}" class="text-info">
                                    {{ $user->email }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-phone me-2"></i>
                                Téléphone
                            </div>
                            <div class="info-value">
                                @if($user->telephone)
                                    <a href="tel:{{ $user->telephone }}" class="text-info">
                                        {{ $user->telephone }}
                                    </a>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-at me-2"></i>
                                Nom d'utilisateur
                            </div>
                            <div class="info-value">
                                {{ $user->username }}
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-shield-alt me-2"></i>
                                Permissions
                            </div>
                            <div class="info-value">
                                @if($user->role == 'superadmin')
                                    Accès complet à toutes les fonctionnalités
                                @elseif($user->role == 'admin')
                                    Gestion des membres, cours et présences
                                @elseif($user->role == 'instructor')
                                    Gestion des cours et présences uniquement
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activité -->
                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fas fa-history"></i>
                        Historique d'activité
                    </h3>
                    
                    <ul class="activity-list">
                        @if($user->last_login_at)
                            <li class="activity-item">
                                <div class="activity-icon login">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Dernière connexion</div>
                                    <div class="activity-time">{{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y à H:i') }}</div>
                                </div>
                            </li>
                        @endif
                        
                        <li class="activity-item">
                            <div class="activity-icon action">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Compte créé</div>
                                <div class="activity-time">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </li>
                        
                        @if($user->updated_at && $user->updated_at->ne($user->created_at))
                            <li class="activity-item">
                                <div class="activity-icon action">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Dernière modification du profil</div>
                                    <div class="activity-time">{{ $user->updated_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
                
                <!-- Actions -->
                <div class="d-flex flex-wrap gap-3 mt-4">
                    @if(Auth::user()->id === $user->id)
                        <button type="button" class="btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-2"></i>
                            Changer mon mot de passe
                        </button>
                    @endif
                    
                    @if(Auth::user()->role === 'superadmin' && Auth::user()->id !== $user->id && $user->role !== 'superadmin')
                        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn-{{ $user->active ? 'warning' : 'success' }}" onclick="return confirm('Êtes-vous sûr de vouloir {{ $user->active ? 'désactiver' : 'activer' }} ce compte ?')">
                                <i class="fas fa-{{ $user->active ? 'user-slash' : 'user-check' }} me-2"></i>
                                {{ $user->active ? 'Désactiver le compte' : 'Activer le compte' }}
                            </button>
                        </form>
                        
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ? Cette action est irréversible.')">
                                <i class="fas fa-trash me-2"></i>
                                Supprimer le compte
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal changement de mot de passe -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-key me-2"></i>
                    Changer mon mot de passe
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.reset-password', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Nouveau mot de passe *</label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <span class="password-toggle" onclick="togglePassword('password')">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label text-white">Confirmer le mot de passe *</label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                            <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer le nouveau mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
