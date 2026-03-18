<section>
    
    <div style="text-align: center; margin-bottom: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
        
        <form id="avatar_form" method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div style="margin: 0 auto; width: 100px; height: 100px; position: relative;">

                <div class="avatar-circle" style="width: 100px; height: 100px; border: 3px solid var(--border-color); box-shadow: 0 2px 5px rgba(0,0,0,0.1); overflow: hidden; margin: 0; padding: 0;">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatarUrl() }}" alt="Avatar de {{ Auth::user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 2.5rem; color: #7f8c8d;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                <label for="avatar_input" style="position: absolute; bottom: 0; right: 0; background-color: var(--accent-color); color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; border: 3px solid var(--card-bg); cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 10;" title="Changer la photo">
                    📷
                </label>

            </div>
            <input type="file" id="avatar_input" name="avatar" accept="image/*" style="display: none;" onchange="document.getElementById('avatar_form').submit();">
        </form>

        <h3 style="margin-top: 15px; margin-bottom: 5px; color: var(--text-color); font-weight: bold;">{{ Auth::user()->name }}</h3>
        <span class="badge" style="background-color: var(--primary-color);">{{ ucfirst(Auth::user()->role) }}</span>

        @if (session('status') === 'profile-avatar-updated')
            <span style="color: var(--success-color); font-weight: bold; font-size: 0.9rem; margin-top: 10px; display: block;">
                ✅ Photo mise à jour
            </span>
        @endif
        @error('avatar')
            <span style="color: var(--danger-color); font-size: 0.85rem; margin-top: 5px; display: block; font-weight: bold;">{{ $message }}</span>
        @enderror
    </div>

    <header style="margin-bottom: 15px;">
        <h2 class="section-title">Mes informations</h2>
    </header>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">Nom complet</label>
            <input id="name" name="name" type="text" class="form-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <span style="color: var(--danger-color); font-size: 0.8rem; margin-top: 3px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <span style="color: var(--danger-color); font-size: 0.8rem; margin-top: 3px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 15px; margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
            @if (session('status') === 'profile-updated')
                <span style="color: var(--success-color); font-weight: bold; font-size: 0.9rem;">
                    ✅ Mis à jour
                </span>
            @endif
            <button type="submit" class="btn btn-primary">Mettre à jour mes infos</button>
        </div>
    </form>
</section>