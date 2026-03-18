<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Affiche la page des paramètres globaux.
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Sauvegarde les paramètres (Simulation pour l'UI).
     */
    public function update(Request $request)
    {
        // Plus tard, on enregistrera ces préférences en base de données.
        // Pour l'instant, on valide l'UX en renvoyant un message de succès.
        return redirect()->route('settings.index')->with('success', 'Vos préférences ont été enregistrées.');
    }
}