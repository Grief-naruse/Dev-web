@extends('layouts.app')

@section('content')
    <header>
        <h1>Modifier le Projet : {{ $project['name'] }}</h1>
        <a href="{{ url('/projects') }}" class="btn" style="background: #7f8c8d;">⬅️ Annuler</a>
    </header>

    <div class="card" style="max-width: 600px; margin-top: 20px;">
        <form action="{{ url('/projects/'.$project['id']) }}" method="POST">
            @csrf 
            @method('PUT') <div class="form-group">
                <label for="name">Nom du Projet</label>
                <input type="text" name="name" id="name" value="{{ $project['name'] }}" required>
            </div>

            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" name="client" id="client" value="{{ $project['client'] }}" required>
            </div>

            <div class="form-group">
                <label for="type">Type de Contrat</label>
                <select name="type" id="type" required>
                    <option value="Forfait" {{ $project['name'] == 'Forfait' ? 'selected' : '' }}>Forfait</option>
                    <option value="Régie" {{ $project['name'] == 'Régie' ? 'selected' : '' }}>Régie</option>
                </select>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn" style="background: var(--warning-color);">💾 Enregistrer les modifications</button>
            </div>
        </form>
    </div>
@endsection