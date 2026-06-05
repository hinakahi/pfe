<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\AnnonceController;
use App\Http\Controllers\Admin\PeriodeController;
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
use App\Http\Controllers\ResponsableFoyer\PromotionController;
use App\Http\Controllers\InscriptionController;

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
    
});

// ─── Étudiante ────────────────────────────────────────────────
Route::prefix('etudiante')->middleware(['auth', 'role:etudiante'])->group(function () {
    Route::get('/dashboard', [HebergementController::class, 'dashboard'])->name('etudiante.dashboard');
    Route::get('/hebergement', [HebergementController::class, 'index'])->name('etudiante.hebergement');
    Route::post('/hebergement/renouvellement', [HebergementController::class, 'renouveler'])->name('etudiante.renouvellement.store');
    Route::get('/hebergement/changement', [HebergementController::class, 'showChangement'])->name('etudiante.changement');
    Route::post('/hebergement/changement', [HebergementController::class, 'demanderChangement'])->name('etudiante.changement.store');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('etudiante.maintenance');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('etudiante.maintenance.store');
    Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy'])->name('etudiante.maintenance.destroy');
    Route::get('/foyer', [FoyerController::class, 'index'])->name('etudiante.foyer');
    Route::post('/foyer/reserver/{article}', [FoyerController::class, 'reserver'])->name('etudiante.foyer.reserver');
    Route::get('/foyer/reservations', [FoyerController::class, 'mesReservations'])->name('etudiante.foyer.reservations');
    Route::delete('/foyer/reservations/{reservation}', [FoyerController::class, 'annuler'])->name('etudiante.foyer.annuler');
    Route::resource('reclamations', ReclamationController::class)->names('etudiante.reclamations');
});

// ─── Responsable Hébergement ──────────────────────────────────
Route::prefix('hebergement')->middleware(['auth', 'role:resp_hebergement'])->group(function () {
    Route::get('/dashboard', [ChambreController::class, 'dashboard'])->name('hebergement.dashboard');
    Route::resource('chambres', ChambreController::class)->names('hebergement.chambres');
    Route::get('/renouvellements', [RenouvellementController::class, 'index'])->name('hebergement.renouvellements');
    Route::post('/renouvellements/{demande}/valider', [RenouvellementController::class, 'valider'])->name('hebergement.renouvellements.valider');
    Route::post('/renouvellements/{demande}/refuser', [RenouvellementController::class, 'refuser'])->name('hebergement.renouvellements.refuser');
    Route::get('/changements', [ChangementController::class, 'index'])->name('hebergement.changements');
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
});

// ─── Responsable Foyer ────────────────────────────────────────
Route::prefix('foyer')->middleware(['auth', 'role:resp_foyer'])->group(function () {
    Route::get('/dashboard', [CatalogueController::class, 'dashboard'])->name('foyer.dashboard');
    
    Route::post('/catalogue/{catalogue}/modifier', [CatalogueController::class, 'update'])
        ->name('foyer.catalogue.modifier');
    Route::resource('catalogue', CatalogueController::class)->names('foyer.catalogue');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('foyer.reservations');
    Route::post('/reservations/{reservation}/valider', [ReservationController::class, 'valider'])->name('foyer.reservations.valider');
    Route::post('/reservations/{reservation}/refuser', [ReservationController::class, 'refuser'])->name('foyer.reservations.refuser');
    Route::resource('promotions', PromotionController::class)->names('foyer.promotions');
    Route::post('/catalogue/{article}/update-promo', [CatalogueController::class, 'updatePromo'])
        ->name('foyer.catalogue.updatePromo');
});
// ─── inscription ────────────────────────────────────────
Route::get('/inscription', [InscriptionController::class, 'create'])->name('inscription');
Route::post('/inscription', [InscriptionController::class, 'store'])->name('inscription.store');
// ─── email────────────────────────────────────────
Route::get('/test-mail', function () {
    Mail::raw('Si tu reçois ce mail, ta configuration Laravel est parfaite !', function ($message) {
        $message->to('adelkahina62@gmail.com')
                ->subject('Test Laravel SMTP');
    });
    return 'Email envoyé avec succès !';
});

// ─── statistiques────────────────────────────────────────
Route::get('/admin/statistiques', 
[App\Http\Controllers\Admin\DashboardController::class, 'statistiques'])->name('admin.statistiques');