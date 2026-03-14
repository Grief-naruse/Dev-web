@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px; max-width: 800px; margin: auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/tickets') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Annuler et retourner à la liste</a>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ecf0f1; padding-bottom: 15px; margin-bottom: 25px;">
            <h1 style="font-size: 1.8rem; color: #2c3e50; margin: 0;">
                Mettre à jour le ticket : <span style="color: #3498db;">{{ $ticket->title }}</span>
            </h1>
            <span style="background-color: #ecf0f1; padding: 5px 10px; border-radius: 4px; font-size: 0.9rem; color: #7f8c8d; font-weight: bold;">
                ID: #{{ $ticket->id }}
            </span>
        </div>

        <form action="{{ url('/tickets/' . $ticket->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="project_id" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Projet rattaché *</label>
                <select name="project_id" id="project_id" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px; background-color: #f9f9f9;">
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $ticket->project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="title" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Titre du ticket *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $ticket->title) }}" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                @error('title')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Description détaillée</label>
                <textarea name="description" id="description" rows="5" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">{{ old('description', $ticket->description) }}</textarea>
                @error('description')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div style="flex: 1;">
                    <label for="status" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Statut *</label>
                    <select name="status" id="status" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                        <option value="todo" {{ old('status', $ticket->status) == 'todo' ? 'selected' : '' }}>À faire</option>
                        <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="in_review" {{ old('status', $ticket->status) == 'in_review' ? 'selected' : '' }}>En revue</option>
                        <option value="completed" {{ old('status', $ticket->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                    </select>
                    @error('status')
                        <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="flex: 1;">
                    <label for="priority" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Niveau d'urgence *</label>
                    <select name="priority" id="priority" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                        <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>🟢 Basse</option>
                        <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>🟡 Normale</option>
                        <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>🟠 Haute</option>
                        <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>🔴 Urgente</option>
                    </select>
                    @error('priority')
                        <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="estimated_hours" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Temps estimé pour ce ticket (en heures)</label>
                    <input type="number" step="0.5" min="0" name="estimated_hours" id="estimated_hours" value="{{ old('estimated_hours', $ticket->estimated_hours) }}" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                </div>
            </div>

            <button type="submit" style="width: 100%; background-color: #f39c12; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer;">
                Mettre à jour le ticket
            </button>
        </form>
    </div>
</div>
@endsection