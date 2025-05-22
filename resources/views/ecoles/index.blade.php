@extends('layouts.admin')

@section('title', 'Gestion des écoles')

@push('styles')
<link href="{{ asset('css/ecoles.css') }}" rel="stylesheet">
<style>
    /* Masquer les flèches de pagination indésirables */
    .pagination-arrows-container,
    .pagination-large-arrow,
    [class*="pagination-arrow"] {
        display: none !important;
    }
    
    /* Améliorer l'espacement du bas de page */
    .mb-large {
        margin-bottom: 150px;
    }
    
    /* Style de pagination personnalisé */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
    }
    
    .pagination .page-item .page-link {
        border-radius: 4px;
        padding: 8px 16px;
        color: #fff;
        background-color: rgba(26, 32, 44, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.2s;
    }
    
    .pagination .page-item .page-link:hover {
        background-color: rgba(49, 130, 206, 0.7);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #3182ce;
        border-color: #3182ce;
    }
    
    /* Correction de la traduction */
    .page-item:first-child .page-link:empty::before {
        content: "« Précédent";
    }
    
    .page-item:last-child .page-link:empty::before {
        content: "Suivant »";
    }
</style>
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
                        Gestion des écoles
                    </div>
                    
                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('ecoles.create') }}" class="btn-secondary">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter une école
                    </a>
                    @endif
                </div>
            </div>

            <!-- Carte de statistiques globales -->
            @if(auth()->user()->isSuperAdmin())
            <div class="ecole-container mb-4 fade-in">
                <div class="row">
                    <div class="col-md-3">
                        <div class="ecole-stat">
                            <div class="stat-value">{{ $totalEcoles ?? 0 }}</div>
                            <div class="stat-label">Écoles</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="ecole-stat">
                            <div class="stat-value">{{ $totalMembres ?? 0 }}</div>
                            <div class="stat-label">Membres</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="ecole-stat">
                            <div class="stat-value">{{ $totalCours ?? 0 }}</div>
                            <div class="stat-label">Cours</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="ecole-stat">
                            <div class="stat-value">{{ $totalSessions ?? 0 }}</div>
                            <div class="stat-label">Sessions</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Liste des écoles -->
            <div class="row">
                @if(isset($ecoles) && count($ecoles) > 0)
                    @foreach($ecoles as $ecole)
                    <div class="col-md-6 col-lg-4 fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="ecole-card">
                            <div class="ecole-card-header">
                                <h3 class="ecole-card-title">{{ $ecole->nom }}</h3>
                                <span class="ecole-card-status {{ $ecole->active ? 'status-active' : 'status-inactive' }}">
                                    {{ $ecole->active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <div class="ecole-card-content">
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
                                
                                <div class="ecole-card-stats">
                                    <div class="ecole-stat">
                                        <div class="stat-value">{{ $ecole->membres_count ?? 0 }}</div>
                                        <div class="stat-label">Membres</div>
                                    </div>
                                    
                                    <div class="ecole-stat">
                                        <div class="stat-value">{{ $ecole->cours_count ?? 0 }}</div>
                                        <div class="stat-label">Cours</div>
                                    </div>
                                    
                                    <div class="ecole-stat">
                                        <div class="stat-value">{{ $ecole->sessions_count ?? 0 }}</div>
                                        <div class="stat-label">Sessions</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ecole-card-actions">
                                <a href="{{ route('ecoles.show', $ecole->id) }}" class="btn-action btn-action-view" data-toggle="tooltip" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(auth()->user()->isSuperAdmin() || (auth()->user()->isAdmin() && auth()->user()->ecole_id == $ecole->id))
                                <a href="{{ route('ecoles.edit', $ecole->id) }}" class="btn-action btn-action-edit" data-toggle="tooltip" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->isSuperAdmin())
                                <form action="{{ route('ecoles.destroy', $ecole->id) }}" method="POST" class="d-inline" data-confirm="Êtes-vous sûr de vouloir supprimer cette école ? Cette action est irréversible.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" data-toggle="tooltip" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-school"></i>
                            <h4>Aucune école disponible</h4>
                            <p>Il n'y a actuellement aucune école enregistrée dans le système.</p>
                            
                            @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('ecoles.create') }}" class="btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Ajouter une école
                            </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if(isset($ecoles) && $ecoles->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $ecoles->links() }}
            </div>
            @endif
            
            <!-- Espace supplémentaire pour éviter que le footer chevauche le contenu -->
            <div class="mb-large"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Confirmation pour les suppressions
    $('form[data-confirm]').on('submit', function(e) {
        const message = $(this).data('confirm') || 'Êtes-vous sûr de vouloir effectuer cette action ?';
        if (!confirm(message)) {
            e.preventDefault();
            return false;
        }
    });
    
    // Corriger les problèmes de pagination
    // 1. Supprimer les éléments de pagination indésirables
    const paginationArrows = document.querySelectorAll('.pagination-arrows-container, .pagination-large-arrow, [class*="pagination-arrow"]');
    if (paginationArrows) {
        paginationArrows.forEach(arrow => {
            arrow.parentNode.removeChild(arrow);
        });
    }
    
    // 2. Corriger les textes de pagination
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        if (link.textContent === 'pagination.previous') {
            link.textContent = '« Précédent';
        }
        if (link.textContent === 'pagination.next') {
            link.textContent = 'Suivant »';
        }
    });
    
    // 3. Si la pagination existe, s'assurer qu'elle s'affiche correctement
    const pagination = document.querySelector('.pagination');
    if (pagination) {
        pagination.style.display = 'flex';
        pagination.style.justifyContent = 'center';
        pagination.style.marginTop = '20px';
        pagination.style.marginBottom = '20px';
    }
});
</script>
@endpush
