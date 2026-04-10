<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// ✨ NOUVEAU : Import de la façade Storage de Laravel pour gérer les fichiers
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (Name, Email).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * ✨ NOUVELLE MÉTHODE ENTERPRISE : Upload de l'avatar utilisateur
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        // 1. Validation stricte du fichier (c'est une image, max 2MB)
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:8192'], // Max 2MB
        ]);

        $user = $request->user();

        // 2. Vérification de la présence effective du fichier
        if ($request->hasFile('avatar')) {
            
            // 3. Ménage Enterprise Ready : Supprimer l'ancien avatar s'il existe
            if ($user->avatar) {
                // On utilise le disk 'public' qu'on a lié précédemment
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // 4. Enregistrement du nouveau fichier
            // Laravel s'occupe de crypter le nom du fichier pour éviter les doublons
            // et de le ranger dans 'public/avatars' (qui pointe vers storage/app/public/avatars)
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // On récupère juste le nom final du fichier généré par Laravel
            $filename = basename($path);

            // 5. Mise à jour Enterprise Ready : On utilise forceFill() pour s'assurer
            // que la modification de la base de données est immédiate et non bloquée.
            $user->forceFill([
                'avatar' => $filename,
            ])->save();
        }

        // 6. Redirection avec un message de succès spécifique à l'avatar
        return Redirect::route('profile.edit')->with('status', 'profile-avatar-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}