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

        // 1. Optimisation Enterprise : On précharge les relations (Eager Loading)
        // Cela évite de faire une requête SQL supplémentaire pour chaque ticket affiché.
        $query = Ticket::with(['project.client', 'author', 'assignee']);

        // 2. Le Filtre Métier (Data Scoping)
        if ($user->isAdmin()) {
            // 👑 L'Admin voit absolument tous les tickets de l'ERP.
            // On ne filtre rien.
            
        } elseif ($user->isClient()) {
            // 🏢 Le Client ne voit QUE les tickets des projets appartenant à SON entreprise.
            $query->whereHas('project', function ($q) use ($user) {
                $q->where('client_id', $user->client_id);
            });
            
        } elseif ($user->isCollaborator()) {
            // 🧑‍💻 Le Collaborateur voit les tickets des projets sur lesquels l'Admin l'a assigné.
            // (Nécessite la table pivot project_user)
            $query->whereHas('project.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            // OU il voit les tickets qui lui sont directement assignés
            ->orWhere('assigned_to', $user->id);
        }

        // 3. On exécute la requête en triant par date de mise à jour récente
        $tickets = $query->latest('updated_at')->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create(): View
    {
        // FIX : On précharge la relation 'client' pour éviter l'erreur sur la vue
        $projects = Project::with('client')
            ->where('status', '!=', 'completed')
            ->orderBy('name')
            ->get();
        
        return view('tickets.create', compact('projects'));
    }

    /**
     * Enregistre le ticket dans la base.
     */
    /**
     * Enregistre le ticket dans la base.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validation stricte des données reçues du formulaire
        $validated = $request->validate([
            'project_id'      => 'required|exists:projects,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'priority'        => 'required|in:low,medium,high,urgent',
            'type'            => 'required|in:included,billable',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        // 2. On ajoute les données systèmes obligatoires
        $validated['author_id'] = Auth::id(); // L'auteur est celui qui est connecté
        $validated['status']    = 'todo';     // Un nouveau ticket est toujours "À faire"

        // 3. Création Enterprise Ready
        Ticket::create($validated);

        // 4. Redirection avec confirmation
        return redirect()->route('tickets.index')->with('success', 'Le ticket a été créé avec succès.');
    }

    /**
     * Affiche les détails d'un ticket.
     */
    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        // On ajoute "comments.user" à la liste des relations à charger !
        $ticket->load(['project.client', 'timeEntries.user', 'comments.user']);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        // 1. On charge les projets pour le premier menu déroulant
        $projects = Project::with('client')->where('status', '!=', 'completed')->get();

        // 2. On charge UNIQUEMENT les membres de l'équipe (pas les clients) pour l'assignation
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
            'project_id'      => 'required|exists:projects,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'status'          => 'required|in:todo,in_progress,in_review,completed',
            'priority'        => 'required|in:low,medium,high,urgent',
            'type'            => 'required|in:included,billable',
            'estimated_hours' => 'nullable|numeric|min:0',
            // 👈 On attend désormais un TABLEAU d'identifiants
            'assignees'       => 'nullable|array', 
            'assignees.*'     => 'exists:users,id', // Chaque ID du tableau doit exister
        ]);

        // On met à jour les informations de base du ticket
        $ticket->update($validated);

        // 🔥 LA MAGIE DU MANY-TO-MANY : On synchronise la table pivot !
        if (isset($validated['assignees'])) {
            $ticket->assignees()->sync($validated['assignees']);
        } else {
            $ticket->assignees()->sync([]); // Si on a tout décoché, on vide la table pivot
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Le ticket a été mis à jour avec succès.');
    }

    /**
     * Supprime le ticket.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        // Sécurité Enterprise Ready : On vérifie que la personne a le droit de supprimer
        Gate::authorize('delete', $ticket); 
        
        $ticket->delete();

        return redirect('/tickets')->with('success', 'Le ticket a été supprimé.');
    }
    /**
     * 🤖 AJAX : Retourne l'équipe d'un projet au format JSON.
     */
    public function getProjectTeam(Project $project)
    {
        // 1. On récupère tous les admins
        $admins = User::where('role', 'admin')->get();

        // 2. On récupère les collaborateurs assignés à ce projet
        // (Vérifie que la relation s'appelle bien 'users' dans ton modèle Project, sinon adapte)
        $collaborators = $project->users()->where('role', 'collaborator')->get();

        // 3. On fusionne, on enlève les doublons potentiels, et on renvoie au format JSON
        $team = $admins->merge($collaborators)->unique('id')->values();

        return response()->json($team);
    }
}