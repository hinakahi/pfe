@extends('layouts.app')

@section('page-title', 'Annonces')

@section('sidebar')
    @include('technicien.partials._sidebar')
@endsection

@section('content')
<div class="container" style="margin-top: 2rem;">

    <h5 class="fw-bold mb-3">
        <i class="bi bi-megaphone"></i> Annonces
        <span class="text-muted fs-6 fw-normal ms-2">({{ $annonces->total() }} résultats)</span>
    </h5>

    @if($annonces->count() > 0)
        <div class="row g-3">
            @foreach($annonces as $annonce)
            @php
                $categories = [
                    'generale'    => ['couleur' => '#6c757d', 'bg' => '#f8f9fa', 'label' => 'Générale'],
                    'hebergement' => ['couleur' => '#0d6efd', 'bg' => '#e7f0ff', 'label' => 'Hébergement'],
                    'foyer'       => ['couleur' => '#198754', 'bg' => '#e8f5ee', 'label' => 'Foyer'],
                    'maintenance' => ['couleur' => '#fd7e14', 'bg' => '#fff3e0', 'label' => 'Maintenance'],
                    'promotion'   => ['couleur' => '#dc3545', 'bg' => '#fde8ea', 'label' => 'Promotion'],
                ];
                $cat = $categories[$annonce->categorie] ?? $categories['generale'];
                $urgences = [
                    'general'        => ['couleur' => '#0dcaf0', 'label' => 'Général'],
                    'urgent'         => ['couleur' => '#dc3545', 'label' => 'Urgent'],
                    'administration' => ['couleur' => '#212529', 'label' => 'Administration'],
                ];
                $urg = $urgences[$annonce->urgence ?? 'general'] ?? $urgences['general'];
            @endphp

            <div class="col-md-12">
                <div class="card border-0 shadow-sm hover-card"
                     style="background-color: {{ $cat['bg'] }}; border-left: 5px solid {{ $cat['couleur'] }} !important; border-radius: 12px;">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <div class="mb-2">
                                    <span class="badge rounded-pill" style="background-color: {{ $cat['couleur'] }};">{{ $cat['label'] }}</span>
                                    @if($annonce->urgence !== 'general')
                                        <span class="badge rounded-pill ms-1" style="background-color: {{ $urg['couleur'] }};">{{ $urg['label'] }}</span>
                                    @endif
                                </div>
                                <h5 class="fw-bold mb-1" style="color: {{ $cat['couleur'] }};">{{ $annonce->titre }}</h5>
                                <p class="text-muted mb-2" style="font-size: 0.9rem;">{{ Str::limit($annonce->contenu, 150, '...') }}</p>
                                <small class="text-muted">
                                    {{ $annonce->created_at->format('d/m/Y') }} - {{ $annonce->user->name ?? 'Admin' }}
                                </small>
                            </div>
                            <button class="btn btn-sm ms-3 fw-bold"
                                    style="background-color: {{ $cat['couleur'] }}; color: white; border-radius: 8px;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#annonce{{ $annonce->id }}">
                                Voir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="annonce{{ $annonce->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0" style="background-color: {{ $cat['bg'] }}; border-left: 5px solid {{ $cat['couleur'] }} !important;">
                            <h5 class="modal-title fw-bold" style="color: {{ $cat['couleur'] }};">{{ $annonce->titre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <span class="badge rounded-pill" style="background-color: {{ $cat['couleur'] }};">{{ $cat['label'] }}</span>
                                <span class="badge rounded-pill ms-1" style="background-color: {{ $urg['couleur'] }};">{{ $urg['label'] }}</span>
                            </div>
                            <p style="line-height: 1.8; font-size: 1.05rem;">{{ $annonce->contenu }}</p>
                            <hr>
                            <small class="text-muted">
                                {{ $annonce->created_at->format('d/m/Y à H:i') }} - {{ $annonce->user->name ?? 'Admin' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $annonces->links() }}</div>
    @else
        <div class="card text-center shadow-sm border-0">
            <div class="card-body py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Aucune annonce trouvée</p>
            </div>
        </div>
    @endif
</div>

<style>
    .hover-card { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-card:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15) !important; }
</style>
@endsection