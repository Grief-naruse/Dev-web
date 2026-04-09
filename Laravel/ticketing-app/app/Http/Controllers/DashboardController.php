<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // On prépare la structure attendue par ta vue Blade
        $stats = [
            'total_projects' => 0,
            'active_tickets' => 0,
            'total_hours'    => 0,
            'completion_rate'=> 0,
        ];
        
        $recentActivities = collect();
        $criticalProjects = collect();

        // ✨ ASTUCE DE PRO : On stocke la requête complexe dans une variable pour ne pas la répéter !
        $criticalCondition = function ($query) {
            $query->whereIn('priority', ['high', 'urgent'])->where('status', '!=', 'completed');
        };

        if ($user->isAdmin()) {
            // 👑 STATISTIQUES GLOBALES DE L'AGENCE
            $stats['total_projects'] = Project::where('status', 'active')->count();
            $stats['active_tickets'] = Ticket::where('status', '!=', 'completed')->count();
            $stats['total_hours']    = TimeEntry::sum('duration');

            $totalTickets = Ticket::count();
            $completed = Ticket::where('status', 'completed')->count();
            $stats['completion_rate'] = $totalTickets > 0 ? round(($completed / $totalTickets) * 100) : 0;

            $recentActivities = Ticket::with(['project.client', 'assignees', 'timeEntries'])
                ->latest('updated_at')->take(5)->get();

            // Correction SQLite : On utilise whereHas au lieu de having
            $criticalProjects = Project::withCount(['tickets as tickets_count' => $criticalCondition])
                ->whereHas('tickets', $criticalCondition)
                ->orderByDesc('tickets_count')->take(3)->get();

        } elseif ($user->isCollaborator()) {
            // 🧑‍💻 STATISTIQUES DU DÉVELOPPEUR
            $projectIds = $user->projects()->pluck('projects.id');
            
            $stats['total_projects'] = $projectIds->count();
            $stats['active_tickets'] = $user->tickets()->where('status', '!=', 'completed')->count();
            $stats['total_hours']    = TimeEntry::where('user_id', $user->id)->sum('duration');

            $totalTickets = $user->tickets()->count();
            $completed = $user->tickets()->where('status', 'completed')->count();
            $stats['completion_rate'] = $totalTickets > 0 ? round(($completed / $totalTickets) * 100) : 0;

            $recentActivities = Ticket::whereIn('project_id', $projectIds)
                ->with(['project.client', 'assignees', 'timeEntries'])
                ->latest('updated_at')->take(5)->get();

            $criticalProjects = Project::whereIn('id', $projectIds)
                ->withCount(['tickets as tickets_count' => $criticalCondition])
                ->whereHas('tickets', $criticalCondition)
                ->orderByDesc('tickets_count')->take(3)->get();

        } elseif ($user->isClient()) {
            // 🏢 STATISTIQUES DU CLIENT (Cloisonnées à son entreprise)
            $projectIds = Project::where('client_id', $user->client_id)->pluck('id');
            
            $stats['total_projects'] = Project::where('client_id', $user->client_id)->where('status', 'active')->count();
            $stats['active_tickets'] = Ticket::whereIn('project_id', $projectIds)->where('status', '!=', 'completed')->count();
            
            $ticketIds = Ticket::whereIn('project_id', $projectIds)->pluck('id');
            $stats['total_hours']    = TimeEntry::whereIn('ticket_id', $ticketIds)->sum('duration');

            $totalTickets = Ticket::whereIn('project_id', $projectIds)->count();
            $completed = Ticket::whereIn('project_id', $projectIds)->where('status', 'completed')->count();
            $stats['completion_rate'] = $totalTickets > 0 ? round(($completed / $totalTickets) * 100) : 0;

            $recentActivities = Ticket::whereIn('project_id', $projectIds)
                ->with(['project.client', 'assignees', 'timeEntries'])
                ->latest('updated_at')->take(5)->get();

            $criticalProjects = Project::where('client_id', $user->client_id)
                ->withCount(['tickets as tickets_count' => $criticalCondition])
                ->whereHas('tickets', $criticalCondition)
                ->orderByDesc('tickets_count')->take(3)->get();
        }

        return view('dashboard', compact('stats', 'recentActivities', 'criticalProjects'));
    }
}