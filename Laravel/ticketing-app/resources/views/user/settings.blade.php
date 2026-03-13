@extends('layouts.app')

@section('content')
    <header style="margin-bottom: 20px;">
        <h1>⚙️ Paramètres</h1>
    </header>

    <div class="card" style="max-width: 600px;">
        <form action="{{ url('/settings') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: rgba(0,0,0,0.03); border-radius: 8px; margin-bottom: 20px;">
                <div>
                    <label style="font-weight: bold; display: block;">Mode Sombre</label>
                    <small style="opacity: 0.7;">Activer l'interface obscure pour économiser vos yeux.</small>
                </div>
                <label class="switch">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Langue</label>
                <select name="lang" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid var(--border-color); background: var(--card-bg); color: var(--text-color);">
                    <option value="fr">Français</option>
                    <option value="en">English</option>
                </select>
            </div>

            <button type="submit" class="btn">💾 Enregistrer</button>
        </form>
    </div>
@endsection