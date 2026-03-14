<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::withCount('projects')->get();
        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        Client::create($request->validated());

        return redirect()->route('clients.index')
            ->with('success', 'Le client a été créé avec succès.');
    }

    public function show(Client $client): View
    {
        $client->load('projects.tickets');
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(StoreClientRequest $request, Client $client): RedirectResponse
    {
        // On ignore l'ID actuel pour la règle "unique" lors de la modification
        $client->update($request->validated());

        return redirect()->route('clients.index')
            ->with('success', 'Le client a été mis à jour.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        // Sécurité Enterprise : on empêche la suppression si le client a des projets
        if ($client->projects()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un client ayant des projets actifs.');
        }

        $client->delete();
        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé.');
    }
}