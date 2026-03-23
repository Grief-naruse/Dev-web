<?php

use App\Http\Controllers\TicketCommentController;
use Illuminate\Support\Facades\Route;

// On utilise le middleware 'web' pour que l'API reconnaisse qui est connecté sur ton navigateur
Route::middleware('web')->group(function () {
    Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store']);
});