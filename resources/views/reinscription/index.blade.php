@extends('layouts.app')

@section('title', 'Réinscription - ' . $membre->prenom . ' ' . $membre->nom)

@push('styles')
<link href="{{ asset('css/reinscription/reinscription.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="reinscription-container">
    <div class="container">
        
        <!-- Header Réinscription -->
        <div class="reinscription-header">
            <h1 class="reinscription-title">
                <i class="fas fa-redo-alt me-3"></i>
                Réinscription
            </h1>
            <p class="reinscription-subtitle">
                Choisissez vos cours pour la prochaine session
            </p>
            
            <!-- Info Membre -->
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

        <!-- Cours Actuels -->
        @if($coursActuels && $coursActuels->count() > 0)
        <div class="cours-actuels">
            <h2 class="section-title">
                <i class="fas fa-clock"></i>
                Vos cours actuels
                @if($sessionActuelle)
                    <small class="text-muted">({{ $sessionActuelle->nom }})</small>
                @endif
            </h2>
            
            <div class="row">
                @foreach($coursActuels as $inscription)
                    <div class="col-md-6 mb-3">
                        <div class="cours-card">
                            <div class="cours-nom">{{ $inscription->cours->nom }}</div>
                            
                            <div class="cours-details">
                                @if($inscription->cours->instructeur)
                                <div class="cours-detail">
                                    <i class="fas fa-user-tie"></i>
                                    {{ $inscription->cours->instructeur }}
                                </div>
                                @endif
                                
                                @if($inscription->cours->tarif)
                                <div class="cours-detail">
                                    <i class="fas fa-dollar-sign"></i>
                                    {{ number_format($inscription->cours->tarif, 0) }}$ / mois
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
                @endforeach
            </div>
        </div>
        @endif

        <!-- Section Réinscription -->
        <div class="reinscription-section">
            <h2 class="section-title">
                <i class="fas fa-calendar-plus"></i>
                Réinscription pour la nouvelle session
            </h2>

            <!-- Info Session -->
            <div class="session-info">
                <div class="session-nom">{{ $prochaineSession->nom }}</div>
                <div class="session-dates">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Du {{ \Carbon\Carbon::parse($prochaineSession->date_debut)->format('d/m/Y') }}
                    au {{ \Carbon\Carbon::parse($prochaineSession->date_fin)->format('d/m/Y') }}
                </div>
            </div>

            <!-- Formulaire de réinscription -->
            <form id="formReinscription" method="POST" action="{{ route('reinscription.confirmer') }}">
                @csrf
                <input type="hidden" id="membreId" name="membre_id" value="{{ $membre->id }}">
                <input type="hidden" id="sessionId" name="session_id" value="{{ $prochaineSession->id }}">

                <!-- Cours Disponibles -->
                <div class="cours-disponibles">
                    @if($coursDisponibles && $coursDisponibles->count() > 0)
                        @foreach($coursDisponibles as $nomCours => $coursOptions)
                            <div class="cours-groupe">
                                <h3 class="groupe-title">
                                    <i class="fas fa-dumbbell me-2"></i>
                                    {{ $nomCours }}
                                </h3>
                                
                                <div class="cours-options">
                                    @foreach($coursOptions as $cours)
                                        <div class="cours-option" 
                                             data-cours-id="{{ $cours->id }}"
                                             data-cours-nom="{{ $cours->nom }}"
                                             data-tarif="{{ $cours->tarif ?? 0 }}">
                                            
                                            <input type="checkbox" 
                                                   name="cours_choisis[]" 
                                                   value="{{ $cours->id }}" 
                                                   id="cours_{{ $cours->id }}">
                                            
                                            <div class="option-header">
                                                <div class="option-nom">{{ $cours->nom }}</div>
                                            </div>
                                            
                                            <div class="option-details">
                                                @if($cours->instructeur)
                                                <div class="option-instructeur">
                                                    <i class="fas fa-user-tie me-2"></i>
                                                    Instructeur: {{ $cours->instructeur }}
                                                </div>
                                                @endif

                                                <!-- Places disponibles -->
                                                @php
                                                    $inscrits = $cours->inscriptions->count();
                                                    $placesMax = $cours->places_max;
                                                    $pourcentage = $placesMax > 0 ? ($inscrits / $placesMax) * 100 : 0;
                                                    $classeGauge = $pourcentage >= 90 ? 'danger' : ($pourcentage >= 70 ? 'warning' : '');
                                                @endphp
                                                
                                                <div class="option-places">
                                                    <div class="places-info">
                                                        {{ $placesMax - $inscrits }} places disponibles
                                                    </div>
                                                    <div class="places-gauge">
                                                        <div class="places-fill {{ $classeGauge }}" 
                                                             style="width: {{ $pourcentage }}%"></div>
                                                    </div>
                                                    <div class="places-info">
                                                        {{ $inscrits }}/{{ $placesMax }}
                                                    </div>
                                                </div>

                                                <!-- Horaires -->
                                                @if($cours->plages_horaires)
                                                    @php
                                                        $plages = is_string($cours->plages_horaires) 
                                                            ? json_decode($cours->plages_horaires, true) 
                                                            : $cours->plages_horaires;
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

                                            @if($cours->tarif)
                                            <div class="option-tarif">
                                                {{ number_format($cours->tarif, 0) }}$ / mois
                                            </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5" style="color: #a0aec0;">
                            <i class="fas fa-info-circle fa-3x mb-3" style="color: #4a5568;"></i>
                            <h5>Aucun cours disponible</h5>
                            <p>Les cours pour cette session ne sont pas encore configurés.</p>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Actions de confirmation -->
        <div class="actions-container">
            <div class="total-section">
                <div class="total-label">Total mensuel</div>
                <div class="total-montant" id="totalMontant">0.00 $</div>
            </div>

            <div class="d-flex justify-content-center flex-wrap gap-3">
                <button type="button" class="btn-confirmer" id="btnConfirmer" disabled>
                    <i class="fas fa-check me-2"></i>
                    Sélectionnez des cours
                </button>
                
                <a href="{{ route('dashboard') }}" class="btn-annuler">
                    <i class="fas fa-times me-2"></i>
                    Annuler
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Messages d'alerte -->
@if(session('success'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div class="alert alert-success-glass alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div class="alert alert-danger-glass alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if($errors->any())
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div class="alert alert-warning-glass alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="{{ asset('js/reinscription/reinscription.js') }}"></script>
<script>
// Auto-dismiss alerts après 5 secondes
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush
