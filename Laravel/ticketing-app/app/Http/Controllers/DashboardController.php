<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Affiche la page d'accueil du tableau de bord.
     */
    public function index(): View
    {
        // Simulation des statistiques pour l'Étape 5
        // Ces chiffres seront remplacés par des requêtes SQL (Eloquent) à l'Étape 6
        $stats = [
            'total_projects' => 2,
            'active_tickets' => 5,
            'pending_hours'  => 14.5,
            'completed_tasks'=> 12
        ];

        // On passe les données à la vue via la fonction compact()
        return view('dashboard', compact('stats'));
    }
}