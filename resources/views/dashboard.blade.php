@extends('layouts.admin')

@section('title', 'Tableau de bord - Studios Unis')

@section('content')
<div class="container-fluid">
    <!-- En-tête moderne du dashboard -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="dashboard-title">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    Tableau de bord
                </h1>
                <p class="dashboard-subtitle">
                    Vue d'ensemble de votre système de gestion - Studios Unis
                </p>
            </div>
            <div class="text-end">
                <div class="text-white">
                    <i class="fas fa-calendar-alt me-2"></i>
                    {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd, LL') }}
                </div>
                <div class="text-white-50 mt-1">
                    <i class="fas fa-clock me-2"></i>
                    Dernière mise à jour: {{ \Carbon\Carbon::now()->format('H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-card-modern info">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $totalMembres }}</h3>
                    <p>Membres inscrits</p>
                </div>
                <div class="stat-icon info">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('membres.index') }}">
                    <span>Voir tous les membres</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card-modern success">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $totalPresences }}</h3>
                    <p>Présences aujourd'hui</p>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('presences.index') }}">
                    <span>Gérer les présences</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card-modern warning">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $membresEnAttente }}</h3>
                    <p>Membres en attente</p>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('membres.index') }}?status=pending">
                    <span>Voir les demandes</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card-modern danger">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $totalEcoles }}</h3>
                    <p>École(s) active(s)</p>
                </div>
                <div class="stat-icon danger">
                    <i class="fas fa-school"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('ecoles.index') }}">
                    <span>Gérer les écoles</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="content-grid">
        <!-- Derniers membres -->
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="content-card-title">
                    <i class="fas fa-user-plus"></i>
                    Derniers membres inscrits
                </h3>
            </div>
            <div class="content-card-body">
                @if($derniersMembres->count() > 0)
                    <div class="table-responsive">
                        <table class="modern-table table">
                            <thead>
                                <tr>
                                    <th>Membre</th>
                                    <th>Contact</th>
                                    <th>École</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($derniersMembres as $membre)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="member-avatar me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <div class="text-white font-weight-bold">
                                                        {{ $membre->prenom }} {{ $membre->nom }}
                                                    </div>
                                                    <small class="text-muted">
                                                        Inscrit le {{ $membre->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($membre->email)
                                                <div class="text-white">{{ $membre->email }}</div>
                                            @endif
                                            @if($membre->telephone)
                                                <small class="text-muted">{{ $membre->telephone }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($membre->ecole)
                                                <span class="badge-modern info">{{ $membre->ecole->nom }}</span>
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h4>Aucun membre récent</h4>
                        <p>Aucun nouveau membre inscrit récemment</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sessions de cours -->
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="content-card-title">
                    <i class="fas fa-calendar-alt"></i>
                    Sessions de cours récentes
                </h3>
            </div>
            <div class="content-card-body">
                @if($sessions->count() > 0)
                    <div class="table-responsive">
                        <table class="modern-table table">
                            <thead>
                                <tr>
                                    <th>Période</th>
                                    <th>Cours</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions->take(5) as $session)
                                    <tr>
                                        <td>
                                            <div class="text-white">
                                                {{ $session->date_debut ? \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') : 'Non définie' }}
                                            </div>
                                            <small class="text-muted">
                                                au {{ $session->date_fin ? \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') : 'Non définie' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge-modern primary">
                                                {{ $session->cours_count ?? 0 }} cours
                                            </span>
                                        </td>
                                        <td>
                                            @if($session->date_fin && \Carbon\Carbon::parse($session->date_fin)->isPast())
                                                <span class="badge-modern info">Terminée</span>
                                            @elseif($session->date_debut && \Carbon\Carbon::parse($session->date_debut)->isFuture())
                                                <span class="badge-modern warning">À venir</span>
                                            @else
                                                <span class="badge-modern primary">En cours</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt"></i>
                        <h4>Aucune session récente</h4>
                        <p>Aucune session de cours récente trouvée</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="quick-actions">
        <div class="quick-actions-header">
            <h3 class="quick-actions-title">
                <i class="fas fa-bolt"></i>
                Actions rapides
            </h3>
        </div>
        <div class="actions-grid">
            <a href="{{ route('membres.create') }}" class="action-btn primary">
                <i class="action-icon primary fas fa-user-plus"></i>
                <p class="action-text">Nouveau membre</p>
            </a>
            
            <a href="{{ route('cours.create') }}" class="action-btn success">
                <i class="action-icon success fas fa-chalkboard-teacher"></i>
                <p class="action-text">Nouveau cours</p>
            </a>
            
            <a href="{{ route('presences.create') }}" class="action-btn info">
                <i class="action-icon info fas fa-check-circle"></i>
                <p class="action-text">Marquer présence</p>
            </a>
            
            @if(auth()->user()->role === 'superadmin')
                <a href="{{ route('users.create') }}" class="action-btn warning">
                    <i class="action-icon warning fas fa-user-cog"></i>
                    <p class="action-text">Nouvel utilisateur</p>
                </a>
            @endif
        </div>
    </div>
</div>

<style>
/* === STYLES ADDITIONNELS === */
.member-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #17a2b8, #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

/* Animation pour les statistiques */
.stat-info h3 {
    animation: countUp 1s ease-out;
}

@keyframes countUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive pour les cartes */
@media (max-width: 576px) {
    .stat-content {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        margin-bottom: 1rem;
    }
}
</style>
@endsection
