@extends('layouts.app')

@section('content')
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>🎫 Ticket #{{ $ticket['id'] }}</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ url('/tickets/'.$ticket['id'].'/edit') }}" class="btn" style="background-color: var(--warning-color);">Modifier</a>
            <a href="{{ url('/tickets') }}" class="btn" style="background-color: #7f8c8d;">Retour à la liste</a>
        </div>
    </header>

    <div class="grid-2">
        <div class="card">
            <h2>{{ $ticket['title'] }}</h2>
            <p style="margin: 10px 0;">
                <strong>Statut :</strong> 
                <span class="badge {{ 'status-'.$ticket['status'] }}">{{ ucfirst($ticket['status']) }}</span>
            </p>
            <p><strong>Projet :</strong> {{ $ticket['project_name'] }}</p>
            <p><strong>Date de création :</strong> {{ $ticket['created_at'] }}</p>
            
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            
            <h3>Description</h3>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 10px; border-left: 4px solid var(--accent-color);">
                {{ $ticket['description'] }}
            </div>
        </div>

        <div class="card">
            <h3>⏱️ Gestion du temps</h3>
            <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 15px;">Enregistrer le temps passé sur ce ticket.</p>
            
            <form action="{{ url('/tickets/'.$ticket['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="time">Ajouter du temps (en heures)</label>
                    <input type="number" name="time" id="time" step="0.25" placeholder="Ex: 1.5">
                </div>
                <button type="submit" class="btn" style="width: 100%;">Ajouter les heures</button>
            </form>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

            <form action="{{ url('/tickets/'.$ticket['id']) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="background: var(--danger-color); width: 100%;" onclick="return confirm('Supprimer définitivement ce ticket ?')">
                    🗑️ Supprimer le ticket
                </button>
            </form>
        </div>
    </div>
@endsection