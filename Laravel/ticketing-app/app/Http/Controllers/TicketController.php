<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\StoreTicketRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Liste tous les tickets.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Le boss voit tout
            $tickets = Ticket::with(['project.client', 'assignees'])->latest()->paginate(15);

        } elseif ($user->isCollaborator()) {
            // Le dev voit les tickets des projets sur lesquels il est affecté
            $projectIds = $user->projects()->pluck('projects.id');
            $tickets = Ticket::whereIn('project_id', $projectIds)
                ->with(['project.client', 'assignees'])->latest()->paginate(15);

        } elseif ($user->isClient()) {
            // 🛡️ LE CLIENT ne voit que les tickets de SES projets
            $projectIds = Project::where('client_id', $user->client_id)->pluck('id');
            $tickets = Ticket::whereIn('project_id', $projectIds)
                ->with(['project.client', 'assignees'])->latest()->paginate(15);

        } else {
            $tickets = collect(); // Sécurité absolue
        }

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create(): View
    {
        $projects = Project::with('client')
            ->where('status', '!=', 'completed')
            ->orderBy('name')
            ->get();

        return view('tickets.create', compact('projects'));
    }

    /**
     * Enregistre le ticket dans la base.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validation stricte incluant désormais les assignees
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'type' => 'required|in:included,billable',
            'estimated_hours' => 'nullable|numeric|min:0',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        // 2. Données systèmes
        $validated['author_id'] = Auth::id();
        $validated['status'] = 'todo';

        // 3. Création du Ticket
        $ticket = Ticket::create($validated);

        // 4. 🔥 Synchronisation de l'équipe (Table Pivot)
        if ($request->has('assignees')) {
            $ticket->assignees()->sync($request->assignees);
        }

        return redirect()->route('tickets.index')->with('success', 'Le ticket a été créé avec succès.');
    }

    /**
     * Affiche les détails d'un ticket.
     */
    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $ticket->load(['project.client', 'timeEntries.user', 'comments.user', 'assignees']);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $projects = Project::with('client')->where('status', '!=', 'completed')->get();
        $users = User::whereIn('role', ['admin', 'collaborator'])->orderBy('name')->get();

        return view('tickets.edit', compact('ticket', 'projects', 'users'));
    }

    /**
     * Met à jour le ticket.
     */
    public function update(Request $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,in_review,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'type' => 'required|in:included,billable',
            'estimated_hours' => 'nullable|numeric|min:0',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        $ticket->update($validated);

        // Synchronisation Many-to-Many
        if (isset($validated['assignees'])) {
            $ticket->assignees()->sync($validated['assignees']);
        } else {
            $ticket->assignees()->sync([]);
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Le ticket a été mis à jour avec succès.');
    }

    /**
     * Supprime le ticket.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        Gate::authorize('delete', $ticket);
        $ticket->delete();

        return redirect('/tickets')->with('success', 'Le ticket a été supprimé.');
    }

    /**
     * 🤖 AJAX : Retourne l'équipe d'un projet au format JSON.
     */
    public function getProjectTeam(Project $project)
    {
        $admins = User::where('role', 'admin')->get();
        $collaborators = $project->users()->where('role', 'collaborator')->get();

        $team = $admins->merge($collaborators)->unique('id')->values();

        return response()->json($team);
    }
}