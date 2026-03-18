@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 20px; max-width: 900px; margin: auto;">

        <div style="margin-bottom: 20px;">
            <a href="{{ route('tickets.index') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">←
                Retour à la liste</a>
        </div>

        <h1 style="font-size: 1.8rem; color: #2c3e50; margin-bottom: 20px; font-weight: bold;">Ouvrir un nouveau Ticket</h1>

        <div class="card"
            style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 20px;">
                    <label for="project_id"
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Projet rattaché
                        *</label>
                    <select name="project_id" id="project_id"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px; background-color: #f9f9f9;"
                        required>
                        <option value="">-- Sélectionner le projet concerné --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                📁 {{ $project->name }} ({{ $project->client?->name ?? 'Client inconnu' }})
                            </option>
                        @endforeach
                    </select>
                    @error('project_id') <span
                        style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="title" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Sujet
                        du ticket (Résumé) *</label>
                    <input type="text" name="title" id="title"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                        value="{{ old('title') }}" placeholder="Ex: Bug sur l'export PDF" required>
                    @error('title') <span
                        style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="description"
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Description
                        détaillée</label>
                    <textarea name="description" id="description"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;" rows="5"
                        placeholder="Détaillez votre demande ici...">{{ old('description') }}</textarea>
                    @error('description') <span
                        style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1;">
                        <label for="priority"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Niveau d'urgence
                            *</label>
                        <select name="priority" id="priority"
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Basse</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>🟡 Normale
                            </option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 Haute</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgente</option>
                        </select>
                    </div>

                    <div style="flex: 1;">
                        <label for="type"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Type de
                            facturation *</label>
                        <select name="type" id="type"
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <option value="included" {{ old('type') == 'included' ? 'selected' : '' }}>📦 Inclus dans le
                                forfait</option>
                            <option value="billable" {{ old('type') == 'billable' ? 'selected' : '' }}>💸 Hors-forfait
                                (Facturable)</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 30px; max-width: 200px;">
                    <label for="estimated_hours"
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Estimation
                        (Heures)</label>
                    <input type="number" step="0.5" min="0" name="estimated_hours" id="estimated_hours"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                        value="{{ old('estimated_hours', 0) }}">
                </div>

                <div style="text-align: right; border-top: 1px solid #ecf0f1; padding-top: 20px;">
                    <button type="submit"
                        style="background-color: #3498db; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-weight: bold; font-size: 1rem; cursor: pointer;">
                        Créer le ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection