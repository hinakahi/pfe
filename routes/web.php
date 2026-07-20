<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\AnnonceController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Etudiante\HebergementController;
use App\Http\Controllers\Etudiante\MaintenanceController;
use App\Http\Controllers\Etudiante\FoyerController;
use App\Http\Controllers\Etudiante\ReclamationController;
use App\Http\Controllers\ResponsableHebergement\ChambreController;
use App\Http\Controllers\ResponsableHebergement\RenouvellementController;
use App\Http\Controllers\ResponsableHebergement\ChangementController;
use App\Http\Controllers\Technicien\DemandeMaintController;
use App\Http\Controllers\Technicien\IncidentController;
use App\Http\Controllers\ResponsableFoyer\CatalogueController;
use App\Http\Controllers\ResponsableFoyer\ReservationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\Etudiante\ProfileController;
use App\Http\Controllers\Technicien\StockController;
use App\Http\Controllers\PublicController;

// ─── Page d'accueil ──────────────────────────────────────────
Route::get('/accueil', function () {
    return view('welcome');
})->name('welcome');

// ─── Authentification ─────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('welcome');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendReset'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ─── Admin ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('utilisateurs', UtilisateurController::class);
    // Routes supplémentaires pour l'archivage
     Route::patch('utilisateurs/{id}/restore', [UtilisateurController::class, 'restore'])
    ->name('utilisateurs.restore');

   Route::delete('utilisateurs/{id}/force-delete', [UtilisateurController::class, 'forceDelete'])
    ->name('utilisateurs.force-delete');

    Route::post('utilisateurs/bulk-archive', [UtilisateurController::class, 'bulkArchive'])
    ->name('utilisateurs.bulk-archive');

    Route::post('utilisateurs/bulk-restore', [UtilisateurController::class, 'bulkRestore'])
       ->name('utilisateurs.bulk-restore');
    Route::resource('annonces', AnnonceController::class);
    Route::resource('periodes', PeriodeController::class);
    Route::resource('matricules', \App\Http\Controllers\Admin\MatriculeController::class);
    Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques');
    Route::get('/statistiques/export-pdf', [StatistiqueController::class, 'exportPdf'])->name('statistiques.pdf');
    Route::get('/statistiques/export-excel', [StatistiqueController::class, 'exportExcel'])->name('statistiques.excel');
    Route::patch('periodes/{periode}/toggle', [PeriodeController::class, 'toggle'])->name('periodes.toggle');
    Route::get('reclamations', [ReclamationController::class, 'indexAdmin'])->name('reclamations.index');
    Route::get('reclamations/{reclamation}', [ReclamationController::class, 'showAdmin'])->name('reclamations.show');
    Route::patch('reclamations/{reclamation}', [ReclamationController::class, 'updateAdmin'])->name('reclamations.update');

    // 
    Route::get('messages', [MessageController::class, 'index'])->name('messages');
    Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
   Route::get('/notifications', function () {
    $notifications = auth()->user()->notifications()->paginate(15);
    return view('admin.notifications.index', compact('notifications'));
})->name('notifications');

Route::post('/notifications/{id}/read', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return redirect($notification->data['url'] ?? url()->previous());
})->name('notifications.read');
});
// ─── Étudiante ────────────────────────────────────────────────
Route::middleware(['auth', 'role:etudiante'])->prefix('etudiante')->name('etudiante.')->group(function () {

    Route::get('/dashboard', [HebergementController::class, 'dashboard'])->name('dashboard');

    // ─── Notifications ───────────────────────────────────────
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('etudiante.notifications.index', compact('notifications'));
    })->name('notifications');

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url'] ?? url()->previous());
    })->name('notifications.read');

    // ─── Annonces ────────────────────────────────────────────
    Route::get('/annonces', function () {
        $annoncesUrgentes = \App\Models\Annonce::with('user')
            ->where('urgence', 'urgent')
            ->where('publiee', true)
            ->where(function ($q) {
                $q->where('destinataire', 'etudiantes')
                  ->orWhere('destinataire', 'tous');
            })
            ->latest()
            ->get();

        $query = \App\Models\Annonce::where(function ($q) {
            $q->where('destinataire', 'etudiantes')
              ->orWhere('destinataire', 'tous');
        })->where('publiee', true);

        if (request('auteur')) {
            $query->whereHas('user', function ($q) {
                $q->where('role', request('auteur'));
            });
        }

        if (request('search')) {
            $query->where('titre', 'like', '%' . request('search') . '%');
        }

        if (request('tri') == 'ancien') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $annonces = $query->paginate(10)->withQueryString();

        return view('etudiante.annonces.index', compact('annonces', 'annoncesUrgentes'));
    })->name('annonces');

    // ─── Hébergement ─────────────────────────────────────────
    Route::prefix('hebergement')->name('hebergement.')->group(function () {
        Route::get('/', [HebergementController::class, 'index'])->name('index');
        Route::get('/renouvellement', [HebergementController::class, 'showRenouvellement'])->name('renouvellement');
        Route::post('/renouvellement', [HebergementController::class, 'renouveler'])->name('renouveller');
        Route::get('/demandes', [HebergementController::class, 'statut'])->name('demandes');
    });
    Route::get('/hebergement/changement', [HebergementController::class, 'showChangement'])->name('changement');
    Route::post('/hebergement/changement', [HebergementController::class, 'demanderChangement'])->name('changement.store');
    Route::put('/hebergement/changement/{demande}/modifier', [HebergementController::class, 'modifierChangement'])
    ->name('changement.modifier');
Route::delete('/hebergement/changement/{demande}/annuler', [HebergementController::class, 'annulerChangement'])
    ->name('changement.annuler');
    Route::put('/hebergement/renouvellement/{demande}/modifier', [HebergementController::class, 'modifierRenouvellement'])
    ->name('hebergement.renouvellement.modifier');
   
  // ─── Etudiante Foyer ──────────────────────────────────────
  Route::prefix('foyer')->name('foyer.')->group(function () {
    Route::get('/', [FoyerController::class, 'dashboard'])->name('dashboard');
    Route::get('/articles', [FoyerController::class, 'categories'])->name('articles');
    Route::get('/articles/{categorie}', [FoyerController::class, 'index'])->name('catalogue');
    Route::get('/reservations', [FoyerController::class, 'reservations'])->name('reservations');
    Route::get('/promotions', [FoyerController::class, 'promotions'])->name('promotions');
    Route::post('/reserver/{article}', [FoyerController::class, 'reserver'])->name('reserver');
    Route::post('/commander', [FoyerController::class, 'confirmer'])->name('confirmer');
    Route::delete('/annuler/{reservation}', [FoyerController::class, 'annuler'])->name('annuler');
});

    // ─── Maintenance ─────────────────────────────────────────
Route::prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/',                      [MaintenanceController::class, 'index'])->name('index');
    Route::get('/signaler',              [MaintenanceController::class, 'create'])->name('signaler');
    Route::post('/',                     [MaintenanceController::class, 'store'])->name('store');
    Route::get('/{maintenance}',         [MaintenanceController::class, 'show'])->name('show');   // ← ajouté
    Route::get('/{maintenance}/edit',    [MaintenanceController::class, 'edit'])->name('edit');
    Route::put('/{maintenance}',         [MaintenanceController::class, 'update'])->name('update');
    Route::delete('/{maintenance}',      [MaintenanceController::class, 'destroy'])->name('destroy');
});
    // ─── Réclamations ────────────────────────────────────────
    
   Route::resource('reclamations', ReclamationController::class)
    ->names('reclamations')
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    // ─── Profil ──────────────────────────────────────────────
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/password', [ProfileController::class, 'editPassword'])->name('edit-password');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
});

