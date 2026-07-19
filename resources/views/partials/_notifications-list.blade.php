<div class="d-flex flex-column gap-2">
    @forelse($notifications as $n)
        @php
            $url = $n->data['url'] ?? null;
            $titre = $n->data['title'] ?? $n->data['titre'] ?? null;
            $texte = $n->data['message'] ?? '';
        @endphp
        <form method="POST" action="{{ route($readRouteName, $n->id) }}">
            @csrf
            <button type="submit"
                    class="card w-100 text-start border-0 shadow-sm {{ $n->read_at ? '' : 'bg-light' }}"
                    style="border-left: 4px solid {{ $n->read_at ? '#dee2e6' : '#0d6efd' }} !important; cursor:pointer;">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            @if($titre)
                                <div class="fw-bold mb-1">{{ $titre }}</div>
                            @endif
                            <div class="{{ $titre ? 'text-muted' : 'fw-semibold' }}" style="font-size:.9rem;">
                                {{ $texte }}
                            </div>
                        </div>
                        <span class="text-muted flex-shrink-0" style="font-size:.75rem;">
                            {{ $n->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </button>
        </form>
    @empty
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-bell-slash fs-1 opacity-25 d-block mb-2"></i>
                Aucune notification.
            </div>
        </div>
    @endforelse
</div>

@if($notifications->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $notifications->links() }}
    </div>
@endif