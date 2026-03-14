@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #dc3545;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="color: #2c3e50; margin: 0; font-size: 2rem;">Gestion des Projets</h1>
        <a href="{{ url('/projects/create') }}" style="background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);">
            + Nouveau Projet
        </a>
    </div>

    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #2c3e50; color: white;">
                <tr>
                    <th style="padding: 15px;">Nom du Projet</th>
                    <th style="padding: 15px;">Client Facturé</th>
                    <th style="padding: 15px;">Statut</th>
                    <th style="padding: 15px;">Activité</th>
                    <th style="padding: 15px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                <tr style="border-bottom: 1px solid #ecf0f1; transition: background-color 0.2s;">
                    <td style="padding: 15px; font-weight: bold; color: #2c3e50; font-size: 1.1rem;">
                        {{ $project->name }}
                    </td>
                    <td style="padding: 15px; color: #7f8c8d;">
                        @if($project->client)
                            🏢 <a href="{{ route('clients.show', $project->client) }}" style="color: #34495e; text-decoration: none; font-weight: bold;">{{ $project->client->name }}</a>
                        @else
                            <span style="color: #e74c3c; font-weight: bold;">⚠️ Client introuvable</span>
                        @endif
                    </td>
                    <td style="padding: 15px;">
                        @if($project->status === 'active')
                            <span style="background-color: #e8f8f5; color: #27ae60; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">En cours</span>
                        @elseif($project->status === 'on_hold')
                            <span style="background-color: #fef9e7; color: #f39c12; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">En attente</span>
                        @else
                            <span style="background-color: #f4f6f6; color: #7f8c8d; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">Terminé</span>
                        @endif
                    </td>
                    <td style="padding: 15px; color: #7f8c8d; font-weight: bold;">
                        🎫 {{ $project->tickets_count }} ticket(s)
                    </td>
                    <td style="padding: 15px; text-align: right;">
                        <a href="{{ url('/projects/' . $project->id) }}" style="color: #3498db; text-decoration: none; margin-right: 15px; font-weight: bold;">Détails</a>
                        <a href="{{ url('/projects/' . $project->id . '/edit') }}" style="color: #f39c12; text-decoration: none; margin-right: 15px; font-weight: bold;">Modifier</a>
                        
                        <form action="{{ url('/projects/' . $project->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce projet et effacer ses données ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-weight: bold; padding: 0;">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 30px; text-align: center; color: #7f8c8d; font-size: 1.1rem;">
                        Aucun projet n'est enregistré pour le moment. Cliquez sur "+ Nouveau Projet" pour commencer !
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection