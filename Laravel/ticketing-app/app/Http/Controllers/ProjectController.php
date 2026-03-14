<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
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
            
        } else {
            // 👤 Le client (On affichera ses projets une fois son compte lié à une entreprise)
            // Pour l'instant, on sécurise en renvoyant une collection vide.
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
        return view('projects.create', compact('clients'));
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
            'status' => 'required|in:active,completed',
            'included_hours' => 'required|numeric|min:0',
        ]);

        Project::create($validated);

        return redirect('/projects')->with('success', 'Le projet a été créé avec succès.');
    }

    /**
     * Afficher un projet spécifique
     */
    public function show(Project $project)
    {
        $user = Auth::user();

        // 🛡️ Vérification des droits d'accès à CE projet précis
        if ($user->isCollaborator() && !$user->projects->contains($project->id)) {
            abort(403, 'Vous n\'êtes pas affecté à ce projet.');
        }

        // TODO: Ajouter la vérification pour le Client plus tard

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
        return view('projects.edit', compact('project', 'clients'));
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
            'status' => 'required|in:active,completed',
            'included_hours' => 'required|numeric|min:0',
        ]);

        $project->update($validated);

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