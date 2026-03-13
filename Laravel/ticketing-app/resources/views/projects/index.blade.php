@extends('layouts.app')

@section('content')
<div class="header-actions">
    <h1>Liste des Projets</h1>
    <a href="{{ url('/projects/create') }}" class="btn btn-primary">➕ Nouveau Projet</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom du Projet</th>
                <th>Statut</th>
                <th>Création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>#{{ $project->id }}</td>
                    <td><strong>{{ $project->name }}</strong></td>
                    <td>
                        @if($project->status === 'active')
                            <span class="badge" style="background: #2ecc71; color: white; padding: 5px 10px; border-radius: 12px; font-size: 0.8rem;">En cours</span>
                        @elseif($project->status === 'completed')
                            <span class="badge" style="background: #95a5a6; color: white; padding: 5px 10px; border-radius: 12px; font-size: 0.8rem;">Terminé</span>
                        @else
                            <span class="badge" style="background: #f39c12; color: white; padding: 5px 10px; border-radius: 12px; font-size: 0.8rem;">En pause</span>
                        @endif
                    </td>
                    <td>{{ $project->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ url('/projects/' . $project->id) }}" class="btn btn-info btn-sm">👁️ Voir</a>
                            <a href="{{ url('/projects/' . $project->id . '/edit') }}" class="btn btn-warning btn-sm">✏️ Éditer</a>
                            
                            <form action="{{ url('/projects/' . $project->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm();">🗑️ Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Aucun projet trouvé dans la base de données. Créez-en un !</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection