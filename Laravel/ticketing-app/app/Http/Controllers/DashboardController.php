<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 📈 1. STATISTIQUES GLOBALES
        $stats = [
            'total_projects' => Project::count(),
            'active_tickets' => Ticket::whereIn('status', ['todo', 'in_progress', 'in_review'])->count(),
            'completed_tasks' => Ticket::where('status', 'completed')->count(),

            // Calcul du temps total consommé sur l'ensemble de l'ERP
            'total_hours' => (float) TimeEntry::sum('duration'),

            // Ratio de complétion (prévention division par zéro)
            'completion_rate' => Ticket::count() > 0
                ? round((Ticket::where('status', 'completed')->count() / Ticket::count()) * 100)
                : 0,
        ];

        // 📋 2. ACTIVITÉS RÉCENTES (Tickets mis à jour ou créés)
        $recentActivities = Ticket::with(['project.client', 'assignees', 'timeEntries']) // 👈 On ajoute project.client et timeEntries
            ->latest('updated_at')
            ->take(6)
            ->get();

        // 🏗️ 3. PROJETS CRITIQUES (Ceux avec des tickets urgents)
        $criticalProjects = Project::whereHas('tickets', function ($query) {
            $query->where('priority', 'urgent')->where('status', '!=', 'completed');
        })
            ->withCount([
                'tickets' => function ($query) {
                    $query->where('status', '!=', 'completed');
                }
            ])
            ->take(3)
            ->get();

        return view('dashboard', compact('stats', 'recentActivities', 'criticalProjects'));
    }
}