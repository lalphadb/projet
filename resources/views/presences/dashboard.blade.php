{{-- resources/views/presences/dashboard.blade.php --}}
@extends('adminlte::page')

@section('title', 'Mes Cours du Jour')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-calendar-day text-primary"></i> 
            Mes Cours du Jour
        </h1>
        <span class="badge badge-info badge-lg">
            {{ \Carbon\Carbon::today()->isoFormat('dddd D MMMM YYYY') }}
        </span>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($coursEnCours)
        <div class="alert alert-info">
            <h5><i class="fas fa-clock"></i> Cours en cours :</h5>
            <strong>{{ $coursEnCours->nom }}</strong> 
            ({{ $coursEnCours->heure_debut }} - {{ $coursEnCours->heure_fin }})
            <br>
            <a href="{{ route('quotidien.prendre', $coursEnCours) }}" class="btn btn-primary btn-sm mt-2">
                <i class="fas fa-user-check"></i> Prendre les présences
            </a>
        </div>
    @endif

    <div class="row">
        @forelse($coursAujourdhui as $cours)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card {{ $cours->id === ($coursEnCours->id ?? null) ? 'card-info' : 'card-default' }}">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chalkboard-teacher"></i>
                            {{ $cours->nom }}
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="fas fa-clock text-muted"></i>
                            <strong>{{ $cours->heure_debut }} - {{ $cours->heure_fin }}</strong>
                        </p>
                        
                        @if($cours->ecole)
                            <p class="mb-2">
                                <i class="fas fa-school text-muted"></i>
                                {{ $cours->ecole->nom }}
                            </p>
                        @endif

                        @if($cours->stats_presence && $cours->stats_presence->total > 0)
                            <div class="mb-3">
                                <small class="text-muted">Présences enregistrées :</small>
                                <div class="d-flex justify-content-between mt-1">
                                    <span class="badge badge-success">✅ {{ $cours->stats_presence->presents }}</span>
                                    <span class="badge badge-warning">⏰ {{ $cours->stats_presence->retards }}</span>
                                    <span class="badge badge-danger">❌ {{ $cours->stats_presence->absents }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-footer">
                        <div class="btn-group w-100">
                            <a href="{{ route('quotidien.prendre', $cours) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-user-check"></i> Présences
                            </a>
                            <a href="{{ route('quotidien.voir', $cours) }}" 
                               class="btn btn-info">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">
                    <h5><i class="fas fa-info-circle"></i> Aucun cours aujourd'hui</h5>
                    <p>Vous n'avez aucun cours programmé pour aujourd'hui.</p>
                </div>
            </div>
        @endforelse
    </div>
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .card-info {
            border-color: #17a2b8;
        }
        .card-info .card-header {
            background-color: #17a2b8;
            color: white;
        }
    </style>
@stop
