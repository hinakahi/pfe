<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\AnnonceController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\StatistiqueController;
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
    Route::resource('annonces', AnnonceController::class);
    Route::resource('periodes', PeriodeController::class);
    Route::resource('matricules', \App\Http\Controllers\Admin\MatriculeController::class);
    Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques'); // ✅ corrigé
    Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques');
Route::get('/statistiques/export-pdf', [StatistiqueController::class, 'exportPdf'])->name('statistiques.pdf'); // ← ajouter
Route::get('/statistiques/export-excel', [StatistiqueController::class, 'exportExcel'])->name('statistiques.excel'); // ← ajouter
});

// ─── Étudiante ────────────────────────────────────────────────
Route::middleware(['auth', 'role:etudiante'])->prefix('etudiante')->name('etudiante.')->group(function () {

    Route::get('/dashboard', [HebergementController::class, 'dashboard'])->name('dashboard');

    // Notifications
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('etudiante.notifications.index', compact('notifications'));
    })->name('notifications');

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    })->name('notifications.read');

   Route::get('/annonces', function () {

    // ✅ Ajouter ceci
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
        $query->whereHas('user', function($q) {
            $q->where('role', request('auteur'));
        });
    }

    if (request('search')) {
        $query->where('titre', 'like', '%'.request('search').'%');
    }
        if (request('tri') == 'ancien') {
        $query->oldest();
    } else {
        $query->latest();
    }

    $annonces = $query->latest()->paginate(10)->withQueryString();

    // annoncesUrgentes 
    return view('etudiante.annonces.index', compact('annonces', 'annoncesUrgentes'));

})->name('annonces');

    // Hébergement
    Route::prefix('hebergement')->name('hebergement.')->group(function () {
        Route::get('/renouvellement', [HebergementController::class, 'index'])->name('renouvellement');
        Route::post('/renouvellement', [HebergementController::class, 'renouveler'])->name('renouveller');
        Route::get('/demandes', [HebergementController::class, 'statut'])->name('demandes');
    });
    Route::get('/hebergement/changement', [HebergementController::class, 'showChangement'])->name('changement');
    Route::post('/hebergement/changement', [HebergementController::class, 'demanderChangement'])->name('changement.store');

    // Foyer
    // Foyer
Route::get('/foyer', [FoyerController::class, 'dashboard'])->name('foyer');  // ✅ DASHBOARD
Route::post('/foyer/reserver/{article}', [FoyerController::class, 'reserver'])->name('foyer.reserver');
Route::get('/foyer/reservations', [FoyerController::class, 'mesReservations'])->name('foyer.reservations');
Route::delete('/foyer/reservations/{reservation}', [FoyerController::class, 'annuler'])->name('foyer.annuler');
Route::post('/foyer/confirmer', [FoyerController::class, 'confirmer'])->name('foyer.confirmer');
Route::get('/foyer/catalogue', [FoyerController::class, 'index'])->name('foyer.catalogue');  // ✅ CATALOGUE
    // Maintenance
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('/signaler', [MaintenanceController::class, 'create'])->name('signaler');
        Route::post('/', [MaintenanceController::class, 'store'])->name('store');
        Route::get('/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('edit');
        Route::put('/{maintenance}', [MaintenanceController::class, 'update'])->name('update');
        Route::delete('/{maintenance}', [MaintenanceController::class, 'destroy'])->name('destroy');
    });

    // Réclamations
    Route::resource('reclamations', ReclamationController::class)->names('reclamations');

    // Profil
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
    Route::post('/renouvellements/{demande}/refuser', [RenouvellementController::class, 'refuser'])->name('hebergement.renouvellements.refuser');
    Route::get('/changements', [ChangementController::class, 'index'])->name('hebergement.changements.index');
    Route::post('/changements/{demande}/accepter', [ChangementController::class, 'accepter'])->name('hebergement.changements.accepter');
    Route::post('/changements/{demande}/refuser', [ChangementController::class, 'refuser'])->name('hebergement.changements.refuser');
});

// ─── Technicien ───────────────────────────────────────────────
Route::prefix('technicien')->middleware(['auth', 'role:technicien'])->group(function () {
    Route::get('/dashboard', [DemandeMaintController::class, 'dashboard'])->name('technicien.dashboard');
    Route::get('/demandes', [DemandeMaintController::class, 'index'])->name('technicien.demandes');
    Route::get('/demandes/{maintenance}', [DemandeMaintController::class, 'show'])->name('technicien.demandes.show');
    Route::post('/demandes/{maintenance}/traiter', [DemandeMaintController::class, 'traiter'])->name('technicien.demandes.traiter');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('technicien.incidents.store');
    Route::resource('stock', StockController::class)->names('technicien.stock');
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
});

// ─── Inscription ────────────────────────────────────────
Route::get('/inscription', [InscriptionController::class, 'create'])->name('inscription');
Route::post('/inscription', [InscriptionController::class, 'store'])->name('inscription.store');

// ─── Email ────────────────────────────────────────
Route::get('/test-mail', function () {
    Mail::raw('Si tu reçois ce mail, ta configuration Laravel est parfaite !', function ($message) {
        $message->to('adelkahina62@gmail.com')
                ->subject('Test Laravel SMTP');
    });
    return 'Email envoyé avec succès !';
});