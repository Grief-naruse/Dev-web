<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Affiche la liste des clients avec le nombre de projets rattachés.
     */
    public function index(): View
    {
        // On utilise eager loading (withCount) pour éviter le problème N+1
        // C'est crucial pour la performance en production.
        $clients = Client::withCount('projects')->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Affiche les détails d'un client spécifique et ses projets.
     */
    public function show(Client $client): View
    {
        // On charge les projets et leurs tickets pour l'affichage
        $client->load('projects.tickets');

        return view('clients.show', compact('client'));
    }
}