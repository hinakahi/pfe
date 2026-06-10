@extends('layouts.app')
@section('page-title', 'Détail du message')

@section('content')
<div class="d-flex justify-content-center">
    <div style="width:100%; max-width:680px;">
    <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">

        {{-- Header coloré --}}
        <div class="p-4 text-white"
             style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f);">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;color:#1a3c5e;font-weight:700;font-size:1.3rem;flex-shrink:0;">
                    {{ strtoupper(substr($message->nom ?? $message->email, 0, 1)) }}
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $message->nom ?? '—' }}</h5>
                    <small class="opacity-75">{{ $message->email }}</small>
                </div>
                <span class="badge bg-white ms-auto"
                      style="color:#1a3c5e;font-size:0.8rem;">
                    {{ $message->objet }}
                </span>
            </div>
        </div>

        {{-- Corps --}}
        <div class="card-body p-4">
            <div class="text-muted small mb-3">
                <i class="bi bi-clock me-1"></i>
                Reçu le {{ $message->created_at->format('d/m/Y à H:i') }}
            </div>

            <div class="p-3 rounded-3 mb-4"
                 style="background:#f8f9fa; border-left:4px solid #2d6a9f; line-height:1.8;">
                {{ $message->message }}
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.messages') }}" class="btn btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
                <a href="https://mail.google.com/mail/?view=cm&to={{ $message->email }}&su=Re: {{ urlencode($message->objet) }}"
                   target="_blank"
                   class="btn text-white" style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);border-radius:8px;">
                    <i class="bi bi-reply me-1"></i> Répondre par Gmail
                </a>
            </div>
        </div>

    </div>
</div>


@endsection