<section>
    <header style="margin-bottom: 10px;">
        <h2 class="section-title" style="color: var(--danger-color); font-size: 1.1rem;">Zone Dangereuse</h2>
        <p class="text-muted" style="font-size: 0.8rem; margin-bottom: 10px;">Action irréversible. Toutes vos données seront effacées.</p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Êtes-vous ABSOLUMENT certain de vouloir supprimer ce compte ?');">
        @csrf
        @method('delete')

        <div style="display: flex; gap: 10px; align-items: center;">
            <input id="password_delete" name="password" type="password" class="form-input" placeholder="Saisissez votre mot de passe" required style="flex: 1; margin-bottom: 0;" />
            
            <button type="submit" class="btn btn-danger" style="white-space: nowrap;">🗑️ Supprimer</button>
        </div>
        
        @error('password', 'userDeletion')
            <span style="color: var(--danger-color); font-size: 0.85rem; margin-top: 5px; display: block; font-weight: bold;">{{ $message }}</span>
        @enderror
    </form>
</section>