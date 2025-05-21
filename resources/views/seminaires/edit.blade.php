@extends('layouts.admin')

@section('title', 'Modifier le séminaire')

@section('content')
<div class="content-wrapper p-4">
    <div class="container-fluid">
        <div class="card bg-dark text-white glass-card shadow">
            <div class="card-header d-flex justify-content-between align-items-center border-0">
                <h3 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i> Modifier : {{ $seminaire->titre }}
                </h3>
                <a href="{{ route('seminaires.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('seminaires.update', $seminaire) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Titre *</label>
                        <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre', $seminaire->titre) }}">
                        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Date *</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $seminaire->date) }}">
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Lieu *</label>
                        <input type="text" name="lieu" class="form-control @error('lieu') is-invalid @enderror" value="{{ old('lieu', $seminaire->lieu) }}">
                        @error('lieu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $seminaire->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
