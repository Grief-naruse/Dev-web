@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 20px; max-width: 800px; margin: auto;">

        <div style="margin-bottom: 20px;">
            <a href="{{ route('users.index') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Retour
                à la liste</a>
        </div>

        <div class="card"
            style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h1
                style="font-size: 1.8rem; color: #2c3e50; margin-bottom: 25px; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
                Créer un accès Utilisateur
            </h1>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 20px;">
                    <label for="name" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Nom
                        complet *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    @error('name') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="email"
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Adresse Email
                        *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    @error('email') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="password" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Mot
                        de passe provisoire *</label>
                    <input type="password" name="password" id="password" required
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    <div style="font-size: 0.8rem; color: #7f8c8d; margin-top: 5px;">Minimum 8 caractères.</div>
                    @error('password') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div
                    style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <label for="role" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Rôle
                        d'accès *</label>
                    <select name="role" id="role" required
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                        <option value="">-- Sélectionner un rôle --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Administrateur (Manager)
                        </option>
                        <option value="collaborator" {{ old('role') == 'collaborator' ? 'selected' : '' }}>🧑‍💻 Collaborateur
                            (Équipe)</option>
                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>🏢 Client</option>
                    </select>
                    @error('role') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div id="client-select-div"
                    style="display: none; margin-bottom: 30px; padding: 15px; background-color: #fff9e6; border: 1px solid #f1c40f; border-radius: 6px;">
                    <label for="client_id"
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #d35400;">Entreprise rattachée
                        *</label>
                    <select name="client_id" id="client_id"
                        style="width: 100%; padding: 10px; border: 1px solid #f39c12; border-radius: 4px;">
                        <option value="">-- Choisir l'entreprise du client --</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <button type="submit"
                    style="background-color: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">Créer
                    l'utilisateur</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const clientDiv = document.getElementById('client-select-div');
            const clientInput = document.getElementById('client_id');

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
            toggleClientDiv();
            roleSelect.addEventListener('change', toggleClientDiv);
        });
    </script>
@endsection