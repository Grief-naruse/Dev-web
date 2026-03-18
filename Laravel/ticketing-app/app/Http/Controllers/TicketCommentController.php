<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    /**
     * Enregistre un nouveau commentaire sur un ticket spécifique.
     */
    public function store(Request $request, Ticket $ticket)
    {
        // 1. Validation de sécurité : on stocke le résultat propre dans $validated
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // 2. Vérification des droits
        if ($request->user()->cannot('view', $ticket)) {
            abort(403, 'Accès refusé à ce ticket.');
        }

        // 3. Création du commentaire (on utilise le tableau $validated !)
        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'], // 👈 Le fix est ici !
        ]);

        // 4. Redirection vers la page du ticket avec un message de succès
        return back()->with('success', 'Votre commentaire a bien été ajouté.');
    }
}