@extends('layouts.app')

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
@section('content')
<div class="container-fluid">

    {{-- Barre filtre + export --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        
        {{-- Filtre période --}}
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted me-2">Période :</span>
            <a href="{{ route('admin.statistiques', ['periode' => 'semaine']) }}"
               class="btn btn-sm {{ $periode === 'semaine' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Cette semaine
            </a>
            <a href="{{ route('admin.statistiques', ['periode' => 'mois']) }}"
               class="btn btn-sm {{ $periode === 'mois' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Ce mois
            </a>
            <a href="{{ route('admin.statistiques', ['periode' => 'semestre']) }}"
               class="btn btn-sm {{ $periode === 'semestre' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Ce semestre
            </a>
        </div>

        {{-- Boutons export --}}
        <div class="d-flex gap-2">
            <a href="{{ route('admin.statistiques.pdf', ['periode' => $periode]) }}"
               class="btn btn-sm btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Exporter PDF
            </a>
            <a href="{{ route('admin.statistiques.excel', ['periode' => $periode]) }}"
               class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Exporter Excel
            </a>
        </div>

    </div>


    {{-- reste du contenu... --}}
<div class="container-fluid">

    <h5 class="mb-4 fw-bold"><i class="bi bi-tools me-2"></i>Statistiques Maintenance</h5>

    <div class="row g-4">

        {{-- 1. Pannes par type --}}
        <div class="col-md-6">
            <div class="card p-3 shadow-sm h-100">
                <h6 class="card-title fw-semibold mb-3">
                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Taux de pannes par type
                </h6>
                <canvas id="pannesTypeChart" height="250"></canvas>
            </div>
        </div>

        {{-- 2. Délai moyen --}}
        <div class="col-md-6">
            <div class="card p-3 shadow-sm h-100">
                <h6 class="card-title fw-semibold mb-3">
                    <i class="bi bi-clock-history me-2 text-warning"></i>Délai moyen de résolution (heures)
                </h6>
                <canvas id="delaiChart" height="250"></canvas>
            </div>
        </div>

        {{-- 3. Chambres problématiques --}}
        <div class="col-md-6">
            <div class="card p-3 shadow-sm h-100">
                <h6 class="card-title fw-semibold mb-3">
                    <i class="bi bi-door-closed-fill me-2 text-danger"></i>Chambres les plus problématiques (Top 5)
                </h6>
                <canvas id="chambresChart" height="250"></canvas>
            </div>
        </div>

        {{-- 4. Évolution mensuelle --}}
        <div class="col-md-6">
            <div class="card p-3 shadow-sm h-100">
                <h6 class="card-title fw-semibold mb-3">
                    <i class="bi bi-graph-up me-2 text-success"></i>Évolution des pannes (12 mois)
                </h6>
                <canvas id="pannesMoisChart" height="250"></canvas>
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
    const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';

    const defaultOptions = {
        plugins: { legend: { labels: { color: textColor } } },
        scales: {
            x: { ticks: { color: textColor }, grid: { color: gridColor } },
            y: { ticks: { color: textColor }, grid: { color: gridColor }, beginAtZero: true }
        }
    };

    // 1. Pannes par type
    new Chart(document.getElementById('pannesTypeChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($pannesParType)) !!},
            datasets: [{
                label: 'Nombre de pannes',
                data: {!! json_encode(array_values($pannesParType)) !!},
                backgroundColor: [
                    '#f59e0b','#3b82f6','#8b5cf6',
                    '#ef4444','#10b981','#6366f1'
                ],
                borderRadius: 6,
            }]
        },
        options: {
            ...defaultOptions,
            plugins: { legend: { display: false } }
        }
    });

    // 2. Délai moyen
    new Chart(document.getElementById('delaiChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($delaiParType)) !!},
            datasets: [{
                label: 'Heures moyennes',
                data: {!! json_encode(array_values($delaiParType)) !!},
                backgroundColor: 'rgba(245,158,11,0.7)',
                borderColor: '#f59e0b',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            ...defaultOptions,
            plugins: { legend: { display: false } }
        }
    });

    // 3. Chambres problématiques
    new Chart(document.getElementById('chambresChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chambresProblematiques->pluck('chambre')) !!},
            datasets: [{
                data: {!! json_encode($chambresProblematiques->pluck('total')) !!},
                backgroundColor: ['#ef4444','#f59e0b','#3b82f6','#10b981','#8b5cf6'],
            }]
        },
        options: {
            plugins: { legend: { labels: { color: textColor } } }
        }
    });

    // 4. Évolution mensuelle
    new Chart(document.getElementById('pannesMoisChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($pannesParMois->keys()) !!},
            datasets: [{
                label: 'Pannes',
                data: {!! json_encode($pannesParMois->values()) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#3b82f6',
            }]
        },
        options: defaultOptions
    });
</script>
@endsection