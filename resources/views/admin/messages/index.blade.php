@extends('layouts.app')
@section('page-title', 'Messages reçus')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold"> Boîte de réception</h4>
        <p class="mb-0 small" style="opacity:0.6;">Messages envoyés depuis la page publique</p>
    </div>
    <span class="badge rounded-pill px-3 py-2 fs-6"
          style="background: linear-gradient(135deg,#1a3c5e,#2d6a9f); color:#fff;">
        {{ $messages->count() }} message(s)
    </span>
</div>

@if($messages->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:4rem;">📭</div>
        <h5 class="text-muted mt-3">Aucun message reçu</h5>
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach($messages as $msg)
        <div class="card border-0 shadow-sm"
             style="border-left: 4px solid {{ $msg->lu ? '#dee2e6' : '#2d6a9f' }} !important;
                    border-radius:12px; overflow:hidden;">
            <div class="card-body d-flex align-items-center gap-3 py-3">

                {{-- Avatar initiale --}}
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:46px;height:46px;
                            background: {{ $msg->lu ? '#e9ecef' : 'linear-gradient(135deg,#1a3c5e,#2d6a9f)' }};
                            color: {{ $msg->lu ? '#6c757d' : '#fff' }};
                            font-weight:700; font-size:1.1rem;">
                    {{ strtoupper(substr($msg->nom ?? $msg->email, 0, 1)) }}
                </div>

                {{-- Contenu --}}
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold {{ $msg->lu ? 'text-muted' : '' }}">
                            {{ $msg->nom ?? '—' }}
                        </span>
                        @if(!$msg->lu)
                            <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Nouveau</span>
                        @endif
                        <span class="badge bg-light text-secondary border" style="font-size:0.7rem;">
                            {{ $msg->objet }}
                        </span>
                    </div>
                    <div class="small text-truncate" style="opacity:0.7;">
    {{ $msg->email }} · {{ $msg->created_at->format('d/m/Y à H:i') }}
</div>
<div class="small mt-1 text-truncate" style="opacity:0.6;">
    {{ Str::limit($msg->message, 80) }}
</div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('admin.messages.show', $msg) }}"
                       class="btn btn-sm px-3"
                       style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f);color:#fff;border-radius:8px;">
                        <i class="bi bi-eye me-1"></i> Voir
                    </a>
                    <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}"
                          onsubmit="return confirm('Supprimer ce message ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger px-3" style="border-radius:8px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection