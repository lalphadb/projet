@extends('layouts.admin')

@section('title', 'Liste des séminaires')

@section('content')
<div class="content-wrapper p-4">
    <div class="container-fluid">
        <div class="card bg-dark text-white glass-card shadow">
            <div class="card-header d-flex justify-content-between align-items-center border-0">
                <h3 class="card-title mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i> Séminaires
                </h3>
                <a href="{{ route('seminaires.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Nouveau séminaire
                </a>
            </div>
            <div class="card-body p-0">
                @if($seminaires->count())
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Titre</th>
                                    <th>Date</th>
                                    <th>Membres</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seminaires as $seminaire)
                                    <tr>
                                        <td>{{ $seminaire->id }}</td>
                                        <td>{{ $seminaire->titre }}</td>
                                        <td>{{ \Carbon\Carbon::parse($seminaire->date)->format('d-m-Y') }}</td>
                                        <td>{{ $seminaire->membres()->count() }}</td>
                                        <td>
                                            <a href="{{ route('seminaires.show', $seminaire) }}" class="btn btn-sm btn-info me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('seminaires.edit', $seminaire) }}" class="btn btn-sm btn-warning me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('seminaires.destroy', $seminaire) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Supprimer ce séminaire ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 px-3">
                        {{ $seminaires->links() }}
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        Aucun séminaire enregistré.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
