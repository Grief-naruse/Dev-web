<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TicketController extends Controller
{
    /**
     * Simulation de données pour les tickets.
     */
    private function mockTickets(): array
    {
        return [
            [
                'id' => 101, 
                'title' => 'Bug affichage menu mobile', 
                'project_name' => 'Site E-commerce ESIEA',
                'status' => 'new', 
                'priority' => 'Haute',
                'description' => 'Le menu burger ne s\'ouvre pas sur iPhone.'
            ],
            [
                'id' => 102, 
                'title' => 'Ajout bouton export PDF', 
                'project_name' => 'Application Ticketing',
                'status' => 'progress', 
                'priority' => 'Moyenne',
                'description' => 'Besoin d\'extraire les rapports en PDF.'
            ],
        ];
    }

    // 1. Liste des tickets
    public function index(): View
    {
        $tickets = $this->mockTickets();
        return view('tickets.index', compact('tickets'));
    }

    // 2. Formulaire de création
    public function create(): View
    {
        // En étape 6, on récupèrerait la liste des projets ici pour le <select>
        $projects = [['id' => 1, 'name' => 'Site E-commerce'], ['id' => 4, 'name' => 'App Ticketing']];
        return view('tickets.create', compact('projects'));
    }

    // 3. Sauvegarde (Simulation)
    public function store(Request $request): RedirectResponse
    {
        return redirect('/tickets')->with('success', 'Ticket créé avec succès !');
    }

    // 4. Détails d'un ticket
    public function show($id): View
    {
        $ticket = [
            'id' => $id, 
            'title' => 'Bug affichage menu mobile', 
            'project_name' => 'Site E-commerce ESIEA',
            'status' => 'new',
            'description' => 'Le menu burger ne s\'ouvre pas sur iPhone.',
            'created_at' => '2026-03-11'
        ];
        return view('tickets.show', compact('ticket'));
    }

    // 5. Formulaire de modification
    public function edit($id): View
    {
        $ticket = ['id' => $id, 'title' => 'Bug affichage menu mobile', 'description' => 'Le menu burger ne s\'ouvre pas sur iPhone.'];
        return view('tickets.edit', compact('ticket'));
    }

    // 6. Mise à jour (Simulation)
    public function update(Request $request, $id): RedirectResponse
    {
        return redirect('/tickets/'.$id)->with('success', 'Ticket mis à jour !');
    }

    // 7. Suppression (Simulation)
    public function destroy($id): RedirectResponse
    {
        return redirect('/tickets')->with('success', 'Ticket supprimé.');
    }
}