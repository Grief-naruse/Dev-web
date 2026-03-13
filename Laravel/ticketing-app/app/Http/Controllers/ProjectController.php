<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    /**
     * Simulation d'une base de données de projets.
     */
    private function mockProjects(): array
    {
        return [
            ['id' => 1, 'name' => 'Site E-commerce ESIEA', 'client' => 'ESIEA', 'type' => 'Forfait', 'allocated_hours' => 50, 'used_hours' => 12],
            ['id' => 4, 'name' => 'Application Ticketing', 'client' => 'Interne', 'type' => 'Régie', 'allocated_hours' => 100, 'used_hours' => 0],
        ];
    }

    // 1. Liste des projets
    public function index(): View
    {
        $projects = $this->mockProjects();
        return view('projects.index', compact('projects'));
    }

    // 2. Formulaire de création
    public function create(): View
    {
        return view('projects.create');
    }

    // 3. Action de sauvegarde (Simulation)
    public function store(Request $request): RedirectResponse
    {
        // En Étape 6, on ferait Project::create($request->all());
        return redirect('/projects')->with('success', 'Le projet a été créé avec succès (Simulé).');
    }

    // 4. Détails d'un projet
    public function show($id): View
    {
        // On simule la recherche par ID
        $project = ['id' => $id, 'name' => 'Projet ' . $id, 'client' => 'Client Test', 'type' => 'Forfait', 'allocated_hours' => 50, 'used_hours' => 5];
        return view('projects.show', compact('project'));
    }

    // 5. Formulaire de modification
    public function edit($id): View
    {
        $project = ['id' => $id, 'name' => 'Projet ' . $id, 'client' => 'Client Test'];
        return view('projects.edit', compact('project'));
    }

    // 6. Mise à jour (Simulation)
    public function update(Request $request, $id): RedirectResponse
    {
        return redirect('/projects/' . $id)->with('success', 'Projet mis à jour (Simulé).');
    }

    // 7. Suppression (Simulation)
    public function destroy($id): RedirectResponse
    {
        return redirect('/projects')->with('success', 'Projet supprimé (Simulé).');
    }
}