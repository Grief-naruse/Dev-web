<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Liste de tous les utilisateurs (Accès Admin uniquement).
     */
    public function index()
    {
        // Tri hiérarchique strict : Admin (1) -> Collab (2) -> Client (3), puis ordre alphabétique
        $users = User::with('clientEnterprise')
            ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'collaborator' THEN 2 WHEN 'client' THEN 3 END")
            ->orderBy('name')
            ->get();
            
        return view('users.index', compact('users'));
    }

    /**
     * Formulaire de création d'un utilisateur.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('users.create', compact('clients'));
    }

    /**
     * Enregistre le nouvel utilisateur.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8',
            'role'      => 'required|in:admin,collaborator,client',
            // Le client_id est obligatoire UNIQUEMENT si le rôle est 'client'
            'client_id' => 'nullable|required_if:role,client|exists:clients,id',
        ]);

        // 🛡️ Sécurité Enterprise : On empêche les croisements de données
        if ($validated['role'] !== 'client') {
            $validated['client_id'] = null;
        }

        // 🔐 Hachage du mot de passe avant insertion
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Le compte utilisateur a été créé avec succès.');
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(User $user)
    {
        // Sécurité : On empêche l'admin de modifier son propre rôle ici pour éviter de se bloquer
        $isSelf = Auth::id() === $user->id;
        $clients = Client::orderBy('name')->get();
        
        return view('users.edit', compact('user', 'clients', 'isSelf'));
    }

    /**
     * Met à jour l'utilisateur.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            // On ignore l'email actuel de l'utilisateur pour la règle d'unicité
            'email'     => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'      => 'required|in:admin,collaborator,client',
            'client_id' => 'nullable|required_if:role,client|exists:clients,id',
            'password'  => 'nullable|string|min:8', // Optionnel à la modification
        ]);

        if ($validated['role'] !== 'client') {
            $validated['client_id'] = null;
        }

        // Si l'admin a tapé un nouveau mot de passe, on le crypte et on l'ajoute
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Sinon on le retire du tableau pour ne pas écraser l'ancien
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Le profil a été mis à jour.');
    }

    public function show(User $user)
    {
        // On précharge la relation client pour optimiser la requête SQL
        $user->load('clientEnterprise');
        
        return view('users.show', compact('user'));
    }
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Compte utilisateur supprimé.');
    }
}