// ─── Responsable Hébergement ──────────────────────────────────
Route::prefix('hebergement')->middleware(['auth', 'role:resp_hebergement'])->group(function () {
    Route::get('/dashboard', [ChambreController::class, 'dashboard'])->name('hebergement.dashboard');
    Route::get('/chambres/vides', [ChambreController::class, 'chambresVides'])->name('hebergement.chambres.vides');
    Route::post('/chambres/publier', [ChambreController::class, 'publierVides'])->name('hebergement.chambres.publier');
    Route::get('/chambres/import', [ChambreController::class, 'importForm'])->name('hebergement.chambres.import');
    Route::post('/chambres/import', [ChambreController::class, 'import'])->name('hebergement.chambres.import.store');
    Route::resource('chambres', ChambreController::class)->names('hebergement.chambres');
    Route::get('/renouvellements', [RenouvellementController::class, 'index'])->name('hebergement.renouvellements.index');
    Route::post('/renouvellements/{demande}/valider', [RenouvellementController::class, 'valider'])->name('hebergement.renouvellements.valider');
    Route::put('/hebergement/renouvellements/{demande}/modifier-pec', [RenouvellementController::class, 'modifierPriseEnCharge'])
    ->name('hebergement.renouvellements.modifier-pec');
    Route::post('/renouvellements/{demande}/refuser', [RenouvellementController::class, 'refuser'])->name('hebergement.renouvellements.refuser');

    Route::get('/changements', [ChangementController::class, 'index'])->name('hebergement.changements.index');
    Route::post('/changements/{demande}/accepter', [ChangementController::class, 'accepter'])->name('hebergement.changements.accepter');
    Route::put('/hebergement/changements/{demande}/modifier-pec', [ChangementController::class, 'modifierPriseEnCharge'])
    ->name('hebergement.changements.modifier-pec');
    Route::post('/changements/{demande}/refuser', [ChangementController::class, 'refuser'])->name('hebergement.changements.refuser');
    Route::get('/annonces', [\App\Http\Controllers\ResponsableHebergement\AnnonceController::class, 'index'])->name('hebergement.annonces.index');
     Route::patch('chambres/{chambre}/depublier', [ChambreController::class, 'depublier'])
     ->name('hebergement.chambres.depublier');

     Route::get('/notifications', function () {
    $notifications = auth()->user()->notifications()->paginate(15);
    return view('hebergement.notifications.index', compact('notifications'));
})->name('hebergement.notifications');

Route::post('/notifications/{id}/read', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return redirect($notification->data['url'] ?? url()->previous());
})->name('notifications.read');

});

// ─── Technicien ───────────────────────────────────────────────
Route::prefix('technicien')->middleware(['auth', 'role:technicien'])->group(function () {
    Route::get('/dashboard', [DemandeMaintController::class, 'dashboard'])->name('technicien.dashboard');
    Route::get('/demandes', [DemandeMaintController::class, 'index'])->name('technicien.demandes');
    Route::get('/demandes/{maintenance}', [DemandeMaintController::class, 'show'])->name('technicien.demandes.show');
    Route::post('/demandes/{maintenance}/traiter', [DemandeMaintController::class, 'traiter'])->name('technicien.demandes.traiter');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('technicien.incidents.store');

    Route::resource('stock', StockController::class)->names('technicien.stock');
    Route::get('/stock/{stock}/historique', [StockController::class, 'historique'])->name('technicien.stock.historique');
    Route::get('/stock-historique', [StockController::class, 'historiqueGlobal'])->name('technicien.stock.historique-global');
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('technicien.notifications.index', compact('notifications'));
    })->name('technicien.notifications');

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url'] ?? url()->previous());
    })->name('technicien.notifications.read');

    Route::get('/annonces', function () {
        $annonces = \App\Models\Annonce::with('user')
            ->where(function ($q) {
                $q->where('destinataire', 'staff')
                  ->orWhere('destinataire', 'tous');
            })
            ->where('publiee', true)
            ->latest()
            ->paginate(10);

        $annoncesUrgentes = \App\Models\Annonce::with('user')
            ->where(function ($q) {
                $q->where('destinataire', 'staff')
                  ->orWhere('destinataire', 'tous');
            })
            ->where('publiee', true)
            ->where('urgence', 'urgent')
            ->latest()
            ->take(5)
            ->get();

        return view('technicien.annonces.index', compact('annonces', 'annoncesUrgentes'));
    })->name('technicien.annonces.index');

  Route::get('/stock-historique/pdf', [StockController::class, 'historiqueGlobalPdf'])
    ->name('technicien.stock.historique-global.pdf');
});

