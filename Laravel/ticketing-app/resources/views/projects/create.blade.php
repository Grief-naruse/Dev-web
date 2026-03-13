@extends('layouts.app')

@section('content')
    <header>
        <h1>Créer un Nouveau Projet</h1>
        <a href="{{ url('/projects') }}" class="btn" style="background: #7f8c8d;">⬅️ Retour à la liste</a>
    </header>

    <div class="card" style="max-width: 600px; margin-top: 20px;">
        <form action="{{ url('/projects') }}" method="POST">
            @csrf 
            <div class="form-group">
                <label for="name">Nom du Projet</label>
                <input type="text" name="name" id="name" placeholder="Ex: Refonte Dashboard" required>
            </div>

            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" name="client" id="client" placeholder="Nom de l'entreprise" required>
            </div>

            <div class="form-group">
                <label for="type">Type de Contrat</label>
                <select name="type" id="type" required>
                    <option value="Forfait">Forfait</option>
                    <option value="Régie">Régie</option>
                </select>
            </div>

            <div class="form-group">
                <label for="allocated_hours">Heures Allouées</label>
                <input type="number" name="allocated_hours" id="allocated_hours" step="0.5" value="0" required>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">🚀 Enregistrer le projet</button>
            </div>
        </form>
    </div>
@endsection