@extends('layouts.app')

@section('title', 'Mon compte')
@section('content')
<div class="content-wrapper p-4 d-flex justify-content-center">
    <section class="content col-lg-8">
        <div class="card bg-dark text-white shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Fiche personnelle</h3>
            </div>

            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nom complet :</dt>
                    <dd class="col-sm-8">{{ $membre->prenom }} {{ $membre->nom }}</dd>

                    <dt class="col-sm-4">Email :</dt>
                    <dd class="col-sm-8">{{ $membre->email }}</dd>

                    <dt class="col-sm-4">Téléphone :</dt>
                    <dd class="col-sm-8">{{ $membre->telephone }}</dd>

                    <dt class="col-sm-4">Date de naissance :</dt>
                    <dd class="col-sm-8">{{ $membre->date_naissance }}</dd>

                    <dt class="col-sm-4">Sexe :</dt>
                    <dd class="col-sm-8">{{ $membre->sexe }}</dd>

                    <dt class="col-sm-4">Adresse :</dt>
                    <dd class="col-sm-8">
                        {{ $membre->numero_rue }} {{ $membre->nom_rue }}<br>
                        {{ $membre->ville }}, {{ $membre->province }} {{ $membre->code_postal }}
                    </dd>

                    <dt class="col-sm-4">École :</dt>
                    <dd class="col-sm-8">{{ $membre->ecole->nom ?? 'Non assignée' }}</dd>
                </dl>
            </div>
        </div>

        <div class="card bg-dark text-white shadow mt-4">
            <div class="card-header">
                <h3 class="card-title">Ceintures obtenues</h3>
            </div>
            <div class="card-body">
                <ul>
                    @forelse($ceintures as $ceinture)
                        <li>{{ $ceinture->nom }} obtenue le {{ $ceinture->pivot->date_obtention }}</li>
                    @empty
                        <li>Aucune ceinture enregistrée.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card bg-dark text-white shadow mt-4">
            <div class="card-header">
                <h3 class="card-title">Séminaires suivis</h3>
            </div>
            <div class="card-body">
                <ul>
                    @forelse($seminaires as $seminaire)
                        <li>{{ $seminaire->nom }} &mdash; {{ $seminaire->date }}</li>
                    @empty
                        <li>Aucun séminaire enregistré.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card bg-dark text-white shadow mt-4">
            <div class="card-header">
                <h3 class="card-title">Horaire des cours</h3>
            </div>
            <div class="card-body">
                <ul>
                    @forelse($cours as $cours)
                        <li>{{ $cours->nom }} &mdash; {{ $cours->jour }} de {{ $cours->heure_debut }} à {{ $cours->heure_fin }}</li>
                    @empty
                        <li>Pas de cours enregistrés.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </section>
</div>
@endsection
