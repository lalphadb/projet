<br><small class="text-muted">{{ $membre->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-white">{{ $membre->telephone }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $membre->created_at->format('d/m/Y') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('membres.show', $membre->id) }}" 
                                                       class="btn btn-sm btn-info"
                                                       title="Voir le profil">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-danger"
                                                            onclick="removeMember({{ $membre->id }})"
                                                            title="Désinscrire">
                                                        <i class="fas fa-user-minus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-4">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h4 class="text-white">Aucun membre inscrit</h4>
                            <p class="text-muted">Ce cours n'a pas encore de membres inscrits.</p>
                            <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#addMemberModal">
                                <i class="fas fa-user-plus mr-2"></i>
                                Inscrire le premier membre
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Statistiques rapides -->
            <div class="card glass-effect mb-4">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title text-white">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Taux d'occupation</span>
                            <span class="badge badge-modern {{ ($membres_inscrits->count() ?? 0) > ($cours->places_max * 0.8) ? 'danger' : 'success' }}">
                                {{ isset($membres_inscrits) ? round(($membres_inscrits->count() / $cours->places_max) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-{{ ($membres_inscrits->count() ?? 0) > ($cours->places_max * 0.8) ? 'danger' : 'success' }}" 
                                 style="width: {{ isset($membres_inscrits) ? round(($membres_inscrits->count() / $cours->places_max) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Durée du cours</span>
                            <span class="text-white">
                                @php
                                    $debut = \Carbon\Carbon::parse($cours->heure_debut);
                                    $fin = \Carbon\Carbon::parse($cours->heure_fin);
                                    $duree = $debut->diffInMinutes($fin);
                                @endphp
                                {{ floor($duree/60) }}h {{ $duree%60 }}min
                            </span>
                        </div>
                    </div>

                    @if($cours->date_debut && $cours->date_fin)
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Nombre de séances</span>
                            <span class="text-white">
                                @php
                                    $debut = \Carbon\Carbon::parse($cours->date_debut);
                                    $fin = \Carbon\Carbon::parse($cours->date_fin);
                                    $seances = 0;
                                    while ($debut <= $fin) {
                                        if (strtolower($debut->format('l')) === strtolower($cours->jour) || 
                                            strtolower($debut->locale('fr')->dayName) === strtolower($cours->jour)) {
                                            $seances++;
                                        }
                                        $debut->addDay();
                                    }
                                @endphp
                                ~{{ $seances }} séances
                            </span>
                        </div>
                    </div>
                    @endif

                    <div class="stat-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Statut</span>
                            <span class="badge badge-modern {{ $cours->date_fin && \Carbon\Carbon::parse($cours->date_fin)->isPast() ? 'danger' : 'success' }}">
                                {{ $cours->date_fin && \Carbon\Carbon::parse($cours->date_fin)->isPast() ? 'Terminé' : 'Actif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card glass-effect mb-4">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title text-white">
                        <i class="fas fa-bolt mr-2"></i>
                        Actions rapides
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('cours.edit', $cours->id) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit mr-2"></i>
                            Modifier le cours
                        </a>
                        
                        @if(isset($membres_inscrits))
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#addMemberModal">
                            <i class="fas fa-user-plus mr-2"></i>
                            Inscrire un membre
                        </button>
                        @endif
                        
                        <button class="btn btn-info btn-block" onclick="duplicateCours()">
                            <i class="fas fa-copy mr-2"></i>
                            Dupliquer le cours
                        </button>
                        
                        <button class="btn btn-success btn-block" onclick="exportData()">
                            <i class="fas fa-download mr-2"></i>
                            Exporter les données
                        </button>
                        
                        <hr class="border-secondary">
                        
                        <button class="btn btn-danger btn-block" onclick="deleteCours()">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer le cours
                        </button>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="card glass-effect">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title text-white">
                        <i class="fas fa-history mr-2"></i>
                        Historique
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon bg-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="text-white">Cours créé</h6>
                                <small class="text-muted">{{ $cours->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                        
                        @if($cours->updated_at != $cours->created_at)
                        <div class="timeline-item">
                            <div class="timeline-icon bg-warning">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="text-white">Dernière modification</h6>
                                <small class="text-muted">{{ $cours->updated_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($membres_inscrits) && $membres_inscrits->count() > 0)
                        <div class="timeline-item">
                            <div class="timeline-icon bg-info">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="text-white">{{ $membres_inscrits->count() }} membre(s) inscrit(s)</h6>
                                <small class="text-muted">Dernière inscription: {{ $membres_inscrits->last()->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de membre -->
    @if(isset($membres_disponibles))
    <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-user-plus mr-2"></i>
                        Inscrire un membre au cours
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addMemberForm">
                        @csrf
                        <div class="form-group">
                            <label for="membre_select" class="text-white">Sélectionner un membre</label>
                            <select class="form-control" id="membre_select" name="membre_id" required>
                                <option value="">-- Choisir un membre --</option>
                                @foreach($membres_disponibles as $membre)
                                    <option value="{{ $membre->id }}">
                                        {{ $membre->prenom }} {{ $membre->nom }} ({{ $membre->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_inscription" class="text-white">Date d'inscription</label>
                            <input type="date" class="form-control" id="date_inscription" name="date_inscription" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="notes" class="text-white">Notes (optionnel)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Notes sur l'inscription..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </button>
                    <button type="button" class="btn btn-success" onclick="addMember()">
                        <i class="fas fa-user-plus mr-2"></i>
                        Inscrire le membre
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
.info-item {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 0.75rem;
}

.info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.info-value {
    color: #fff;
    font-size: 1rem;
    margin: 0;
    line-height: 1.4;
}

.stat-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.stat-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: rgba(255, 255, 255, 0.2);
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-icon {
    position: absolute;
    left: -2rem;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
}

.timeline-content h6 {
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #17a2b8, #00caff);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.btn-group .btn {
    border-radius: 4px;
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.d-grid {
    display: grid;
    gap: 0.75rem;
}

.progress {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}

.badge-modern.purple {
    background: linear-gradient(135deg, #6f42c1, #9b59b6);
}

@media (max-width: 768px) {
    .content-header {
        flex-direction: column;
        text-align: center;
    }
    
    .content-header .btn-group {
        margin-top: 1rem;
        width: 100%;
    }
    
    .content-header .btn {
        flex: 1;
    }
    
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline-icon {
        left: -1.5rem;
        width: 25px;
        height: 25px;
        font-size: 0.7rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function duplicateCours() {
    if (confirm('Voulez-vous créer une copie de ce cours ?')) {
        // Rediriger vers le formulaire de création avec les données pré-remplies
        const url = '{{ route("cours.create") }}' + '?duplicate={{ $cours->id }}';
        window.location.href = url;
    }
}

function deleteCours() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("cours.destroy", $cours->id) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportData() {
    // Créer un lien pour télécharger les données du cours
    const url = '{{ route("cours.export", $cours->id) }}';
    window.open(url, '_blank');
}

@if(isset($membres_inscrits))
function addMember() {
    const form = document.getElementById('addMemberForm');
    const formData = new FormData(form);
    
    fetch('{{ route("cours.members.add", $cours->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue');
    });
}

function removeMember(memberId) {
    if (confirm('Êtes-vous sûr de vouloir désinscrire ce membre du cours ?')) {
        fetch(`{{ route("cours.members.remove", $cours->id) }}/${memberId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}
@endif

// Animation au chargement
$(document).ready(function() {
    $('.card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('animate__animated animate__fadeInUp');
    });
});
</script>
@endpush
@endsection
