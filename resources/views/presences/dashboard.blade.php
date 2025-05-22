@extends('layouts.admin')

@section('title', 'Tableau de bord - Présences')

@section('content')
<div class="container-fluid">
    <div class="stats-grid">
        <div class="stat-card-modern info">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $totalPresences }}</h3>
                    <p>Total Présences</p>
                </div>
                <div class="stat-icon info">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('presences.index') }}">
                    <span>Voir toutes les présences</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card-modern success">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $presentsCeJour }}</h3>
                    <p>Présents aujourd'hui</p>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('quotidien.dashboard') }}">
                    <span>Vue quotidienne</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card-modern warning">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $retardsCeJour }}</h3>
                    <p>Retards aujourd'hui</p>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('presences.index') }}?status=retard">
                    <span>Voir les retards</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="stat-card-modern danger">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ $absentsCeJour }}</h3>
                    <p>Absents aujourd'hui</p>
                </div>
                <div class="stat-icon danger">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('presences.index') }}?status=absent">
                    <span>Voir les absences</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Contenus supplémentaires si nécessaire -->
    <div class="content-grid">
        <!-- Exemple : Dernières présences -->
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="content-card-title">
                    <i class="fas fa-list-alt"></i>
                    Dernières présences
                </h3>
            </div>
            <div class="content-card-body">
                <!-- Tableau des dernières présences -->
                <!-- Implémentation similaire à index.blade.php mais plus compact -->
            </div>
        </div>
    </div>
</div>
@endsection
