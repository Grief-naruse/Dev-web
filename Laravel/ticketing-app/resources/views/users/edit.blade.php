@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 20px; max-width: 800px; margin: auto;">

        <div style="margin-bottom: 20px;">
            <a href="{{ route('users.index') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Retour
                à la liste</a>
        </div>

        <div class="card"
            style="background: #fff; padding: 0; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">

            <div style="background-color: #f8f9fa; border-bottom: 1px solid #ecf0f1;">
                <div style="padding: 20px 30px;">
                    <h1 style="font-size: 1.5rem; color: #2c3e50; margin: 0; font-weight: bold;">Configuration :
                        {{ $user->name }}</h1>
                </div>

                <div style="display: flex; padding: 0 30px; gap: 20px;">
                    <button type="button" class="tab-btn active" onclick="switchTab('general')"
                        style="background: none; border: none; padding: 10px 5px; font-weight: bold; color: #3498db; border-bottom: 3px solid #3498db; cursor: pointer; font-size: 0.95rem;">
                        👤 Informations Générales
                    </button>
                    <button type="button" class="tab-btn" onclick="switchTab('security')"
                        style="background: none; border: none; padding: 10px 5px; font-weight: bold; color: #7f8c8d; border-bottom: 3px solid transparent; cursor: pointer; font-size: 0.95rem;">
                        🔒 Sécurité & Mot de passe
                    </button>
                </div>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST" style="padding: 30px;">
                @csrf @method('PUT')

                <div id="tab-general" class="tab-content" style="display: block;">
                    <div style="margin-bottom: 20px;">
                        <label for="name" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Nom
                            complet *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                        @error('name') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="email"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Adresse Email
                            *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                        @error('email') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                    </div>

                    <div
                        style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <label for="role"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Rôle d'accès
                            *</label>
                        <select name="role" id="role" required
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;" {{ $isSelf ? 'disabled' : '' }}>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>👑
                                Administrateur (Manager)</option>
                            <option value="collaborator" {{ old('role', $user->role) == 'collaborator' ? 'selected' : '' }}>
                                🧑‍💻 Collaborateur (Équipe)</option>
                            <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>🏢 Client
                            </option>
                        </select>
                        @if($isSelf)
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <div style="font-size: 0.8rem; color: #7f8c8d; margin-top: 5px;">Vous ne pouvez pas modifier votre
                                propre rôle.</div>
                        @endif
                    </div>

                    <div id="client-select-div"
                        style="display: {{ old('role', $user->role) == 'client' ? 'block' : 'none' }}; margin-bottom: 20px; padding: 15px; background-color: #fff9e6; border: 1px solid #f1c40f; border-radius: 6px;">
                        <label for="client_id"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #d35400;">Entreprise
                            rattachée *</label>
                        <select name="client_id" id="client_id"
                            style="width: 100%; padding: 10px; border: 1px solid #f39c12; border-radius: 4px;">
                            <option value="">-- Choisir l'entreprise du client --</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" {{ old('client_id', $user->client_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="tab-security" class="tab-content" style="display: none;">
                    <div style="padding: 20px; border: 1px solid #e74c3c; border-radius: 8px; background-color: #fdfefe;">
                        <h3 style="color: #c0392b; margin-top: 0; font-size: 1.1rem;">Forcer un nouveau mot de passe</h3>
                        <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 15px;">Laissez ce champ vide si vous ne
                            souhaitez pas modifier le mot de passe actuel de l'utilisateur.</p>

                        <label for="password"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Nouveau mot de
                            passe</label>
                        <input type="password" name="password" id="password"
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                            placeholder="Minimum 8 caractères">
                        @error('password') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-top: 30px; border-top: 1px solid #ecf0f1; padding-top: 20px; text-align: right;">
                    <button type="submit"
                        style="background-color: #f39c12; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-weight: bold; font-size: 1rem; cursor: pointer;">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logique des onglets
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.style.color = '#7f8c8d';
                btn.style.borderBottomColor = 'transparent';
            });

            document.getElementById('tab-' + tabName).style.display = 'block';
            const activeBtn = event.currentTarget || document.querySelector('.tab-btn');
            activeBtn.style.color = '#3498db';
            activeBtn.style.borderBottomColor = '#3498db';
        }

        // Logique du Client dynamique
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const clientDiv = document.getElementById('client-select-div');
            const clientInput = document.getElementById('client_id');

            if (!roleSelect.disabled) {
                function toggleClientDiv() {
                    if (roleSelect.value === 'client') {
                        clientDiv.style.display = 'block';
                        clientInput.required = true;
                    } else {
                        clientDiv.style.display = 'none';
                        clientInput.required = false;
                        clientInput.value = '';
                    }
                }
                roleSelect.addEventListener('change', toggleClientDiv);
            }
        });
    </script>
@endsection