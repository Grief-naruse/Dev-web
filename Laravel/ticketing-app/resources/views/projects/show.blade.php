@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ url('/projects') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Retour aux projets</a>
        
        <div>
            <a href="{{ url('/projects/' . $project->id . '/edit') }}" style="background-color: #f39c12; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;">
                ✏️ Modifier
            </a>
            <form action="{{ url('/projects/' . $project->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Attention, supprimer ce projet est irréversible. Continuer ?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background-color: #e74c3c; color: white; padding: 8px 15px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    🗑️ Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 style="font-size: 2rem; color: #2c3e50; margin: 0 0 10px 0;">{{ $project->name }}</h1>
                <p style="color: #7f8c8d; font-size: 1.1rem; margin: 0;">
                    Client : 
                    @if($project->client)
                        <a href="{{ route('clients.show', $project->client) }}" style="color: #34495e; font-weight: bold; text-decoration: none;">🏢 {{ $project->client->name }}</a>
                    @else
                        <span style="color: #e74c3c; font-weight: bold;">⚠️ Client supprimé</span>
                    @endif
                </p>
            </div>
            
            <div style="text-align: right;">
                <span style="display: inline-block; padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;
                    {{ $project->status === 'active' ? 'background-color: #e8f8f5; color: #27ae60;' : 
                      ($project->status === 'on_hold' ? 'background-color: #fef9e7; color: #f39c12;' : 'background-color: #f4f6f6; color: #7f8c8d;') }}">
                    {{ $project->status === 'active' ? 'En cours' : ($project->status === 'on_hold' ? 'En attente' : 'Terminé') }}
                </span>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #ecf0f1; margin: 20px 0;">

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <div>
                <h3 style="color: #34495e; margin-top: 0;">Cahier des charges</h3>
                <div style="background-color: #f9f9f9; padding: 20px; border-radius: 6px; border: 1px solid #ecf0f1; min-height: 100px;">
                    {{ $project->description ?: 'Aucune description fournie pour ce projet.' }}
                </div>
            </div>

            <div>
                <h3 style="color: #34495e; margin-top: 0;">Budget d'heures</h3>
                <div style="background-color: #f9f9f9; padding: 20px; border-radius: 6px; border: 1px solid #ecf0f1;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #7f8c8d; font-weight: bold;">Forfait :</span>
                        <span style="color: #2c3e50; font-weight: bold; font-size: 1.2rem;">{{ $project->included_hours }}h</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2 style="color: #2c3e50; font-size: 1.5rem; margin-bottom: 15px;">Tickets associés ({{ $project->tickets->count() }})</h2>
    
    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #2c3e50; color: white;">
                <tr>
                    <th style="padding: 15px;">ID</th>
                    <th style="padding: 15px;">Titre du ticket</th>
                    <th style="padding: 15px;">Priorité</th>
                    <th style="padding: 15px;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($project->tickets as $ticket)
                <tr style="border-bottom: 1px solid #ecf0f1; transition: background-color 0.2s;">
                    <td style="padding: 15px; color: #7f8c8d;">#{{ $ticket->id }}</td>
                    <td style="padding: 15px; font-weight: bold; color: #3498db;">{{ $ticket->title }}</td>
                    <td style="padding: 15px;">
                        <span style="font-weight: bold; color: {{ $ticket->priority === 'high' ? '#e74c3c' : ($ticket->priority === 'medium' ? '#f39c12' : '#27ae60') }};">
                            {{ strtoupper($ticket->priority) }}
                        </span>
                    </td>
                    <td style="padding: 15px;">{{ $ticket->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 30px; text-align: center; color: #7f8c8d; font-size: 1.1rem;">
                        Aucun ticket n'est encore lié à ce projet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection