@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="color: #2c3e50; margin: 0; font-size: 2rem;">Tous les Tickets</h1>
        <a href="{{ url('/tickets/create') }}" style="background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);">
            + Ouvrir un Ticket
        </a>
    </div>

    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #2c3e50; color: white;">
                <tr>
                    <th style="padding: 15px; width: 5%;">ID</th>
                    <th style="padding: 15px; width: 20%;">Projet</th>
                    <th style="padding: 15px; width: 30%;">Titre du Ticket</th>
                    <th style="padding: 15px; width: 10%;">Urgence</th>
                    <th style="padding: 15px; width: 15%;">Statut</th>
                    <th style="padding: 15px; width: 20%; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr style="border-bottom: 1px solid #ecf0f1; transition: background-color 0.2s;">
                    <td style="padding: 15px; color: #7f8c8d; font-weight: bold;">
                        #{{ $ticket->id }}
                    </td>
                    
                    <td style="padding: 15px;">
                        @if($ticket->project)
                            📁 <a href="{{ url('/projects/' . $ticket->project_id) }}" style="color: #34495e; text-decoration: none; font-weight: bold; font-size: 0.95rem;">
                                {{ $ticket->project->name }}
                            </a>
                        @else
                            <span style="color: #e74c3c; font-weight: bold; font-size: 0.9rem;">⚠️ Projet orphelin</span>
                        @endif
                    </td>

                    <td style="padding: 15px; font-weight: bold; color: #2c3e50;">
                        {{ $ticket->title }}
                    </td>

                    <td style="padding: 15px;">
                        @if($ticket->priority === 'urgent')
                            <span style="background-color: #fadbd8; color: #c0392b; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">🔴 URGENT</span>
                        @elseif($ticket->priority === 'high')
                            <span style="background-color: #fdebd0; color: #e67e22; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">🟠 HAUTE</span>
                        @elseif($ticket->priority === 'medium')
                            <span style="background-color: #fcf3cf; color: #f39c12; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">🟡 NORMALE</span>
                        @else
                            <span style="background-color: #d5f5e3; color: #27ae60; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">🟢 BASSE</span>
                        @endif
                    </td>

                    <td style="padding: 15px;">
                        @if($ticket->status === 'todo')
                            <span style="background-color: #f2f4f4; color: #7f8c8d; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">À faire</span>
                        @elseif($ticket->status === 'in_progress')
                            <span style="background-color: #ebf5fb; color: #2980b9; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">En cours</span>
                        @elseif($ticket->status === 'in_review')
                            <span style="background-color: #f5eef8; color: #8e44ad; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">En revue</span>
                        @else
                            <span style="background-color: #e8f8f5; color: #27ae60; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">Terminé</span>
                        @endif
                    </td>

                    <td style="padding: 15px; text-align: right;">
                        <a href="{{ url('/tickets/' . $ticket->id) }}" style="color: #3498db; text-decoration: none; margin-right: 15px; font-weight: bold;">Détails</a>
                        <a href="{{ url('/tickets/' . $ticket->id . '/edit') }}" style="color: #f39c12; text-decoration: none; margin-right: 15px; font-weight: bold;">Modifier</a>
                        
                        <form action="{{ url('/tickets/' . $ticket->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce ticket ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-weight: bold; padding: 0;">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #7f8c8d; font-size: 1.1rem;">
                        <div style="font-size: 3rem; margin-bottom: 10px;">🎉</div>
                        Aucun ticket en cours ! Tout le monde peut aller boire un café.<br>
                        <a href="{{ url('/tickets/create') }}" style="color: #3498db; font-weight: bold; display: inline-block; margin-top: 15px;">Ou créez le premier ticket ici</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection