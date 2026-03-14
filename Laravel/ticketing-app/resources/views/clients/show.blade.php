@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ url('/clients') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Retour au portefeuille</a>
        <div>
            <a href="{{ url('/clients/' . $client->id . '/edit') }}" style="background-color: #f39c12; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;">
                ✏️ Éditer la fiche
            </a>
            <form action="{{ url('/clients/' . $client->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('La suppression du client supprimera également TOUS ses projets et tickets. Continuer ?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background-color: #e74c3c; color: white; padding: 8px 15px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    🗑️ Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px;">
        <h1 style="font-size: 2.5rem; color: #2c3e50; margin: 0 0 5px 0;">🏢 {{ $client->name }}</h1>
        <p style="color: #7f8c8d; font-size: 1rem; margin: 0;">Client inscrit le {{ $client->created_at->format('d/m/Y') }}</p>
    </div>

    <h2 style="color: #34495e; margin-bottom: 15px; font-size: 1.5rem;">Projets associés ({{ $client->projects ? $client->projects->count() : 0 }})</h2>
    
    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
        @if($client->projects && $client->projects->count() > 0)
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background-color: #ecf0f1; color: #2c3e50;">
                    <tr>
                        <th style="padding: 15px;">Nom du Projet</th>
                        <th style="padding: 15px;">Statut</th>
                        <th style="padding: 15px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($client->projects as $project)
                    <tr style="border-bottom: 1px solid #ecf0f1; transition: background-color 0.2s;">
                        <td style="padding: 15px;">
                            <a href="{{ url('/projects/' . $project->id) }}" style="color: #34495e; font-weight: bold; text-decoration: none; font-size: 1.1rem;">
                                📁 {{ $project->name }}
                            </a>
                        </td>
                        <td style="padding: 15px;">
                            @if($project->status === 'completed')
                                <span style="background-color: #e8f8f5; color: #27ae60; padding: 5px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">Terminé</span>
                            @else
                                <span style="background-color: #ebf5fb; color: #2980b9; padding: 5px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">En cours</span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: right;">
                            <a href="{{ url('/projects/' . $project->id) }}" style="color: #3498db; text-decoration: none; font-weight: bold;">Accéder au projet →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding: 40px; text-align: center; color: #7f8c8d; font-size: 1.1rem;">
                Ce client n'a aucun projet actif pour le moment.<br>
                <a href="{{ url('/projects/create') }}" style="color: #3498db; font-weight: bold; display: inline-block; margin-top: 10px;">Lui créer un projet</a>
            </div>
        @endif
    </div>
</div>
@endsection