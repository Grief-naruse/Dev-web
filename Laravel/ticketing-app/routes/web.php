<?php

use Illuminate\Support\Facades\Route;

// Importation de tous les contrôleurs créés
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. TABLEAU DE BORD (Accueil)
Route::get('/', [DashboardController::class, 'index']);

// 2. MODULE PROJETS
Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);           // Liste
    Route::get('/create', [ProjectController::class, 'create']);    // Formulaire d'ajout
    Route::post('/', [ProjectController::class, 'store']);          // Action d'enregistrement
    Route::get('/{id}', [ProjectController::class, 'show']);        // Détails
    Route::get('/{id}/edit', [ProjectController::class, 'edit']);   // Formulaire de modification
    Route::put('/{id}', [ProjectController::class, 'update']);      // Action de mise à jour
    Route::delete('/{id}', [ProjectController::class, 'destroy']);  // Action de suppression
});

// 3. MODULE TICKETS
Route::prefix('tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index']);            // Liste
    Route::get('/create', [TicketController::class, 'create']);     // Formulaire d'ajout
    Route::post('/', [TicketController::class, 'store']);           // Action d'enregistrement
    Route::get('/{id}', [TicketController::class, 'show']);         // Détails
    Route::get('/{id}/edit', [TicketController::class, 'edit']);    // Formulaire de modification
    Route::put('/{id}', [TicketController::class, 'update']);       // Action de mise à jour (et ajout de temps)
    Route::delete('/{id}', [TicketController::class, 'destroy']);   // Action de suppression
});

// 4. MODULE UTILISATEUR
Route::get('/profile', [UserController::class, 'profile']);
Route::get('/settings', [UserController::class, 'settings']);
Route::put('/settings', [UserController::class, 'updateSettings']); // Action de mise à jour des paramètres

/* Note du Lead Dev : Les routes d'authentification (login/register) 
   seront ajoutées à l'étape suivante pour sécuriser tout cet ensemble.
*/