<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Affiche le profil de l'utilisateur connecté.
     */
    public function profile(): View
    {
        // On simule l'utilisateur connecté (en étape 6, on utilisera auth()->user())
        $user = [
            'name' => 'Ilan Rubaud',
            'email' => 'ilan.rubaud@esiea.fr',
            'role' => 'Administrateur',
            'created_at' => '2025-12-18'
        ];

        return view('user.profile', compact('user'));
    }

    /**
     * Affiche la page des paramètres / réglages.
     */
    public function settings(): View
    {
        return view('user.settings');
    }

    /**
     * Simulation de mise à jour des paramètres.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        return redirect('/settings')->with('success', 'Paramètres mis à jour avec succès !');
    }
}