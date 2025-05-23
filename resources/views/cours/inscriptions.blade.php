@extends('layouts.app')

@section('title', 'Inscriptions - ' . $cours->nom)

@push('styles')
<link href="{{ asset('css/cours/sessions-duplication.css') }}" rel="stylesheet">
<link href="{{ asset('css/reinscription/reinscription.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('cours.show', $cours) }}" class="btn btn-secondary-glass me-3">
                    <i class="fas fa-arrow-left"></i> Retour au cours
                </a>
                <div>
                    <h1 class="h2 mb-1" style="color: #e2e8f0;">
                        <i class="fas fa-users me-2"></i>
                        Inscriptions
                    </h1>
                    <p class="text-muted mb-0">{{ $cours->nom }} - {{ $cours->session->nom ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-success-gradient" data-bs-toggle="modal" data-bs-target="#modalAjouterInscription">
                <i class="fas fa-user-plus me-2"></i>
                Ajouter une inscription
            </button>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="session-stats mb-4">
        <div class="stat-card">
            <span class="stat-number">{{ $cours->inscriptions->where('statut', 'actif')->count() }}</span>
            <div class="stat-label">Inscrits Actifs</div>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ max(0, $cours->places_max - $cours->inscriptions->where('statut', 'actif')->count()) }}</span>
            <div class="stat-label">Places Disponibles</div>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $cours->places_max > 0 ? round(($cours->inscriptions->where('statut', 'actif')->count() / $cours->places_max) * 100, 1) : 0 }}%</span>
            <div class="stat-label">Taux Remplissage</div>
        </div>
        <div class="stat-card">
            <span class="stat-number" style="color: {{ $cours->session && $cours->session->inscriptions_actives ? '#48bb78' : '#f56565' }};">
                {{ $cours->session && $cours->session->inscriptions_actives ? 'Ouvertes' : 'Fermées' }}
            </span>
            <div class="stat-label">Réinscriptions</div>
        </div>
    </div>

    <!-- Liste des inscriptions -->
    <div class="card" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);">
        <div class="card-header" style="background: rgba(45, 55, 72, 0.6); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <h5 class="mb-0" style="color: #e2e8f0;">
                <i class="fas fa-list me-2"></i>
                Liste des inscriptions ({{ $cours->inscriptions->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            @if($cours->inscriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: rgba(45, 55, 72, 0.6);">
                            <tr>
                                <th style="color: #e2e8f0; border: none; padding: 15px;">Membre</th>
                                <th style="color: #e2e8f0; border: none;">Statut</th>
                                <th style="color: #e2e8f0; border: none;">Session</th>
                                <th style="color: #e2e8f0; border: none;">Date inscription</th>
                                <th style="color: #e2e8f0; border: none;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cours->inscriptions->sortBy('membre.nom') as $inscription)
                            <tr style="background: rgba(26, 54, 93, 0.4); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                <td style="color: #e2e8f0; border: none; padding: 15px;">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #48bb78, #38b2ac); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                            {{ strtoupper(substr($inscription->membre->prenom, 0, 1) . substr($inscription->membre->nom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $inscription->membre->prenom }} {{ $inscription->membre->nom }}</strong>
                                            @if($inscription->membre->email)
                                                <br><small style="color: #a0aec0;">{{ $inscription->membre->email }}</small>
                                            @endif
                                            @if($inscription->membre->telephone)
                                                <br><small style="color: #a0aec0;">{{ $inscription->membre->telephone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="border: none;">
                                    @if($inscription->statut === 'actif')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Actif
                                        </span>
                                    @elseif($inscription->statut === 'annule')
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i>Annulé
                                        </span>
                                    @elseif($inscription->statut === 'attente')
                                        <span class="badge bg-warning px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">{{ ucfirst($inscription->statut) }}</span>
                                    @endif
                                </td>
                                <td style="color: #a0aec0; border: none;">
                                    {{ $inscription->session->nom ?? 'N/A' }}
                                    @if($inscription->session && $inscription->session->inscriptions_actives)
                                        <br><small style="color: #48bb78;">
                                            <i class="fas fa-check-circle me-1"></i>Réinscriptions ouvertes
                                        </small>
                                    @endif
                                </td>
                                <td style="color: #a0aec0; border: none;">
                                    {{ $inscription->created_at->format('d/m/Y H:i') }}
                                    @if($inscription->updated_at != $inscription->created_at)
                                        <br><small>Modifié: {{ $inscription->updated_at->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td style="border: none;">
                                    <div class="btn-group" role="group">
                                        <!-- Modifier statut -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <ul class="dropdown-menu" style="background: rgba(45, 55, 72, 0.95); border: 1px solid rgba(255, 255, 255, 0.1);">
                                                <li>
                                                    <form method="POST" action="{{ route('cours.inscriptions.statut', $inscription) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="statut" value="actif">
                                                        <button type="submit" class="dropdown-item" style="color: #48bb78;">
                                                            <i class="fas fa-check me-2"></i>Activer
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('cours.inscriptions.statut', $inscription) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="statut" value="attente">
                                                        <button type="submit" class="dropdown-item" style="color: #ed8936;">
                                                            <i class="fas fa-clock me-2"></i>Mettre en attente
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('cours.inscriptions.statut', $inscription) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="statut" value="annule">
                                                        <button type="submit" class="dropdown-item" style="color: #f56565;">
                                                            <i class="fas fa-times me-2"></i>Annuler
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Voir réinscription -->
                                        <a href="{{ route('reinscription.index', ['membre_id' => $inscription->membre_id]) }}" 
                                           class="btn btn-sm btn-outline-info" title="Voir réinscription">
                                            <i class="fas fa-redo-alt"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form method="POST" action="{{ route('cours.inscriptions.destroy', $inscription) }}" 
                                              class="d-inline" onsubmit="return confirm('Supprimer cette inscription ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
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
                <div class="text-center py-5" style="color: #a0aec0;">
                    <i class="fas fa-user-slash fa-3x mb-3" style="color: #4a5568;"></i>
                    <h5>Aucune inscription</h5>
                    <p>Ce cours n'a encore aucun membre inscrit.</p>
                    <button type="button" class="btn btn-success-gradient mt-3" data-bs-toggle="modal" data-bs-target="#modalAjouterInscription">
                        <i class="fas fa-user-plus me-2"></i>Ajouter la première inscription
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Ajouter Inscription -->
<div class="modal fade modal-duplication" id="modalAjouterInscription" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Ajouter une inscription
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form method="POST" action="{{ route('cours.inscriptions.store', $cours) }}">
                @csrf
                <div class="form-duplication">
                    <div class="form-group">
                        <label for="membre_id">Membre *</label>
                        <select class="form-control" id="membre_id" name="membre_id" required>
                            <option value="">Sélectionner un membre</option>
                            @foreach(\App\Models\Membre::where('ecole_id', $cours->ecole_id)->orderBy('nom')->get() as $membre)
                                @if(!$cours->inscriptions->where('membre_id', $membre->id)->where('statut', 'actif')->count())
                                <option value="{{ $membre->id }}">
                                    {{ $membre->prenom }} {{ $membre->nom }}
                                    @if($membre->email) - {{ $membre->email }} @endif
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="statut_inscription">Statut initial</label>
                        <select class="form-control" id="statut_inscription" name="statut">
                            <option value="actif">Actif</option>
                            <option value="attente">En attente</option>
                        </select>
                    </div>

                    <div class="alert alert-info" style="background: rgba(66, 153, 225, 0.1); border: 1px solid rgba(66, 153, 225, 0.3);">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Places disponibles :</strong> 
                        {{ max(0, $cours->places_max - $cours->inscriptions->where('statut', 'actif')->count()) }} / {{ $cours->places_max }}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-glass" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-success-gradient">
                        <i class="fas fa-user-plus me-2"></i>Ajouter l'inscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/cours/sessions-duplication.js') }}"></script>
@endpush
