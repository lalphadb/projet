@extends('layouts.admin')

@section('title', 'Fiche du membre')

@section('content')
<div class="container-fluid">
    <!-- En-tête du profil -->
    <div class="member-profile-header">
        <div class="d-flex align-items-center">
            <div class="member-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="flex-grow-1">
                <h1 class="mb-2 text-white">{{ $membre->prenom }} {{ $membre->nom }}</h1>
                <div class="d-flex align-items-center gap-3">
                    @if($membre->approuve)
                        <span class="status-badge status-approved">
                            <i class="fas fa-check-circle me-2"></i>Approuvé
                        </span>
                    @else
                        <span class="status-badge status-pending">
                            <i class="fas fa-clock me-2"></i>En attente
                        </span>
                    @endif
                    @if($membre->ecole)
                        <span class="badge bg-info">{{ $membre->ecole->nom }}</span>
                    @endif
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('membres.edit', $membre) }}" class="btn btn-warning btn-action">
                    <i class="fas fa-edit"></i>Modifier
                </a>
                <a href="{{ route('membres.index') }}" class="btn btn-secondary btn-action">
                    <i class="fas fa-arrow-left"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche - Informations -->
        <div class="col-lg-6">
            <!-- Informations personnelles -->
            <div class="member-info-section">
                <div class="section-title">
                    <i class="fas fa-id-card"></i>
                    Informations personnelles
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Nom complet</div>
                            <div class="info-value">{{ $membre->prenom }} {{ $membre->nom }}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-{{ $membre->sexe === 'H' ? 'mars' : 'venus' }}"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Sexe</div>
                            <div class="info-value">{{ $membre->sexe === 'H' ? 'Homme' : 'Femme' }}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Date de naissance</div>
                            <div class="info-value">
                                {{ $membre->date_naissance ? \Carbon\Carbon::parse($membre->date_naissance)->format('d/m/Y') : 'Non renseignée' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Adresse -->
            <div class="member-info-section">
                <div class="section-title">
                    <i class="fas fa-address-book"></i>
                    Contact & Adresse
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value">{{ $membre->telephone ?? 'Non renseigné' }}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $membre->email ?? 'Non renseigné' }}</div>
                        </div>
                    </div>
                    
                    @if($membre->numero_rue || $membre->nom_rue || $membre->ville)
                    <div class="info-item" style="grid-column: 1/-1;">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Adresse complète</div>
                            <div class="info-value">
                                {{ $membre->numero_rue }} {{ $membre->nom_rue }}<br>
                                {{ $membre->ville }}, {{ $membre->province }} {{ $membre->code_postal }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - Ceintures et Séminaires -->
        <div class="col-lg-6">
            <!-- Ceintures -->
            <div class="member-info-section">
                <div class="section-title">
                    <i class="fas fa-award"></i>
                    Ceintures obtenues
                    <span class="count-badge ms-auto">{{ $membre->ceintures->count() }}</span>
                </div>
                
                <div class="belt-timeline">
                    @forelse($membre->ceintures->sortByDesc('pivot.date_obtention') as $ceinture)
                        <div class="belt-item">
                            <div class="belt-date">
                                {{ \Carbon\Carbon::parse($ceinture->pivot->date_obtention)->format('d/m/Y') }}
                            </div>
                            <div class="belt-name">{{ $ceinture->nom }}</div>
                            <form action="{{ route('membres.ceinture.retirer', [$membre->id, $ceinture->id]) }}" 
                                  method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="belt-remove" 
                                        onclick="return confirm('Retirer cette ceinture ?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucune ceinture enregistrée.
                        </div>
                    @endforelse
                </div>

                <!-- Formulaire d'ajout de ceinture -->
                <div class="add-form">
                    <h6 class="text-white mb-3">
                        <i class="fas fa-plus me-2"></i>Ajouter une ceinture
                    </h6>
                    <form action="{{ route('membres.ceinture.ajouter', $membre->id) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group-compact">
                                <label for="ceinture_id">Ceinture</label>
                                <select name="ceinture_id" id="ceinture_id" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($ceintures as $ceinture)
                                        <option value="{{ $ceinture->id }}">{{ $ceinture->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group-compact">
                                <label for="date_obtention">Date d'obtention</label>
                                <input type="date" name="date_obtention" id="date_obtention" 
                                       class="form-control" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Séminaires -->
            <div class="member-info-section">
                <div class="section-title">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Séminaires suivis
                    <span class="count-badge ms-auto">{{ $membre->seminaires->count() }}</span>
                </div>
                
                @if($membre->seminaires->count() > 0)
                    <div class="seminars-grid">
                        @foreach($membre->seminaires->sortByDesc('date') as $seminaire)
                            <div class="seminar-card">
                                <form action="{{ route('membres.seminaire.retirer', [$membre->id, $seminaire->id]) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="seminar-remove" 
                                            onclick="return confirm('Retirer ce séminaire ?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                
                                <div class="seminar-title">{{ $seminaire->titre }}</div>
                                <div class="seminar-meta">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($seminaire->date)->format('d/m/Y') }}
                                    @if($seminaire->lieu)
                                        <span class="ms-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $seminaire->lieu }}
                                        </span>
                                    @endif
                                </div>
                                @if($seminaire->description)
                                    <div class="seminar-description">
                                        {{ Str::limit($seminaire->description, 100) }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun séminaire enregistré.
                    </div>
                @endif

                <!-- Formulaire d'ajout de séminaire -->
                <div class="add-form">
                    <h6 class="text-white mb-3">
                        <i class="fas fa-plus me-2"></i>Ajouter un séminaire
                    </h6>
                    <form action="{{ route('membres.seminaire.inscrire', $membre->id) }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" name="titre" class="form-control" 
                                       placeholder="Titre du séminaire" required>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="lieu" class="form-control" 
                                       placeholder="Lieu" required>
                            </div>
                            <div class="col-md-9">
                                <textarea name="description" class="form-control" rows="2" 
                                          placeholder="Description (optionnelle)"></textarea>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-1"></i>Ajouter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
