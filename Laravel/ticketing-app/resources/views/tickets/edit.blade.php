@extends('layouts.app')

@section('content')
    <header style="margin-bottom: 20px;">
        <h1>✏️ Modifier le Ticket #{{ $ticket['id'] }}</h1>
        <p class="text-muted">Mise à jour des informations et du statut.</p>
    </header>

    <div class="card" style="max-width: 700px;">
        <form action="{{ url('/tickets/'.$ticket['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Titre du ticket</label>
                <input type="text" name="title" id="title" value="{{ $ticket['title'] }}" required>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="status">Statut actuel</label>
                    <select name="status" id="status" required>
                        <option value="new" {{ $ticket['id'] == 101 ? 'selected' : '' }}>Nouveau</option>
                        <option value="progress">En cours</option>
                        <option value="done">Terminé</option>
                        <option value="refused">Refusé</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="priority">Priorité</label>
                    <select name="priority" id="priority" required>
                        <option value="Basse">Basse</option>
                        <option value="Moyenne" selected>Moyenne</option>
                        <option value="Haute">Haute</option>
                        <option value="Urgente">Urgente</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description détaillée</label>
                <textarea name="description" id="description" rows="5" required>{{ $ticket['description'] }}</textarea>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="btn" style="background: var(--warning-color);">💾 Enregistrer les modifications</button>
                <a href="{{ url('/tickets') }}" class="btn" style="background: #7f8c8d;">Annuler</a>
            </div>
        </form>
    </div>
@endsection