@extends('layouts.app')

@section('page-title', 'Notifications')

@section('sidebar')
    @include('admin.partials._sidebar')
@endsection

@section('content')
<div class="container" style="margin-top: 2rem;">
    <h5 class="fw-bold mb-4"><i class="bi bi-bell"></i> Mes Notifications</h5>

    @if($notifications->count() > 0)
        <div class="row g-3">
            @foreach($notifications as $notif)
            <div class="col-md-12">
                <div class="card border-0 shadow-sm" style="border-left: 4px solid {{ $notif->read_at ? '#ccc' : '#0d6efd' }} !important; border-radius: 10px; opacity: {{ $notif->read_at ? '0.7' : '1' }};">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            @if(!$notif->read_at)
                                <span class="badge bg-primary mb-1">Nouveau</span>
                            @endif
                            <h6 class="fw-bold mb-1">{{ $notif->data['title'] ?? 'Notification' }}</h6>
                            <p class="text-muted mb-1" style="font-size:0.9rem;">{{ Str::limit($notif->data['message'] ?? '', 120) }}</p>
                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                        @if(!$notif->read_at)
                        <form method="POST" action="{{ route('admin.notifications.read', $notif->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary ms-3">
                                <i class="bi bi-check2"></i> Lu
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $notifications->links() }}</div>
    @else
        <div class="card text-center shadow-sm border-0">
            <div class="card-body py-5">
                <i class="bi bi-bell-slash" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Aucune notification</p>
            </div>
        </div>
    @endif
</div>
@endsection