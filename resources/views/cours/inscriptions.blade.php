@extends('layouts.admin')

@section('title', 'Gestion des inscriptions')

@push('styles')
<link href="{{ asset('css/cours.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/cours.js') }}"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="cours-container">
        <!-- En-tête -->
        <div class="cours-header">
            <div class="cours-title">
                <div class="title-content">
                    <i class="fas fa-users"></i>
                    Inscriptions : {{ $cours->nom }}
                    <span class="cours-count">{{ $membresInscrits->count() }}/{{ $cours->places_max }} inscrits</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('cours.show', $cours) }}" class="btn-secondary">
                        <i class="fas fa-eye me-2"></i>
                        Voir le cours
                    </a>
                    <a href="{{ route('cours.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Liste des cours
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations du cours -->
        <div class="cours-info-card">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-item">
                        <span class="info-icon"><i class="fas fa-calendar-week"></i></span>
                        <div class="info-content">
                            <span class="info-label">Jours</span>
                            <span class="info-value">
                                @if(is_array($cours->jours))
                                    {{ implode(', ', array_map('ucfirst', $cours->jours)) }}
                                @else
                                    {{ $cours->jours }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <span class="info-icon"><i class="fas fa-clock"></i></span>
                        <div class="info-content">
                            <span class="info-label">Horaire</span>
                            <span class="info-value">{{ $cours->heure_debut }} - {{ $cours->heure_fin }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <span class="info-icon"><i class="fas fa-calendar-alt"></i></span>
                        <div class="info-content">
                            <span class="info-label">Session</span>
                            <span class="info-value">{{ $cours->session->nom ?? 'Non assignée' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <span class="info-icon"><i class="fas fa-percentage"></i></span>
                        <div class="info-content">
                            <span class="info-label">Taux de remplissage</span>
                            <span class="info-value">
                                {{ $cours->places_max > 0 ? round(($membresInscrits->count() / $cours->places_max) * 100) : 0 }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="actions-section">
            <div class="row g-3">
                @if($membresDisponibles->count() > 0)
                    <div class="col-md-6">
                        <div class="action-card">
                            <h6><i class="fas fa-user-plus me-2"></i>Inscrire un membre</h6>
                            <form action="{{ route('cours.inscriptions.store', $cours) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <select name="membre_id" class="form-select flex-grow-1" required>
                                    <option value="">Sélectionner un membre</option>
                                    @foreach($membresDisponibles as $membre)
                                        <option value="{{ $membre->id }}">
                                            {{ $membre->prenom }} {{ $membre->nom }}
                                            @if($membre->email)
                                                ({{ $membre->email }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Inscrire
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($membresInscrits->count() > 0)
                    <div class="col-md-6">
                        <div class="action-card">
                            <h6><i class="fas fa-envelope me-2"></i>Actions groupées</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn-secondary" onclick="exporterListeEmails()">
                                    <i class="fas fa-download me-1"></i>
                                    Exporter emails
                                </button>
                                <button type="button" class="btn-secondary" onclick="envoyerNotification()">
                                    <i class="fas fa-bell me-1"></i>
                                    Notifier tous
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Liste des membres inscrits -->
        <div class="cours-table-container">
            @if($membresInscrits->count())
                <div class="table-header">
                    <h5><i class="fas fa-users me-2"></i>Membres inscrits ({{ $membresInscrits->count() }})</h5>
                    <div class="places-indicator">
                        @php
                            $disponibles = $cours->places_max - $membresInscrits->count();
                            $percentage = ($disponibles / $cours->places_max) * 100;
                        @endphp
                        <span class="places-text">
                            {{ $disponibles }} place(s) disponible(s)
                        </span>
                        <div class="places-bar">
                            <div class="places-fill" style="width: {{ 100 - $percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="cours-table table">
                        <thead>
                            <tr>
                                <th>Membre</th>
                                <th>Contact</th>
                                <th>Date d'inscription</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($membresInscrits as $inscription)
                                <tr>
                                    <td>
                                        <div class="membre-info">
                                            <div class="membre-name">
                                                {{ $inscription->membre->prenom }} {{ $inscription->membre->nom }}
                                            </div>
                                            @if($inscription->membre->date_naissance)
                                                <small class="text-muted">
                                                    Né(e) le {{ \Carbon\Carbon::parse($inscription->membre->date_naissance)->format('d/m/Y') }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            @if($inscription->membre->email)
                                                <div class="contact-item">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    <a href="mailto:{{ $inscription->membre->email }}" class="contact-link">
                                                        {{ $inscription->membre->email }}
                                                    </a>
                                                </div>
                                            @endif
                                            @if($inscription->membre->telephone)
                                                <div class="contact-item">
                                                    <i class="fas fa-phone me-1"></i>
                                                    <a href="tel:{{ $inscription->membre->telephone }}" class="contact-link">
                                                        {{ $inscription->membre->telephone }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="inscription-date">
                                            <span class="date-value">
                                                {{ $inscription->created_at->format('d/m/Y') }}
                                            </span>
                                            <span class="date-time">
                                                {{ $inscription->created_at->format('H:i') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($inscription->statut ?? 'actif')
                                            @case('actif')
                                                <span class="status-indicator status-active">
                                                    <i class="fas fa-check-circle"></i>
                                                    Actif
                                                </span>
                                                @break
                                            @case('en_attente')
                                                <span class="status-indicator status-upcoming">
                                                    <i class="fas fa-hourglass-half"></i>
                                                    En attente
                                                </span>
                                                @break
                                            @case('suspendu')
                                                <span class="status-indicator status-warning">
                                                    <i class="fas fa-pause-circle"></i>
                                                    Suspendu
                                                </span>
                                                @break
                                            @case('annule')
                                                <span class="status-indicator status-danger">
                                                    <i class="fas fa-times-circle"></i>
                                                    Annulé
                                                </span>
                                                @break
                                            @default
                                                <span class="status-indicator status-active">
                                                    <i class="fas fa-check-circle"></i>
                                                    Actif
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('membres.show', $inscription->membre) }}" 
                                               class="btn-action btn-action-view action-tooltip" 
                                               data-tooltip="Voir la fiche membre">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn-action btn-action-edit action-tooltip" 
                                                    data-tooltip="Changer le statut"
                                                    onclick="changerStatut({{ $inscription->id }}, '{{ $inscription->statut ?? 'actif' }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <form action="{{ route('cours.inscriptions.destroy', [$cours, $inscription]) }}" 
                                                  method="POST" 
                                                  class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn-action btn-action-delete action-tooltip" 
                                                        data-tooltip="Désinscrire"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir désinscrire {{ $inscription->membre->prenom }} {{ $inscription->membre->nom }} ?')">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- État vide -->
                <div class="empty-state">
                    <i class="fas fa-user-slash"></i>
                    <h4>Aucun membre inscrit</h4>
                    <p>Ce cours n'a encore aucune inscription.</p>
                    @if($membresDisponibles->count() > 0)
                        <p>{{ $membresDisponibles->count() }} membre(s) peuvent être inscrits.</p>
                    @else
                        <p>Aucun membre disponible pour ce cours.</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Section de réinscription pour session suivante -->
        @if($sessionSuivante && $membresInscrits->count() > 0)
            <div class="reinscription-section">
                <div class="reinscription-card">
                    <h5>
                        <i class="fas fa-redo me-2"></i>
                        Réinscription pour la session suivante
                    </h5>
                    <p class="text-muted">
                        Session suivante : <strong>{{ $sessionSuivante->nom }}</strong> ({{ $sessionSuivante->mois }})
                    </p>
                    <div class="reinscription-actions">
                        <button type="button" class="btn-primary" onclick="ouvrirModalReinscription()">
                            <i class="fas fa-users me-2"></i>
                            Réinscrire les membres automatiquement
                        </button>
                        <small class="text-muted d-block mt-2">
                            Permet de réinscrire tous les membres actifs de ce cours à la même plage horaire pour la session suivante.
                        </small>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modale de changement de statut -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-edit me-2"></i>
                    Changer le statut
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nouveau_statut" class="form-label text-white">Nouveau statut</label>
                        <select class="form-select" id="nouveau_statut" name="statut" required>
                            <option value="actif">Actif</option>
                            <option value="en_attente">En attente</option>
                            <option value="suspendu">Suspendu</option>
                            <option value="annule">Annulé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="raison" class="form-label text-white">Raison (optionnel)</label>
                        <textarea class="form-control" id="raison" name="raison" rows="3" placeholder="Expliquez le changement..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modale de réinscription -->
@if($sessionSuivante)
    <div class="modal fade" id="reinscriptionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-redo me-2"></i>
                        Réinscription automatique
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('cours.reinscription.auto', $cours) }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_destination_id" value="{{ $sessionSuivante->id }}">
                    
                    <div class="modal-body">
                        <p class="text-white-50 mb-3">
                            Cette action va automatiquement réinscrire les membres sélectionnés au même cours 
                            (mêmes jours et horaires) pour la session <strong>{{ $sessionSuivante->nom }}</strong>.
                        </p>
                        
                        <div class="membres-reinscription">
                            @foreach($membresInscrits as $inscription)
                                @if(($inscription->statut ?? 'actif') === 'actif')
                                    <div class="membre-reinscription-item">
                                        <input type="checkbox" 
                                               name="membres[]" 
                                               value="{{ $inscription->membre->id }}" 
                                               id="membre_{{ $inscription->membre->id }}" 
                                               checked>
                                        <label for="membre_{{ $inscription->membre->id }}" class="text-white">
                                            {{ $inscription->membre->prenom }} {{ $inscription->membre->nom }}
                                            @if($inscription->membre->email)
                                                <small class="text-muted">({{ $inscription->membre->email }})</small>
                                            @endif
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note :</strong> Seuls les membres avec le statut "Actif" peuvent être réinscrits automatiquement.
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-redo me-2"></i>
                            Réinscrire automatiquement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<style>
/* Styles spécifiques pour les inscriptions */
.cours-info-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(255, 255, 255, 0.08);
}

.info-icon {
    color: #17a2b8;
    font-size: 1.2rem;
    width: 40px;
    text-align: center;
}

.info-content {
    flex: 1;
}

.info-label {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.info-value {
    display: block;
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
}

/* Section d'actions */
.actions-section {
    margin-bottom: 2rem;
}

.action-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    height: 100%;
}

.action-card h6 {
    color: #ffc107;
    margin-bottom: 1rem;
    font-weight: 600;
}

/* Header du tableau */
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table-header h5 {
    color: #fff;
    margin: 0;
    font-weight: 600;
}

.places-indicator {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.places-text {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    font-weight: 500;
}

.places-bar {
    width: 150px;
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
}

.places-fill {
    height: 100%;
    background: linear-gradient(90deg, #28a745, #20c997);
    transition: width 0.3s ease;
}

/* Informations des membres */
.membre-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.membre-name {
    font-weight: 600;
    color: #fff;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.contact-link {
    color: #17a2b8;
    text-decoration: none;
    font-size: 0.9rem;
}

.contact-link:hover {
    color: #20c997;
    text-decoration: underline;
}

.inscription-date {
    text-align: center;
}

.date-value {
    display: block;
    color: #fff;
    font-weight: 500;
}

.date-time {
    display: block;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.8rem;
}

/* Statuts des inscriptions */
.status-warning {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-danger {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

/* Section de réinscription */
.reinscription-section {
    margin-top: 2rem;
}

.reinscription-card {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
    border: 1px solid rgba(40, 167, 69, 0.3);
    border-radius: 15px;
    padding: 2rem;
}

.reinscription-card h5 {
    color: #28a745;
    margin-bottom: 1rem;
    font-weight: 600;
}

.reinscription-actions {
    margin-top: 1.5rem;
}

/* Membres pour réinscription */
.membres-reinscription {
    max-height: 300px;
    overflow-y: auto;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 1rem;
}

.membre-reinscription-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 6px;
    transition: all 0.3s ease;
}

.membre-reinscription-item:hover {
    background: rgba(255, 255, 255, 0.08);
}

.membre-reinscription-item input[type="checkbox"] {
    accent-color: #28a745;
    transform: scale(1.2);
}

.membre-reinscription-item label {
    cursor: pointer;
    margin: 0;
    flex: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .places-indicator {
        align-items: flex-start;
        width: 100%;
    }
    
    .places-bar {
        width: 100%;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<script>
function changerStatut(inscriptionId, statutActuel) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    const selectStatut = document.getElementById('nouveau_statut');
    
    // Définir l'action du formulaire
    form.action = `/cours/inscriptions/${inscriptionId}/statut`;
    
    // Pré-sélectionner le statut actuel
    selectStatut.value = statutActuel;
    
    modal.show();
}

function ouvrirModalReinscription() {
    const modal = new bootstrap.Modal(document.getElementById('reinscriptionModal'));
    modal.show();
}

function exporterListeEmails() {
    // Récupérer tous les emails des membres inscrits
    const emails = [];
    document.querySelectorAll('.contact-link[href^="mailto:"]').forEach(link => {
        emails.push(link.textContent.trim());
    });
    
    if (emails.length > 0) {
        // Créer un fichier texte avec les emails
        const emailsText = emails.join('; ');
        
        // Copier dans le presse-papiers
        navigator.clipboard.writeText(emailsText).then(() => {
            window.showNotification('Liste des emails copiée dans le presse-papiers', 'success');
        });
        
        // Ou télécharger comme fichier
        const blob = new Blob([emailsText], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `emails_cours_${new Date().getTime()}.txt`;
        a.click();
        window.URL.revokeObjectURL(url);
    } else {
        window.showNotification('Aucun email à exporter', 'error');
    }
}

function envoyerNotification() {
    // Ici vous pouvez implémenter l'envoi de notifications
    // Par exemple, rediriger vers une page d'envoi d'emails
    window.showNotification('Fonctionnalité de notification en développement', 'info');
}

// Mise à jour en temps réel du nombre de places
document.addEventListener('DOMContentLoaded', function() {
    // Observer les changements dans le tableau
    const observer = new MutationObserver(() => {
        const inscrits = document.querySelectorAll('.cours-table tbody tr').length;
        const placesMax = {{ $cours->places_max }};
        const disponibles = placesMax - inscrits;
        const percentage = (inscrits / placesMax) * 100;
        
        // Mettre à jour l'affichage
        const placesText = document.querySelector('.places-text');
        const placesFill = document.querySelector('.places-fill');
        const coursCount = document.querySelector('.cours-count');
        
        if (placesText) placesText.textContent = `${disponibles} place(s) disponible(s)`;
        if (placesFill) placesFill.style.width = `${percentage}%`;
        if (coursCount) coursCount.textContent = `${inscrits}/${placesMax} inscrits`;
        
        // Mettre à jour la couleur selon le taux de remplissage
        if (placesFill) {
            if (percentage >= 90) {
                placesFill.style.background = 'linear-gradient(90deg, #dc3545, #c82333)';
            } else if (percentage >= 75) {
                placesFill.style.background = 'linear-gradient(90deg, #ffc107, #e0a800)';
            } else {
                placesFill.style.background = 'linear-gradient(90deg, #28a745, #20c997)';
            }
        }
    });
    
    observer.observe(document.querySelector('.cours-table tbody'), {
        childList: true,
        subtree: true
    });
});
</script>
@endsection
