@extends('layouts.app')
@section('page-title', 'Tableau de bord')
@section('sidebar')
    @include('etudiante.partials._sidebar')
@endsection

@section('content')

{{-- Bienvenue --}}
<div class="alert alert-info">
    <i class="bi bi-person-circle me-2"></i>
    Bienvenue, <strong>{{ auth()->user()->prenom ?? auth()->user()->name }}</strong> 👋 —
    Matricule : <strong>{{ auth()->user()->matricule }}</strong>
</div>

{{-- KPIs --}}
<div class="row">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f)">
            <div class="number"><i class="bi bi-door-closed" style="font-size:1.8rem"></i></div>
            <div class="label">
                @if($maChambre)
                    Chambre {{ $maChambre->numero }} — Bloc {{ $maChambre->bloc }}
                @else
                    Non assignée
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="{{ route('etudiante.annonces') }}" class="text-decoration-none">
        <div class="stat-card" style="background: linear-gradient(135deg,#198754,#20c997)">
            <div class="number">{{ $annonces->count() }}</div>
            <div class="label"><i class="bi bi-megaphone me-1"></i>Annonces</div>
        </div>
</a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('etudiante.notifications') }}" class="text-decoration-none">
        <div class="stat-card" style="background: linear-gradient(135deg,#fd7e14,#ffc107)">
            <div class="number">{{ $notifications->count() }}</div>
            <div class="label"><i class="bi bi-bell me-1"></i>Notifications</div>
        </div>
       </a>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#dc3545,#e91e63)">
            <div class="number"><i class="bi bi-egg-fried" style="font-size:1.8rem"></i></div>
            <div class="label"><a href="https://onou.mesrs.dz/doumenu/22" class="text-white text-decoration-none">Menu du jour</a></div>
        </div>
    </div>
</div>

{{-- Ligne 2 : Annonces + Notifications --}}
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0"><i class="bi bi-megaphone me-1 text-warning"></i> Dernières annonces</h6>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($annonces as $annonce)
                    <li class="list-group-item px-0">
                        <div class="fw-semibold small">{{ $annonce->titre }}</div>
                        <div class="text-muted" style="font-size:12px">{{ $annonce->created_at->diffForHumans() }}</div>
                    </li>
                    @empty
                    <li class="list-group-item px-0 text-muted text-center">Aucune annonce.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0"><i class="bi bi-bell me-1 text-success"></i> Notifications récentes</h6>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($notifications as $notif)
                    <li class="list-group-item px-0 small">
                        {{ $notif->data['message'] ?? 'Notification' }}
                        <div class="text-muted" style="font-size:11px">{{ $notif->created_at->diffForHumans() }}</div>
                    </li>
                    @empty
                    <li class="list-group-item px-0 text-muted text-center">Aucune notification.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>



@endsection