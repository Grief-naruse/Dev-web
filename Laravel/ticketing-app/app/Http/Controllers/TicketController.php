<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use App\Http\Requests\StoreTicketRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Liste tous les tickets.
     */
    public function index(): View
    {
        // Eager Loading : On charge le projet lié au ticket pour éviter le problème "N+1 queries"
        // latest() permet d'afficher les tickets les plus récents en premier
        $tickets = Ticket::with('project')->latest()->get();
        
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create(): View
    {
        // On a besoin de la liste des projets actifs pour le menu déroulant
        $projects = Project::select('id', 'name')->where('status', '!=', 'completed')->orderBy('name')->get();
        
        return view('tickets.create', compact('projects'));
    }

    /**
     * Enregistre le ticket dans la base.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        Ticket::create($request->validated());

        return redirect()->route('dashboard') // On changera vers tickets.index quand la vue existera
            ->with('success', 'Le ticket a été créé et rattaché au projet.');
    }

    /**
     * Affiche les détails d'un ticket.
     */
    public function show(int $id): View
    {
        $ticket = Ticket::with('project')->findOrFail($id);
        
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(int $id): View
    {
        $ticket = Ticket::findOrFail($id);
        
        // On récupère les projets pour pouvoir potentiellement déplacer le ticket
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        
        return view('tickets.edit', compact('ticket', 'projects'));
    }

    /**
     * Met à jour le ticket.
     */
    public function update(StoreTicketRequest $request, int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->validated());

        return redirect()->route('dashboard')
            ->with('success', 'Le ticket a été mis à jour.');
    }

    /**
     * Supprime le ticket.
     */
    public function destroy(int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Le ticket a été supprimé.');
    }
}