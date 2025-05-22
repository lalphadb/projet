@if($seminaires->count())
    <div class="col-12 mt-4">
        <h5><i class="fas fa-chalkboard me-2"></i>Inscrire à un séminaire</h5>
        <form action="{{ route('membres.seminaire.inscrire', $membre->id) }}" method="POST" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-6">
                <label class="form-label fw-bold">Séminaire</label>
                <select name="seminaire_id" class="form-select">
                    @foreach($seminaires as $s)
                        <option value="{{ $s->id }}">{{ $s->titre }} ({{ \Carbon\Carbon::parse($s->date)->format('d-m-Y') }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus-circle me-1"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
@else
    <div class="col-12 mt-4">
        <div class="alert alert-secondary">
            Aucun séminaire disponible pour le moment.
        </div>
    </div>
@endif
