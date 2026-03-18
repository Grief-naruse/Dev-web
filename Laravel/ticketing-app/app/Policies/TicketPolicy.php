<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * RÈGLE D'OR (Filtre Global) :
     * Si l'utilisateur est un Admin, il contourne toutes les restrictions.
     * Cette méthode est lue par Laravel AVANT toutes les autres.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        return null; // On laisse les autres méthodes faire leur travail
    }

    /**
     * Détermine si l'utilisateur peut VOIR la liste globale des tickets.
     */
    public function viewAny(User $user): bool
    {
        // Tout le monde a le droit de voir *sa* liste (le contrôleur filtrera le contenu)
        return true; 
    }

    /**
     * Détermine si l'utilisateur peut VOIR UN TICKET PRÉCIS.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isCollaborator()) {
            return true; // Le collaborateur voit tout (pour le moment)
        }

        if ($user->isClient()) {
            // Enterprise Ready : On vérifie que le client de l'utilisateur 
            // correspond bien au client du projet auquel appartient le ticket.
            return $user->client_id === $ticket->project->client_id;
        }

        return false;
    }

    /**
     * Détermine si l'utilisateur peut CRÉER un ticket.
     */
    public function create(User $user): bool
    {
        // Les clients et les collaborateurs peuvent créer des tickets
        return $user->isClient() || $user->isCollaborator();
    }

    /**
     * Détermine si l'utilisateur peut MODIFIER un ticket précis.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->isCollaborator()) {
            return true; // Un collaborateur peut mettre à jour n'importe quel ticket
        }

        if ($user->isClient()) {
            // Un client ne peut modifier QUE ses propres tickets
            return $user->client_id === $ticket->project->client_id;
        }

        return false;
    }

    /**
     * Détermine si l'utilisateur peut SUPPRIMER un ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Règle métier stricte : Seul l'Admin peut supprimer un ticket (géré par le 'before')
        // Les clients ne peuvent pas supprimer une trace de leur demande.
        return false; 
    }
}