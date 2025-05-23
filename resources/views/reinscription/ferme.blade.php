@extends('layouts.app')

@section('title', 'Réinscriptions fermées')

@push('styles')
<link href="{{ asset('css/reinscription/reinscription.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="reinscription-container">
    <div class="container">
        
        <!-- Header avec info membre -->
        <div class="reinscription-header">
            <h1 class="reinscription-title">
                <i class="fas fa-user-clock me-3"></i>
                Bonjour {{ $membre->prenom }}
            </h1>
            <div class="membre-info">
                <div class="membre-nom">
                    <i class="fas fa-user me-2"></i>
                    {{ $membre->prenom }} {{ $membre->nom }}
                </div>
                <div class="membre-ecole">
                    <i class="fas fa-school me-2"></i>
                    {{ $membre->ecole->nom ?? 'École non définie' }}
                </div>
            </div>
        </div>

        <!-- Message principal -->
        <div class="reinscriptions-fermees">
            <div class="fermees-icon">
                <i class="fas fa-pause-circle"></i>
            </div>
            
            <h2 class="fermees-title">
                Réinscriptions temporairement fermées
            </h2>
            
            <div class="fermees-message">
                <p class="mb-4">
                    Les réinscriptions pour la prochaine session ne sont pas encore ouvertes. 
                    L'administration de votre école prépare actuellement l'offre de cours.
                </p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="info-card">
                            <h5 style="color: #667eea; margin-bottom: 15px;">
                                <i class="fas fa-info-circle me-2"></i>
                                Que faire en attendant ?
                            </h5>
                            <ul style="text-align: left; color: #a0aec0;">
                                <li>Continuez vos cours actuels</li>
                                <li>Consultez régulièrement cette page</li>
                                <li>Vous recevrez une notification par email</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-card">
                            <h5 style="color: #48bb78; margin-bottom: 15px;">
                                <i class="fas fa-phone me-2"></i>
                                Besoin d'aide ?
                            </h5>
                            <div style="text-align: left; color: #a0aec0;">
                                <p>Contactez votre école :</p>
                                @if($membre->ecole)
                                    @if($membre->ecole->telephone)
                                    <p>
                                        <i class="fas fa-phone me-2"></i>
                                        <a href="tel:{{ $membre->ecole->telephone }}" style="color: #48bb78;">
                                            {{ $membre->ecole->telephone }}
                                        </a>
                                    </p>
                                    @endif
                                    
                                    @if($membre->ecole->email)
                                    <p>
                                        <i class="fas fa-envelope me-2"></i>
                                        <a href="mailto:{{ $membre->ecole->email }}" style="color: #48bb78;">
                                            {{ $membre->ecole->email }}
                                        </a>
                                    </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cours actuels si disponibles -->
        @if($membre->inscriptionsCours && $membre->inscriptionsCours->where('statut', 'actif')->count() > 0)
        <div class="cours-actuels mt-5">
            <h3 class="section-title">
                <i class="fas fa-dumbbell"></i>
                Vos cours en cours
            </h3>
            
            <div class="row">
                @foreach($membre->inscriptionsCours->where('statut', 'actif') as $inscription)
                    @if($inscription->cours)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="cours-card">
                            <div class="cours-nom">{{ $inscription->cours->nom }}</div>
                            
                            <div class="cours-details">
                                @if($inscription->cours->instructeur)
                                <div class="cours-detail">
                                    <i class="fas fa-user-tie"></i>
                                    {{ $inscription->cours->instructeur }}
                                </div>
                                @endif
                                
                                @if($inscription->session)
                                <div class="cours-detail">
                                    <i class="fas fa-calendar"></i>
                                    {{ $inscription->session->nom }}
                                </div>
                                @endif
                            </div>

                            <!-- Affichage des horaires -->
                            @if($inscription->cours->plages_horaires)
                                @php
                                    $plages = is_string($inscription->cours->plages_horaires) 
                                        ? json_decode($inscription->cours->plages_horaires, true) 
                                        : $inscription->cours->plages_horaires;
                                @endphp
                                <div class="mt-2">
                                    @foreach($plages as $plage)
                                        @foreach($plage['jours'] as $jour)
                                            <span class="cours-horaire">
                                                {{ ucfirst($jour) }} {{ $plage['heure_debut'] }}-{{ $plage['heure_fin'] }}
                                            </span>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="actions-container">
            <div class="d-flex justify-content-center flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="btn-confirmer">
                    <i class="fas fa-home me-2"></i>
                    Retour au tableau de bord
                </a>
                
                <button type="button" class="btn-annuler" onclick="window.location.reload()">
                    <i class="fas fa-refresh me-2"></i>
                    Actualiser la page
                </button>
            </div>
            
            <div class="mt-4">
                <p style="color: #a0aec0; font-size: 0.9rem;">
                    <i class="fas fa-clock me-2"></i>
                    Dernière vérification : {{ now()->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>

    </div>
</div>

<!-- CSS additionnels pour cette page -->
<style>
.info-card {
    background: rgba(45, 55, 72, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 20px;
    height: 100%;
    backdrop-filter: blur(10px);
}

.info-card ul {
    list-style: none;
    padding-left: 0;
}

.info-card li {
    padding: 5px 0;
    position: relative;
    padding-left: 20px;
}

.info-card li::before {
    content: '✓';
    color: #48bb78;
    font-weight: bold;
    position: absolute;
    left: 0;
}

.fermees-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.7;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Animation d'apparition */
.reinscriptions-fermees {
    animation: fadeInUp 0.8s ease-out;
}

.info-card {
    animation: fadeInUp 1s ease-out;
    animation-delay: 0.3s;
    animation-fill-mode: both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive pour les cartes info */
@media (max-width: 768px) {
    .info-card {
        margin-bottom: 20px;
    }
    
    .fermees-icon {
        font-size: 3rem;
    }
    
    .fermees-title {
        font-size: 1.5rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
// Auto-refresh de la page toutes les 5 minutes pour vérifier si les réinscriptions sont ouvertes
document.addEventListener('DOMContentLoaded', function() {
    // Refresh automatique toutes les 5 minutes
    setTimeout(function() {
        window.location.reload();
    }, 300000); // 5 minutes = 300000ms
    
    // Indicateur de temps restant (optionnel)
    let seconds = 300; // 5 minutes
    const updateTimer = setInterval(function() {
        seconds--;
        if (seconds <= 0) {
            clearInterval(updateTimer);
        }
    }, 1000);
});

// Vérification périodique via AJAX (plus élégant)
function verifierReinscriptions() {
    fetch('/reinscription?membre_id={{ $membre->id }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.url.includes('/reinscription') && !response.url.includes('ferme')) {
            // Les réinscriptions sont ouvertes, rediriger
            window.location.href = response.url;
        }
    })
    .catch(error => {
        console.log('Vérification des réinscriptions:', error);
    });
}

// Vérifier toutes les 2 minutes
setInterval(verifierReinscriptions, 120000);
</script>
@endpush
