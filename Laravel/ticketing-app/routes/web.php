<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']); // La page d'accueil
Route::get('/tickets', [TicketController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);