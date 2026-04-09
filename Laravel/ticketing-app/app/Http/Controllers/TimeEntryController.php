<?php

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Http\Requests\StoreTimeEntryRequest;
use Illuminate\Http\RedirectResponse;

class TimeEntryController extends Controller
{
    /**
     * Enregistre une nouvelle saisie de temps.
     */
    public function store(StoreTimeEntryRequest $request): RedirectResponse
    {
        // On récupère les données validées
        $data = $request->validated();
        
        // ✨ On force l'identité de l'auteur (Enterprise Ready)
        $data['user_id'] = auth()->id();

        TimeEntry::create($data);

        return redirect()->back()
            ->with('success', '⏱️ Temps de travail ajouté avec succès !');
    }

    /**
     * Supprime une saisie de temps en cas d'erreur.
     */
    public function destroy(int $id): RedirectResponse
    {
        $timeEntry = TimeEntry::findOrFail($id);
        $timeEntry->delete();

        return redirect()->back()
            ->with('success', '🗑️ Saisie de temps supprimée.');
    }
}