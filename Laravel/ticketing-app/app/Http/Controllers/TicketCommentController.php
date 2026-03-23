<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        // 1. Validation stricte
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // 2. Création du message
        // Dans la méthode store
        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        // 3. Réponse JSON pour le JavaScript (Étape 7 du TP)
        return response()->json([
            'success' => true,
            'comment' => [
                'content' => $comment->content,
                'author' => Auth::user()->name,
                'time' => $comment->created_at->format('d/m/Y H:i'),
            ]
        ]);
    }
}