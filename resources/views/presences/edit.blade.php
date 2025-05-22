@extends('layouts.admin')

@section('title', 'Modifier la Présence')

@section('content')
<div class="container-fluid">
    <div class="member-form-container">
        <div class="member-form-header">
            <div class="form-title">
                <i class="fas fa-edit"></i>
                Modifier la Présence
            </div>
            <div class="form-actions">
                <a href="{{ route('presences.show', $presence) }}" class="btn-form-secondary mr-2">
                    <i class="fas fa-eye"></i> Voir
                </a>
                <a href="{{ route('presences.index') }}" class="btn-form-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <form action="{{ route('presences.update', $presence) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="member-form-body">
                @if($errors->any())
                    <div class="validation-errors">
                        <div class="alert">
                            <h6><i class="fas fa-exclamation-triangle"></i> Erreurs de validation</h6>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-user-edit"></i>
                        Informations de Présence
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group-enhanced">
                            <label for="membre_id" class="form-label-enhanced required">Membre</label>
                            <select name="membre_id" id="membre_id" 
                                    class="form-select-enhanced @error('membre_id') is-invalid @enderror" 
                                    required>
                                <option value="">Sélectionner un membre</option>
                                @foreach($membres as $membre)
                                    <option value="{{ $membre->id }}" 
                                        {{ old('membre_id', $presence->membre_id) == $membre->id ? 'selected' : '' }}>
                                        {{ $membre->nom }} {{ $membre->prenom }}
                                        @if($membre->email) - {{ $membre->email }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('membre_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-enhanced">
                            <label for="cours_id" class="form-label-enhanced required">Cours</label>
                            <select name="cours_id" id="cours_id" 
                                    class="form-select-enhanced @error('cours_id') is-invalid @enderror" 
                                    required>
                                <option value="">Sélectionner un cours</option>
                                @foreach($cours as $c)
                                    <option value="{{ $c->id }}" 
                                        {{ old('cours_id', $presence->cours_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->nom }}
                                        @if($c->ecole) - {{ $c->ecole->nom }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('cours_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-grid-2 mt-3">
                        <div class="form-group-enhanced">
                            <label for="date_presence" class="form-label-enhanced required">Date de présence</label>
                            <input type="date" name="date_presence" id="date_presence" 
                                   class="form-control-enhanced @error('date_presence') is-invalid @enderror"
                                   value="{{ old('date_presence', $presence->date_presence->format('Y-m-d')) }}" 
                                   required>
                            @error('date_presence')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-enhanced">
                            <label for="status" class="form-label-enhanced required">Statut</label>
                            <div class="btn-group btn-group-toggle d-flex">
                                @foreach($statuts as $key => $label)
                                    <label class="btn btn-outline-{{ 
                                        $key === 'present' ? 'success' : 
                                        ($key === 'retard' ? 'warning' : 'danger') 
                                    }} flex-fill {{ old('status', $presence->status) == $key ? 'active' : '' }}">
                                        <input type="radio" name="status" value="{{ $key }}" 
                                               {{ old('status', $presence->status) == $key ? 'checked' : '' }} 
                                               required>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group-enhanced mt-3">
                        <label for="commentaire" class="form-label-enhanced">Commentaire</label>
                        <textarea name="commentaire" id="commentaire" rows="3" 
                                  class="form-control-enhanced @error('commentaire') is-invalid @enderror"
                                  placeholder="Commentaire optionnel...">{{ old('commentaire', $presence->commentaire) }}</textarea>
                        @error('commentaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-help">Maximum 500 caractères</small>
                    </div>

                    <div class="form-section mt-4">
                        <div class="section-header">
                            <i class="fas fa-info-circle"></i>
                            Informations de suivi
                        </div>
                        <div class="form-grid-2">
                            <div class="form-group-enhanced">
                                <label class="form-label-enhanced">Créé le</label>
                                <input type="text" class="form-control-enhanced" 
                                       value="{{ $presence->created_at->format('d/m/Y à H:i') }}" 
                                       readonly>
                            </div>
                            @if($presence->updated_at != $presence->created_at)
                                <div class="form-group-enhanced">
                                    <label class="form-label-enhanced">Modifié le</label>
                                    <input type="text" class="form-control-enhanced" 
                                           value="{{ $presence->updated_at->format('d/m/Y à H:i') }}" 
                                           readonly>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn-form-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('presences.show', $presence) }}" class="btn-form-secondary ml-2">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                    </div>
                    <div class="status-badge {{ $presence->status_class }}">
                        {{ $presence->status_label }}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#commentaire').on('input', function() {
        var length = $(this).val().length;
        var maxLength = 500;
        var remaining = maxLength - length;
        
        $(this).next('.form-help').text(
            'Caractères restants : ' + remaining + '/' + maxLength
        );
    });

    // Changement de statut rapide
    $('.btn-group-toggle label').on('click', function() {
        $(this).addClass('active').siblings().removeClass('active');
    });
});

function setStatus(status) {
    $('input[name="status"][value="' + status + '"]').prop('checked', true);
    $('input[name="status"][value="' + status + '"]').closest('label').addClass('active')
        .siblings().removeClass('active');
}
</script>
@endpush
