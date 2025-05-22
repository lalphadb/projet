{{-- resources/views/presences/voir.blade.php --}}
@extends('adminlte::page')

@section('title', 'Rapport de Présences')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>
                <i class="fas fa-chart-bar text-info"></i> 
                Rapport - {{ $cours->nom }}
            </h1>
            <small class="text-muted">
                {{ $cours->heure_debut }} - {{ $cours->heure_fin }} | 
                {{ \Carbon\Carbon::today()->isoFormat('dddd D MMMM YYYY') }}
            </small>
        </div>
        <div>
            <a href="{{ route('quotidien.prendre', $cours) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('quotidien.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
@stop

@section('content')
    {{-- Statistiques --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Présents</span>
                    <span class="info-box-number">{{ $presents->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">En Retard</span>
                    <span class="info-box-number">{{ $retards->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-user-times"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Absents</span>
                    <span class="info-box-number">{{ $absents->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Taux Présence</span>
                    <span class="info-box-number">
                        @php
                            $total = $presents->count() + $retards->count() + $absents->count();
                            $tauxPresence = $total > 0 ? round((($presents->count() + $retards->count()) / $total) * 100) : 0;
                        @endphp
                        {{ $tauxPresence }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Présents --}}
        <div class="col-md-4">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-check"></i> 
                        Présents ({{ $presents->count() }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if($presents->isEmpty())
                        <div class="alert alert-info m-3 mb-0">
                            <i class="fas fa-info-circle"></i> Aucun membre présent
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($presents as $presence)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $presence->membre->nom }} {{ $presence->membre->prenom }}</strong>
                                        @if($presence->commentaire)
                                            <br><small class="text-muted">{{ $presence->commentaire }}</small>
                                        @endif
                                    </div>
                                    <span class="badge badge-success">✅</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Retards --}}
        <div class="col-md-4">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock"></i> 
                        En Retard ({{ $retards->count() }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if($retards->isEmpty())
                        <div class="alert alert-info m-3 mb-0">
                            <i class="fas fa-info-circle"></i> Aucun retard
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($retards as $presence)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $presence->membre->nom }} {{ $presence->membre->prenom }}</strong>
                                        @if($presence->commentaire)
                                            <br><small class="text-muted">{{ $presence->commentaire }}</small>
                                        @endif
                                    </div>
                                    <span class="badge badge-warning">⏰</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Absents --}}
        <div class="col-md-4">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-times"></i> 
                        Absents ({{ $absents->count() }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if($absents->isEmpty())
                        <div class="alert alert-info m-3 mb-0">
                            <i class="fas fa-info-circle"></i> Aucune absence
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($absents as $presence)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $presence->membre->nom }} {{ $presence->membre->prenom }}</strong>
                                        @if($presence->commentaire)
                                            <br><small class="text-muted">{{ $presence->commentaire }}</small>
                                        @endif
                                    </div>
                                    <span class="badge badge-danger">❌</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-download"></i> Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                        <a href="{{ route('export.presences.excel', $cours) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('export.presences.pdf', $cours) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
@media print {
    .content-header, .main-sidebar, .main-footer, .btn-group {
        display: none !important;
    }
}
</style>
@stop
