<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Project; // On importe aussi Project car on en aura besoin pour lister les projets dans le formulaire

class TicketController extends Controller
{
    /**
     * READ : Affiche la liste de tous les tickets
     */
    public function index()
    {
        // On récupère tous les tickets en incluant les données du projet lié (Eager Loading)
        $tickets = Ticket::with('project')->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        // On a besoin de la liste des projets pour le menu déroulant du ticket
        $projects = Project::all();
        return view('tickets.create', compact('projects'));
    }

    /**
     * CREATE : Enregistre le ticket dans la BDD
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:new,progress,done,refused',
            'priority' => 'required|in:Basse,Moyenne,Haute,Urgente',
            'estimated_hours' => 'numeric|min:0',
            'spent_hours' => 'numeric|min:0'
        ]);

        Ticket::create($request->all());

        return redirect('/tickets')->with('success', 'Ticket créé avec succès !');
    }

    /**
     * READ : Affiche les détails d'un ticket
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(Ticket $ticket)
    {
        $projects = Project::all();
        return view('tickets.edit', compact('ticket', 'projects'));
    }

    /**
     * UPDATE : Met à jour le ticket dans la BDD
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:new,progress,done,refused',
            'priority' => 'required|in:Basse,Moyenne,Haute,Urgente',
            'estimated_hours' => 'numeric|min:0',
            'spent_hours' => 'numeric|min:0'
        ]);

        $ticket->update($request->all());

        return redirect('/tickets')->with('success', 'Ticket mis à jour !');
    }

    /**
     * DELETE : Supprime le ticket
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect('/tickets')->with('success', 'Ticket supprimé définitivement !');
    }
}