<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TimeEntryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Modèles pour le Dashboard
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TimeEntry;

// 🛡️ Import de notre Middleware de rôles
use App\Http\Middleware\CheckRole;

// 🚪 La porte d'entrée de l'ERP
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// 📊 Le Dashboard Dynamique
Route::get('/dashboard', function () {
    $stats = [
        'total_projects'  => Project::count(),
        'active_tickets'  => Ticket::where('status', '!=', 'completed')->count(),
        'pending_hours'   => TimeEntry::sum('duration') ?? 0, 
        'completed_tasks' => Ticket::where('status', 'completed')->count(),
    ];

    $recentActivities = Ticket::with('project')
                            ->latest('updated_at')
                            ->take(5)
                            ->get();

    return view('dashboard', compact('stats', 'recentActivities'));
})->middleware(['auth', 'verified'])->name('dashboard');

// -----------------------------------------------------------------------
// 🔒 ZONE SÉCURISÉE GLOBALE (Accès partagé selon les règles métier)
// -----------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    // ⚙️ PARAMÈTRES GLOBAUX
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    
    // 👤 Gestion du Profil (Breeze & Personnalisations Enterprise)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ✨ NOUVELLE ROUTE ENTERPRISE : Pour l'upload de l'avatar
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // 💼 MODULES PARTAGÉS (Projets & Tickets)
    Route::resource('projects', ProjectController::class);
    Route::resource('tickets', TicketController::class);

    // 🤖 ROUTE AJAX : Récupérer l'équipe d'un projet spécifique
    Route::get('/api/projects/{project}/team', [App\Http\Controllers\TicketController::class, 'getProjectTeam'])->name('api.projects.team');

    Route::post('/tickets/{ticket}/comments', [App\Http\Controllers\TicketCommentController::class, 'store'])->name('tickets.comments.store');
    
    // ⏱️ SAISIE DES TEMPS
    Route::post('/time-entries', [TimeEntryController::class, 'store'])->name('time-entries.store');
    Route::delete('/time-entries/{id}', [TimeEntryController::class, 'destroy'])->name('time-entries.destroy');
    
});

// -----------------------------------------------------------------------
// 👑 ZONE RÉSERVÉE AUX ADMINISTRATEURS
// -----------------------------------------------------------------------
Route::middleware(['auth', CheckRole::class.':admin'])->group(function () {
    
    // Le CRUD complet des clients est un privilège exclusif de l'Administrateur
    Route::resource('clients', ClientController::class);
    
});

// Les routes d'authentification (Login, Register, Password Reset)
require __DIR__.'/auth.php';