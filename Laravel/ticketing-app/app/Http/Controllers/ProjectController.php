<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project; // 👈 L'importation vitale de ton Modèle

class ProjectController extends Controller
{
    /**
     * READ : Affiche la liste de tous les projets
     */
    public function index()
    {
        // On demande à Eloquent de récupérer TOUS les projets dans la BDD
        $projects = Project::all(); 
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * CREATE : Enregistre un nouveau projet dans la BDD
     */
    public function store(Request $request)
    {
        // 1. Validation des données envoyées par le formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold'
        ]);

        // 2. Création et sauvegarde en une seule ligne !
        Project::create($request->all());

        // 3. Redirection avec le message SweetAlert
        return redirect('/projects')->with('success', 'Projet créé avec succès !');
    }

    /**
     * READ : Affiche les détails d'un projet précis
     */
    public function show(Project $project)
    {
        // Grâce à la relation définie dans le modèle, on récupère les tickets liés
        $tickets = $project->tickets; 

        return view('projects.show', compact('project', 'tickets'));
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * UPDATE : Met à jour le projet dans la BDD
     */
    public function update(Request $request, Project $project)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on_hold'
        ]);

        // 2. Mise à jour magique
        $project->update($request->all());

        return redirect('/projects')->with('success', 'Projet mis à jour !');
    }

    /**
     * DELETE : Supprime le projet de la BDD
     */
    public function destroy(Project $project)
    {
        // La suppression "en cascade" s'occupera de supprimer ses tickets liés
        $project->delete();

        return redirect('/projects')->with('success', 'Projet supprimé définitivement !');
    }
}