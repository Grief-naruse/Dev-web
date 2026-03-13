@extends('layouts.app')

@section('content')
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>📁 Projet : {{ $project['name'] }}</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ url('/projects/'.$project['id'].'/edit') }}" class="btn" style="background-color: var(--warning-color);">Modifier</a>
            <a href="{{ url('/projects') }}" class="btn" style="background-color: #7f8c8d;">Retour à la liste</a>
        </div>
    </header>

    <div class="card">
        <h3>Informations Générales</h3>
        <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
        
        <p><strong>Client :</strong> {{ $project['client'] }}</p>
        <p><strong>Type de contrat :</strong> <span class="badge">{{ $project['type'] }}</span></p>
        
        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <p><strong>Suivi du temps :</strong></p>
            <p style="font-size: 1.2rem;">
                {{ $project['used_hours'] }}h consommées sur <strong>{{ $project['allocated_hours'] }}h</strong> allouées.
            </p>
            <div style="width: 100%; background: #ddd; height: 10px; border-radius: 5px; margin-top: 10px;">
                <div style="width: {{ ($project['used_hours'] / $project['allocated_hours']) * 100 }}%; background: var(--accent-color); height: 10px; border-radius: 5px;"></div>
            </div>
        </div>
    </div>
@endsection