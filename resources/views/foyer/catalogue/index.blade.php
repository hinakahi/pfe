@extends('layouts.app')

@section('title', 'Catalogue des Articles')

@section('sidebar')
    @include('foyer.partials._sidebar')
@endsection

@section('page-title', 'Catalogue des Articles')

@section('content')

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">
                {{ $articles->count() }} article(s) au total
            </span>

            <a href="{{ route('foyer.catalogue.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>
                Ajouter un article
            </a>
        </div>

        {{-- Recherche + Filtre --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text"
                       id="searchInput"
                       class="form-control"
                       placeholder="Rechercher un article...">
            </div>

            <div class="col-md-3">
                <select id="categorieFilter" class="form-select">
                    <option value="">Toutes les catégories</option>
                    <option value="fastfood">Fast Food</option>
                    <option value="magasin">Magasin</option>
                    <option value="cafeteria">Cafétéria</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Prix (DA)</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($articles as $article)

                    <tr class="article-row"
                        data-nom="{{ strtolower($article->nom_article) }}"
                        data-categorie="{{ strtolower($article->categorie) }}">

                        {{-- Photo --}}
                        <td>
                            @if($article->photo)
                                <img src="{{ asset('storage/' . $article->photo) }}"
                                     alt="{{ $article->nom_article }}"
                                     style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                            @else
                                <div style="width:60px;height:60px;
                                            background:#f0f0f0;
                                            border-radius:8px;
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>

                        {{-- Nom --}}
                        <td class="fw-semibold">
                            {{ $article->nom_article }}
                        </td>

                        {{-- Catégorie --}}
                        <td>
                            @if($article->categorie == 'fastfood')
                                <span class="badge bg-warning text-dark">
                                    Fast Food
                                </span>

                            @elseif($article->categorie == 'magasin')
                                <span class="badge bg-primary">
                                    Magasin
                                </span>

                            @elseif($article->categorie == 'cafeteria')
                                <span class="badge bg-success">
                                    Cafétéria
                                </span>

                            @else
                                <span class="badge bg-secondary">
                                    Non définie
                                </span>
                            @endif
                        </td>

                        {{-- Description --}}
                        <td class="text-muted">
                            {{ $article->description ? Str::limit($article->description, 50) : '—' }}
                        </td>

                        {{-- Prix --}}
                        <td>
                            {{ number_format($article->prix, 2) }}
                        </td>

                        {{-- Stock --}}
                        <td>
                            @if($article->stock <= 5)
                                <span class="badge bg-danger">
                                    {{ $article->stock }}
                                </span>

                            @elseif($article->stock <= 10)
                                <span class="badge bg-warning text-dark">
                                    {{ $article->stock }}
                                </span>

                            @else
                                <span class="fw-semibold text-success">
                                    {{ $article->stock }}
                                </span>
                            @endif
                        </td>

                        {{-- Statut --}}
                        <td>
                            @if($article->disponible)
                                <span class="badge bg-success">
                                    Disponible
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    Indisponible
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <a href="{{ route('foyer.catalogue.edit', $article) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form method="POST"
                                  action="{{ route('foyer.catalogue.destroy', $article) }}"
                                  style="display:inline;"
                                  onsubmit="return confirm('Supprimer cet article ?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-box-seam fs-3 d-block mb-2"></i>
                            Aucun article dans le catalogue.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    const categorieFilter = document.getElementById('categorieFilter');

    function filtrer() {

        const recherche = searchInput.value.toLowerCase();
        const categorie = categorieFilter.value.toLowerCase();

        document.querySelectorAll('.article-row').forEach(row => {

            const nom = row.dataset.nom;
            const cat = row.dataset.categorie;

            const matchNom = nom.includes(recherche);
            const matchCat = categorie === '' || cat === categorie;

            row.style.display = (matchNom && matchCat)
                ? ''
                : 'none';
        });
    }

    searchInput.addEventListener('keyup', filtrer);
    categorieFilter.addEventListener('change', filtrer);

});
</script>
@endsection