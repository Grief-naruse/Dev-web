<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User; // 👈 AJOUT : On importe le modèle User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Afficher la liste des projets (Filtrée selon le rôle)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // 👑 Le patron voit tout
            $projects = Project::with('client')->get();
            
        } elseif ($user->isCollaborator()) {
            // 🧑‍💻 Le collaborateur ne voit que SES projets (via la table pivot)
            $projects = $user->projects()->with('client')->get();
            
        } elseif ($user->isClient()) {
            // 👤 ✨ NOUVEAU : Le client voit tous les projets liés à son entreprise
            $projects = Project::where('client_id', $user->client_id)->with('client')->get();
            
        } else {
            $projects = collect();
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // Sécurité : Seul l'admin peut créer un projet
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Seul un administrateur peut créer un projet.');
        }

        $clients = Client::all();
        // 👈 AJOUT : On récupère l'équipe (pas les clients)
        $users = User::whereIn('role', ['admin', 'collaborator'])->orderBy('name')->get(); 
        
        return view('projects.create', compact('clients', 'users'));
    }

    /**
     * Enregistrer un nouveau projet
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:active,on_hold,completed', // Ajout de on_hold
            'included_hours' => 'required|numeric|min:0',
            'users' => 'nullable|array', // 👈 AJOUT : Le tableau des membres cochés
            'users.*' => 'exists:users,id',
        ]);

        $project = Project::create($validated);

        // 👈 AJOUT : On attache les utilisateurs cochés au projet dans la table pivot
        if (!empty($validated['users'])) {
            $project->users()->attach($validated['users']);
        }

        return redirect('/projects')->with('success', 'Le projet a été créé avec succès.');
    }

    /**
     * Afficher un projet spécifique
     */
    public function show(Project $project)
    {
        $user = Auth::user();

        // 🛡️ Le collaborateur doit faire partie de l'équipe
        if ($user->isCollaborator() && !$user->projects->contains($project->id)) {
            abort(403, 'Vous n\'êtes pas affecté à ce projet.');
        }

        // 🛡️ ✨ NOUVEAU : Le client ne peut voir QUE les projets de sa propre entreprise
        if ($user->isClient() && $project->client_id !== $user->client_id) {
            abort(403, 'Accès refusé : Ce projet n\'appartient pas à votre entreprise.');
        }

        return view('projects.show', compact('project'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Seul un administrateur peut modifier un projet.');
        }

        $clients = Client::all();
        // 👈 AJOUT : On récupère l'équipe
        $users = User::whereIn('role', ['admin', 'collaborator'])->orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients', 'users'));
    }

    /**
     * Mettre à jour le projet
     */
    public function update(Request $request, Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:active,on_hold,completed',
            'included_hours' => 'required|numeric|min:0',
            'users' => 'nullable|array', // 👈 AJOUT
            'users.*' => 'exists:users,id',
        ]);

        $project->update($validated);

        // 👈 AJOUT : On synchronise la table pivot. 
        // Sync() est magique : il ajoute les nouveaux, garde les existants, et supprime ceux qui ont été décochés !
        if (isset($validated['users'])) {
            $project->users()->sync($validated['users']);
        } else {
            $project->users()->sync([]); // Si tout est décoché, on vide
        }

        return redirect('/projects')->with('success', 'Le projet a bien été mis à jour.');
    }

    /**
     * Supprimer le projet
     */
    public function destroy(Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $project->delete();

        return redirect('/projects')->with('success', 'Le projet a été supprimé.');
    }
}