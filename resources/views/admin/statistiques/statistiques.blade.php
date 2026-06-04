@extends('layouts.app') {{-- Assurez-vous que c'est le nom de votre fichier layout --}}

@section('page-title', 'Statistiques Globales')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link">
        <i class="bi bi-house"></i> <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.statistiques') }}" class="nav-link active">
        <i class="bi bi-pie-chart-fill"></i> <span>Statistiques</span>
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Maintenance --}}
        <div class="col-md-4">
            <div class="card p-3">
                <h6 class="card-title"><i class="bi bi-tools me-2"></i>Taux de maintenance</h6>
                <canvas id="maintenanceChart" height="200"></canvas>
            </div>
        </div>
        {{-- Chambres --}}
        <div class="col-md-4">
            <div class="card p-3">
                <h6 class="card-title"><i class="bi bi-door-open me-2"></i>Occupation chambres</h6>
                <canvas id="chambreChart" height="200"></canvas>
            </div>
        </div>
        {{-- Demandes --}}
        <div class="col-md-4">
            <div class="card p-3">
                <h6 class="card-title"><i class="bi bi-file-earmark-text me-2"></i>Demandes</h6>
                <canvas id="demandeChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card p-3">
                <h6 class="card-title"><i class="bi bi-graph-up me-2"></i>Inscriptions (6 mois)</h6>
                <canvas id="inscriptionsChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#e9ecef' : '#212529';

    new Chart(document.getElementById('maintenanceChart'), {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'En cours', 'Terminée'],
            datasets: [{
                data: [{{ $maintenanceStats['en_attente'] }}, {{ $maintenanceStats['en_cours'] }}, {{ $maintenanceStats['terminee'] }}],
                backgroundColor: ['#ffc107', '#0d6efd', '#198754']
            }]
        },
        options: { plugins: { legend: { labels: { color: textColor } } } }
    });

    new Chart(document.getElementById('chambreChart'), {
        type: 'doughnut',
        data: {
            labels: ['Disponibles', 'Occupées'],
            datasets: [{
                data: [{{ $chambreStats['disponibles'] }}, {{ $chambreStats['occupees'] }}],
                backgroundColor: ['#198754', '#dc3545']
            }]
        },
        options: { plugins: { legend: { labels: { color: textColor } } } }
    });

    new Chart(document.getElementById('demandeChart'), {
        type: 'bar',
        data: {
            labels: ['Renouvellement', 'Changement'],
            datasets: [
                { label: 'En attente', data: [{{ $demandeStats['renouvellement_attente'] }}, {{ $demandeStats['changement_attente'] }}], backgroundColor: '#ffc107' },
                { label: 'Validée', data: [{{ $demandeStats['renouvellement_validee'] }}, {{ $demandeStats['changement_acceptee'] }}], backgroundColor: '#198754' },
                { label: 'Refusée', data: [{{ $demandeStats['renouvellement_refusee'] }}, {{ $demandeStats['changement_refusee'] }}], backgroundColor: '#dc3545' }
            ]
        },
        options: { 
            scales: { x: { ticks: { color: textColor } }, y: { ticks: { color: textColor }, beginAtZero: true } },
            plugins: { legend: { labels: { color: textColor } } }
        }
    });

    new Chart(document.getElementById('inscriptionsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($inscriptionsMois->keys()) !!},
            datasets: [{
                label: 'Inscriptions',
                data: {!! json_encode($inscriptionsMois->values()) !!},
                borderColor: '#2d6a9f',
                backgroundColor: 'rgba(45,106,159,0.1)',
                fill: true
            }]
        },
        options: { 
            scales: { x: { ticks: { color: textColor } }, y: { ticks: { color: textColor }, beginAtZero: true } },
            plugins: { legend: { labels: { color: textColor } } }
        }
    });
</script>
@endsection