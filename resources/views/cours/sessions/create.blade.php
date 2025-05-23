@extends("layouts.admin")

@section('title', 'Créer une nouvelle session')

@push('styles')
<link href="{{ asset('css/cours/sessions-duplication.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('cours.sessions.index') }}" class="btn btn-secondary-glass me-3">
                    <i class="fas fa-arrow-left"></i> Retour aux sessions
                </a>
                <div>
                    <h1 class="h2 mb-1" style="color: #e2e8f0;">
                        <i class="fas fa-plus-circle me-2"></i>
                        Créer une nouvelle session
                    </h1>
                    <p class="text-muted mb-0">Configurez une nouvelle session pour organiser vos cours</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-edit me-2"></i>
                        Informations de la session
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cours.sessions.store') }}">
                        @csrf

                        <!-- Nom et période -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="nom" class="form-label">Nom de la session *</label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" 
                                           value="{{ old('nom') }}" 
                                           placeholder="Ex: Hiver 2025"
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="mois" class="form-label">Période</label>
                                    <input type="text" 
                                           class="form-control @error('mois') is-invalid @enderror" 
                                           id="mois" name="mois" 
                                           value="{{ old('mois') }}" 
                                           placeholder="Ex: Jan-Mar">
                                    @error('mois')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date_debut" class="form-label">Date de début *</label>
                                    <input type="date" 
                                           class="form-control @error('date_debut') is-invalid @enderror" 
                                           id="date_debut" name="date_debut" 
                                           value="{{ old('date_debut') }}" 
                                           required>
                                    @error('date_debut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date_fin" class="form-label">Date de fin *</label>
                                    <input type="date" 
                                           class="form-control @error('date_fin') is-invalid @enderror" 
                                           id="date_fin" name="date_fin" 
                                           value="{{ old('date_fin') }}" 
                                           required>
                                    @error('date_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3"
                                      placeholder="Description de la session, objectifs, informations particulières...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date_limite_inscription" class="form-label">Date limite d'inscription</label>
                                    <input type="date" 
                                           class="form-control @error('date_limite_inscription') is-invalid @enderror" 
                                           id="date_limite_inscription" name="date_limite_inscription" 
                                           value="{{ old('date_limite_inscription') }}">
                                    @error('date_limite_inscription')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="couleur" class="form-label">Couleur d'identification</label>
                                    <input type="color" 
                                           class="form-control @error('couleur') is-invalid @enderror" 
                                           id="couleur" name="couleur" 
                                           value="{{ old('couleur', '#48bb78') }}"
                                           style="height: 45px;">
                                    @error('couleur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Cases à cocher -->
                        <div class="form-group mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" 
                                       id="activer_inscriptions" name="activer_inscriptions" value="1"
                                       {{ old('activer_inscriptions', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activer_inscriptions">
                                    <strong>Activer les inscriptions</strong>
                                    <br><small class="text-muted">Les membres pourront s'inscrire aux cours de cette session</small>
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" 
                                       id="visible_public" name="visible_public" value="1"
                                       {{ old('visible_public', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="visible_public">
                                    <strong>Visible publiquement</strong>
                                    <br><small class="text-muted">La session apparaîtra dans les listes publiques</small>
                                </label>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('cours.sessions.index') }}" class="btn btn-secondary-glass">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-success-gradient">
                                <i class="fas fa-save me-2"></i>Créer la session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Aide -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-lightbulb me-2"></i>Conseils
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 style="color: #48bb78;">
                            <i class="fas fa-check-circle me-2"></i>Bonnes pratiques
                        </h6>
                        <ul style="color: #a0aec0; font-size: 0.9rem;">
                            <li>Utilisez un nom clair (ex: Hiver 2025)</li>
                            <li>Définissez des dates réalistes</li>
                            <li>Activez les inscriptions au bon moment</li>
                            <li>Choisissez une couleur distinctive</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 style="color: #667eea;">
                            <i class="fas fa-calendar me-2"></i>Organisation
                        </h6>
                        <p style="color: #a0aec0; font-size: 0.9rem;">
                            Une fois la session créée, vous pourrez :
                            <br>• Créer des cours pour cette session
                            <br>• Dupliquer des cours existants
                            <br>• Gérer les inscriptions
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Validation des dates
document.addEventListener('DOMContentLoaded', function() {
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    
    dateDebut.addEventListener('change', function() {
        dateFin.min = this.value;
        if (dateFin.value && dateFin.value < this.value) {
            dateFin.value = '';
        }
    });
    
    // Générer nom automatique basé sur les dates
    dateFin.addEventListener('change', function() {
        const nomInput = document.getElementById('nom');
        if (!nomInput.value && dateDebut.value && dateFin.value) {
            const debut = new Date(dateDebut.value);
            const fin = new Date(dateFin.value);
            const moisDebut = debut.toLocaleDateString('fr-FR', { month: 'long' });
            const annee = debut.getFullYear();
            
            // Suggestions selon la période
            if (debut.getMonth() >= 0 && debut.getMonth() <= 2) {
                nomInput.value = `Hiver ${annee}`;
                document.getElementById('mois').value = 'Jan-Mar';
            } else if (debut.getMonth() >= 3 && debut.getMonth() <= 5) {
                nomInput.value = `Printemps ${annee}`;
                document.getElementById('mois').value = 'Avr-Juin';
            } else if (debut.getMonth() >= 6 && debut.getMonth() <= 8) {
                nomInput.value = `Été ${annee}`;
                document.getElementById('mois').value = 'Juil-Sep';
            } else {
                nomInput.value = `Automne ${annee}`;
                document.getElementById('mois').value = 'Oct-Déc';
            }
        }
    });
});
</script>
@endpush
