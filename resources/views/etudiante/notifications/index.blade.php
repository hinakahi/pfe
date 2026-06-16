@extends('layouts.app')

@section('page-title', 'Notifications')
@section('styles')
<style>
    [data-theme="dark"] .text-muted {
        color: var(--text-muted) !important;
    }
    [data-theme="dark"] .modal-content {
        background-color: var(--bg-card);
        color: var(--text-main);
    }
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-footer {
        border-color: #444;
    }
    [data-theme="dark"] .btn-close {
        filter: invert(1);
    }
</style>
@endsection

@section('sidebar')
    @include('etudiante.partials._sidebar')
@endsection

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-bell"></i> Notifications</h2>
            <p class="text-muted">Vos notifications personnelles</p>
        </div>
    </div>

    @if($notifications->count())

        @foreach($notifications as $notification)

        <div class="card mb-3 shadow-sm {{ !$notification->read_at ? 'border-primary' : '' }}">
            <div class="card-body d-flex justify-content-between align-items-center">

                <div>
                    @if(!$notification->read_at)
                        <span class="badge bg-danger me-2">Nouveau</span>
                    @endif

                    <strong>
                        {{ $notification->data['title'] ?? 'Notification' }}
                    </strong>

                    <br>

                    <small class="text-muted">
                        {{ $notification->created_at->diffForHumans() }}
                    </small>
                </div>

                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#notification{{ $notification->id }}">
                    Voir
                </button>

            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade"
             id="notification{{ $notification->id }}"
             tabindex="-1">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $notification->data['title'] ?? 'Notification' }}
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <p>
                            {{ $notification->data['message'] ?? '' }}
                        </p>

                        <small class="text-muted">
                            {{ $notification->created_at->format('d/m/Y H:i') }}
                        </small>

                    </div>

                    <div class="modal-footer">

                        @if(!$notification->read_at)

                            <form action="{{ route('etudiante.notifications.read', $notification->id) }}"
                                  method="POST">
                                @csrf

                                <button type="submit"
                                        class="btn btn-success">
                                    ✓ Marquer comme lu
                                </button>
                            </form>

                        @else

                            <span class="badge bg-success">
                                ✓ Déjà lue
                            </span>

                        @endif

                    </div>

                </div>
            </div>
        </div>

        @endforeach

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>

    @else

        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-bell-slash fs-1 text-muted"></i>
                <p class="mt-3">Aucune notification</p>
            </div>
        </div>

    @endif

</div>
@endsection