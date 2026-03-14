<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. On prépare EXACTEMENT les statistiques demandées par Chart.js et tes cartes
        $stats = [
            'total_projects'  => Project::count(),
            'active_tickets'  => Ticket::count(),
            'pending_hours'   => 15, // Chiffre temporaire pour débloquer le visuel
            'completed_tasks' => 8,  // Chiffre temporaire pour débloquer le visuel
        ];

        // 2. On prépare la variable $user attendue par le header de ta vue
        $user = [
            'name' => 'Ilan Rubaud'
        ];

        // 3. On envoie le tout à la vue
        return view('dashboard', compact('stats', 'user'));
    }
}