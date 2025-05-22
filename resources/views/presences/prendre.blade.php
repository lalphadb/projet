{{-- resources/views/presences/prendre.blade.php --}}
@extends('adminlte::page')

@section('title', 'Prendre les Présences')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>
                <i class="fas fa-user-check text-primary"></i> 
                Présences - {{ $cours->nom }}
            </h1>
            <small class="text-muted">
                {{ $cours->heure_debut }} - {{ $cours->heure_fin }} | 
                {{ \Carbon\Carbon::today()->isoFormat('dddd D MMMM YYYY') }}
            </small>
        </div>
        <a href="{{ route('quotidien.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
@stop

@section('content')
    <form action="{{ route('quotidien.enregistrer', $cours) }}" method="POST" id="formPresences">
        @csrf
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list"></i> 
                    Liste des Membres ({{ $membres->count() }})
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-success" onclick="toutPresent()">
                        <i class="fas fa-check-double"></i> Tous présents
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="toutAbsent()">
                        <i class="fas fa-times"></i> Tous absents
                    </button>
                </div>
            </div>
            
            <div class="card-body p-0">
                @if($membres->isEmpty())
                    <div class="alert alert-warning m-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        Aucun membre inscrit à ce cours.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40%">Membre</th>
                                    <th width="30%">Statut</th>
                                    <th width="30%">Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($membres as $membre)
                                    @php
                                        $presenceExistante = $presencesExistantes->get($membre->id);
                                        $statusActuel = $presenceExistante->status ?? 'present';
                                        $commentaireActuel = $presenceExistante->commentaire ?? '';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; margin-right: 10px;">
                                                    <span class="text-white font-weight-bold">
                                                        {{ strtoupper(substr($membre->prenom, 0, 1) . substr($membre->nom, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $membre->nom }} {{ $membre->prenom }}</strong>
                                                    @if($membre->email)
                                                        <br><small class="text-muted">{{ $membre->email }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                @foreach($statutsDisponibles as $status => $label)
                                                    <label class="btn btn-sm btn-outline-{{ $status === 'present' ? 'success' : ($status === 'retard' ? 'warning' : 'danger') }} {{ $statusActuel === $status ? 'active' : '' }}">
                                                        <input type="radio" 
                                                               name="presences[{{ $membre->id }}][status]" 
                                                               value="{{ $status }}"
                                                               {{ $statusActuel === $status ? 'checked' : '' }}>
                                                        {{ $label }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="presences[{{ $membre->id }}][commentaire]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Commentaire (optionnel)"
                                                   value="{{ $commentaireActuel }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
            @if($membres->isNotEmpty())
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> 
                        Enregistrer les Présences ({{ $membres->count() }})
                    </button>
                </div>
            @endif
        </div>
    </form>
@stop

@section('js')
<script>
function toutPresent() {
    $('input[type="radio"][value="present"]').prop('checked', true).closest('label').addClass('active');
    $('input[type="radio"]:not([value="present"])').prop('checked', false).closest('label').removeClass('active');
}

function toutAbsent() {
    $('input[type="radio"][value="absent"]').prop('checked', true).closest('label').addClass('active');
    $('input[type="radio"]:not([value="absent"])').prop('checked', false).closest('label').removeClass('active');
}

// Auto-save indication
$('#formPresences input').on('change', function() {
    $(this).closest('tr').addClass('table-warning');
});
</script>
@stop

@section('css')
<style>
.user-avatar {
    font-size: 14px;
}
.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>
@stop
