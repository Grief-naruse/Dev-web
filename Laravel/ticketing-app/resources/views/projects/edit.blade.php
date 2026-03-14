@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px; max-width: 800px; margin: auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/projects') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Annuler et retourner à la liste</a>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ecf0f1; padding-bottom: 15px; margin-bottom: 25px;">
            <h1 style="font-size: 1.8rem; color: #2c3e50; margin: 0;">
                Modifier le projet : <span style="color: #3498db;">{{ $project->name }}</span>
            </h1>
            <span style="background-color: #ecf0f1; padding: 5px 10px; border-radius: 4px; font-size: 0.9rem; color: #7f8c8d; font-weight: bold;">
                ID: #{{ $project->id }}
            </span>
        </div>

        <form action="{{ url('/projects/' . $project->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="client_id" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Client facturé *</label>
                <select name="client_id" id="client_id" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px; background-color: #f9f9f9;">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
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
                <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                @error('name')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Cahier des charges / Description</label>
                <textarea name="description" id="description" rows="5" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div style="flex: 1;">
                    <label for="status" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Statut</label>
                    <select name="status" id="status" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>En cours (Actif)</option>
                        <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>En attente</option>
                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                    </select>
                    @error('status')
                        <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="flex: 1;">
                    <label for="included_hours" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Forfait d'heures vendues *</label>
                    <input type="number" name="included_hours" id="included_hours" value="{{ old('included_hours', $project->included_hours) }}" min="0" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    @error('included_hours')
                        <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" style="width: 100%; background-color: #f39c12; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer;">
                Sauvegarder les modifications
            </button>
        </form>
    </div>
</div>
@endsection