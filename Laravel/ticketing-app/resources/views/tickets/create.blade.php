@extends('layouts.app')

@section('content')
    <header style="margin-bottom: 20px;">
        <h1>🎫 Ouvrir un Nouveau Ticket</h1>
        <p class="text-muted">Décrivez le problème ou la demande d'évolution.</p>
    </header>

    <div class="card" style="max-width: 700px;">
        <form action="{{ url('/tickets') }}" method="POST">
            @csrf <div class="form-group">
                <label for="title">Titre du ticket</label>
                <input type="text" name="title" id="title" placeholder="Ex: Problème de connexion..." required>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="project_id">Projet concerné</label>
                    <select name="project_id" id="project_id" required>
                        <option value="">-- Sélectionner un projet --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                        @endforeach
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
                <textarea name="description" id="description" rows="5" placeholder="Expliquez ici les étapes pour reproduire le bug..." required></textarea>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="btn">🚀 Créer le ticket</button>
                <a href="{{ url('/tickets') }}" class="btn" style="background: #7f8c8d;">Annuler</a>
            </div>
        </form>
    </div>
@endsection