// ─── Responsable Foyer ────────────────────────────────────────
Route::prefix('foyer')->middleware(['auth', 'role:resp_foyer'])->group(function () {
    Route::get('/dashboard', [CatalogueController::class, 'dashboard'])->name('foyer.dashboard');
    Route::post('/catalogue/{catalogue}/modifier', [CatalogueController::class, 'update'])->name('foyer.catalogue.modifier');
    Route::resource('catalogue', CatalogueController::class)->names('foyer.catalogue');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('foyer.reservations');
    Route::post('/reservations/{reservation}/valider', [ReservationController::class, 'valider'])->name('foyer.reservations.valider');
    Route::post('/reservations/{reservation}/refuser', [ReservationController::class, 'refuser'])->name('foyer.reservations.refuser');
    Route::post('/catalogue/{article}/update-promo', [CatalogueController::class, 'updatePromo'])->name('foyer.catalogue.updatePromo');
    Route::get('/annonces', [\App\Http\Controllers\ResponsableFoyer\AnnonceController::class, 'index'])->name('foyer.annonces.index');
    Route::get('/annonces/create', [\App\Http\Controllers\ResponsableFoyer\AnnonceController::class, 'create'])->name('foyer.annonces.create');
    Route::post('/annonces', [\App\Http\Controllers\ResponsableFoyer\AnnonceController::class, 'store'])->name('foyer.annonces.store');
    Route::delete('/annonces/{annonce}', [\App\Http\Controllers\ResponsableFoyer\AnnonceController::class, 'destroy'])->name('foyer.annonces.destroy');
    Route::get('/annonces/{annonce}/edit', [\App\Http\Controllers\ResponsableFoyer\AnnonceController::class, 'edit'])->name('foyer.annonces.edit');
    Route::post('/annonces/{annonce}/update', [\App\Http\Controllers\ResponsableFoyer\AnnonceController::class, 'update'])->name('foyer.annonces.update');
    Route::post('reservations/{reservation}/recuperee', [ReservationController::class, 'recuperee'])->name('foyer.reservations.recuperee');
    Route::post('foyer/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('foyer.notifications.markAllRead')->middleware('auth');

    // ─── Notifications ───────────────────────────────────────
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('foyer.notifications.index', compact('notifications'));
    })->name('foyer.notifications');

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url'] ?? url()->previous());
    })->name('foyer.notifications.read');
});

// ─── Inscription ─────────────────────────────────────────────
Route::get('/inscription', [InscriptionController::class, 'create'])->name('inscription');
Route::post('/inscription', [InscriptionController::class, 'store'])->name('inscription.store');

// ─── Email Test ──────────────────────────────────────────────
Route::get('/test-mail', function () {
    Mail::raw('Si tu reçois ce mail, ta configuration Laravel est parfaite !', function ($message) {
        $message->to('adelkahina62@gmail.com')
                ->subject('Test Laravel SMTP');
    });
    return 'Email envoyé avec succès !';
});
// ─── visiteur ──────────────────────────────────────────────
Route::post('/contact', [PublicController::class, 'envoyerMessage'])->name('contact.send');