@extends('layouts.app')

@section('content')
    <header>
        <h1>Liste des Projets</h1>
        <a href="{{ url('/projects/create') }}" class="btn">➕ Nouveau Projet</a>
    </header>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du Projet</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Heures (Utilisées / Allouées)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>#{{ $project['id'] }}</td>
                        <td><strong>{{ $project['name'] }}</strong></td>
                        <td>{{ $project['client'] }}</td>
                        <td>
                            <span class="badge {{ $project['type'] == 'Forfait' ? 'status-new' : 'status-progress' }}">
                                {{ $project['type'] }}
                            </span>
                        </td>
                        <td>
                            {{ $project['used_hours'] }}h / {{ $project['allocated_hours'] }}h
                        </td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ url('/projects/'.$project['id']) }}" class="btn" style="background: var(--accent-color); padding: 5px 10px; font-size: 0.8rem;">Voir</a>
                                <a href="{{ url('/projects/'.$project['id'].'/edit') }}" class="btn" style="background: var(--warning-color); padding: 5px 10px; font-size: 0.8rem;">Modifier</a>
                                
                                <form action="{{ url('/projects/'.$project['id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background: var(--danger-color); padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Supprimer ce projet ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Aucun projet trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection