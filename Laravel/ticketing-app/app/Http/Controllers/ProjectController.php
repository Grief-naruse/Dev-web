<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Affiche la liste des projets.
     */
    public function index(): View
    {
        // Eager Loading : On charge le client et on compte les tickets en une seule requête SQL optimisée.
        $projects = Project::with('client')->withCount('tickets')->get();
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create(): View
    {
        // Pour lier un projet à un client, on a besoin de la liste des clients pour le <select>
        // On ne prend que 'id' et 'name' pour ne pas surcharger la RAM inutilement.
        $clients = Client::select('id', 'name')->orderBy('name')->get();
        
        return view('projects.create', compact('clients'));
    }

    /**
     * Enregistre le nouveau projet (protégé par StoreProjectRequest).
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        Project::create($request->validated());

        return redirect()->route('dashboard') // Ou vers projects.index quand la route existera
            ->with('success', 'Le projet a été créé avec succès et lié au client.');
    }

    /**
     * Affiche les détails d'un projet spécifique.
     */
    public function show(int $id): View
    {
        // On utilise findOrFail pour afficher une vraie erreur 404 si l'ID n'existe pas
        $project = Project::with(['client', 'tickets'])->findOrFail($id);
        
        return view('projects.show', compact('project'));
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(int $id): View
    {
        $project = Project::findOrFail($id);
        $clients = Client::select('id', 'name')->orderBy('name')->get();
        
        return view('projects.edit', compact('project', 'clients'));
    }

    /**
     * Met à jour le projet.
     */
    public function update(StoreProjectRequest $request, int $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $project->update($request->validated());

        return redirect()->route('dashboard')
            ->with('success', 'Le projet a été mis à jour.');
    }

    /**
     * Supprime le projet.
     */
    public function destroy(int $id): RedirectResponse
    {
        $project = Project::findOrFail($id);

        // Sécurité Enterprise : On bloque la suppression s'il y a des tickets
        if ($project->tickets()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un projet qui contient des tickets. Clôturez-les d\'abord.');
        }

        $project->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Projet supprimé définitivement.');
    }
}