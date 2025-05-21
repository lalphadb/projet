<div>
    <input type="text" wire:model="query" class="form-control mb-3" placeholder="Rechercher un membre...">

    @if($membres && count($membres) > 0)
        <ul class="list-group">
            @foreach($membres as $membre)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $membre->prenom }} {{ $membre->nom }}
                    <a href="{{ route('membres.show', $membre->id) }}" class="btn btn-sm btn-primary">Voir</a>
                </li>
            @endforeach
        </ul>
    @elseif(strlen($query) > 1)
        <div class="alert alert-warning">Aucun membre trouvé.</div>
    @endif
</div>
