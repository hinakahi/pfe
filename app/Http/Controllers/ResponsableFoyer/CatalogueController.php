<?php

namespace App\Http\Controllers\ResponsableFoyer;

use App\Http\Controllers\Controller;
use App\Models\ArticleFoyer;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_articles' => ArticleFoyer::count(),
            'articles_disponibles' => ArticleFoyer::where('disponible', true)->count(),
            'reservations' => Reservation::where('statut', 'en_attente')->count(),
            'stock_faible' => ArticleFoyer::where('disponible', true)->where('stock', '<=', 5)->count(),
            
        ];

        $articles = ArticleFoyer::latest()->take(5)->get();

        return view('foyer.dashboard', compact('stats', 'articles'));
    }

public function index(Request $request)
{
    $query = ArticleFoyer::latest();

    if ($request->filtre === 'stock_faible') {
        $query->where('stock', '<=', 5);
    } elseif ($request->filtre === 'promo') {
        $query->where('promo_active', true);
    } elseif ($request->filtre === 'peremption') {
        $query->where('date_peremption', '<=', now()->addDays(7))
              ->whereNotNull('date_peremption');
    }

    $articles = $query->get();
    $filtre = $request->filtre;

    return view('foyer.catalogue.index', compact('articles', 'filtre'));
}

    public function create()
    {
        return view('foyer.catalogue.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_article' => 'required|string|max:191',
            'categorie'   => 'required|in:fastfood,magasin,cafeteria',
            'description' => 'nullable|string',
            'prix'        => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'disponible'  => 'boolean',
        ]);

        $photo = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('articles', 'public');
        }

        ArticleFoyer::create([
            'resp_foyer_id' => auth()->id(),
            'nom_article'   => $request->nom_article,
            'categorie'     => $request->categorie,
            'description'   => $request->description,
            'prix'          => $request->prix,
            'stock'         => $request->stock,
            'photo'         => $photo,
            'disponible'    => $request->boolean('disponible', true),
        ]);

        return redirect()
            ->route('foyer.catalogue.index')
            ->with('success', 'Article ajouté avec succès.');
    }

    public function edit(ArticleFoyer $catalogue)
    {
        return view('foyer.catalogue.edit', compact('catalogue'));
    }

public function update(Request $request, ArticleFoyer $catalogue)
{
  
    $request->validate([
        'nom_article'     => 'required|string|max:191',
        'categorie'       => 'required|in:fastfood,magasin,cafeteria',
        'description'     => 'nullable|string',
        'prix'            => 'required|numeric|min:0',
        'stock'           => 'required|integer|min:0',
        'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'date_peremption' => 'nullable|date',
        'promo_active'    => 'nullable|boolean',
        'prix_promo'      => 'nullable|numeric|min:0',
        'promo_remarque'  => 'nullable|string|max:255',
        'promo_date_fin'  => 'nullable|date',
    ]);

    $data = [
        'nom_article'     => $request->nom_article,
        'categorie'       => $request->categorie,
        'description'     => $request->description,
        'prix'            => $request->prix,
        'stock'           => $request->stock,
        'disponible'      => $request->boolean('disponible'),
        'date_peremption' => $request->date_peremption ?: null,
        'promo_active'    => $request->boolean('promo_active'),
        'prix_promo'      => $request->prix_promo,
        'promo_remarque'  => $request->promo_remarque,
        'promo_date_fin'  => $request->promo_date_fin ?: null,
    ];

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('articles', 'public');
    }

    $catalogue->update($data);

    return redirect()
        ->route('foyer.catalogue.index')
        ->with('success', 'Article modifié avec succès.');
}

    public function destroy(ArticleFoyer $catalogue)
    {
        $catalogue->delete();

        return redirect()
            ->route('foyer.catalogue.index')
            ->with('success', 'Article supprimé avec succès.');
    }
    public function updatePromo(Request $request, ArticleFoyer $article)
{
    $request->validate([
        'prix_promo'     => 'nullable|numeric|min:0',
        'promo_remarque' => 'nullable|string|max:255',
        'promo_date_fin' => 'nullable|date',
    ]);

    $article->update([
        'promo_active'   => $request->boolean('promo_active'),  // false si absent
        'prix_promo'     => $request->prix_promo,
        'promo_remarque' => $request->promo_remarque,
    ]);

    return redirect()->route('foyer.catalogue.index')
        ->with('success', '✅ Promotion mise à jour avec succès');
}
}