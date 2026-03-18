<section>
    <header style="border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 15px;">
        <h2 class="section-title" style="font-size: 1.1rem;">Sécurité du compte</h2>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="current_password" class="form-label">Mot de passe actuel</label>
            <input id="current_password" name="current_password" type="password" class="form-input" autocomplete="current-password" />
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <input id="password" name="password" type="password" class="form-input" autocomplete="new-password" />
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password" />
        </div>

        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 15px; margin-top: 15px;">
            @if (session('status') === 'password-updated')
                <span style="color: var(--success-color); font-weight: bold; font-size: 0.9rem;">✅ Sécurisé</span>
            @endif
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</section>