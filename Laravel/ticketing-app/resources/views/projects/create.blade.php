@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px; max-width: 800px; margin: auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/projects') }}" style="color: #3498db; text-decoration: none;">← Retour à la liste des projets</a>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h1 style="font-size: 1.8rem; color: #2c3e50; margin-bottom: 25px; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
            Lancer un nouveau Projet
        </h1>

        <form action="{{ url('/projects') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="client_id" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Client facturé *</label>
                <select name="client_id" id="client_id" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    <option value="">-- Sélectionner une entreprise --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Nom du Projet *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ex: Refonte du site vitrine" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                @error('name')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Cahier des charges / Description</label>
                <textarea name="description" id="description" rows="4" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">{{ old('description') }}</textarea>
                @error('description')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div style="flex: 1;">
                    <label for="status" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Statut</label>
                    <select name="status" id="status" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>En cours (Actif)</option>
                        <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>En attente</option>
                    </select>
                    @error('status')
                        <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="flex: 1;">
                    <label for="included_hours" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Forfait d'heures vendues *</label>
                    <input type="number" name="included_hours" id="included_hours" value="{{ old('included_hours', 0) }}" min="0" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    @error('included_hours')
                        <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" style="background-color: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; width: 100%;">
                Créer le projet
            </button>
        </form>
    </div>
</div>
@endsection