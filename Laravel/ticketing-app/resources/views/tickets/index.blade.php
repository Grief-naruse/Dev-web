@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.8rem; color: #2c3e50; margin: 0; font-weight: bold;">Gestion des Tickets</h1>
        
        @can('create', App\Models\Ticket::class)
            <a href="{{ route('tickets.create') }}" style="background-color: #3498db; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.95rem;">
                + Nouveau Ticket
            </a>
        @endcan
    </div>

    <div class="card" style="background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; padding: 0;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #2c3e50; color: white;">
                    <th style="padding: 15px; font-weight: bold;">N°</th>
                    <th style="padding: 15px; font-weight: bold;">Sujet</th>
                    <th style="padding: 15px; font-weight: bold;">Projet / Client</th>
                    <th style="padding: 15px; font-weight: bold; text-align: center;">Statut</th>
                    <th style="padding: 15px; font-weight: bold;">Priorité</th>
                    <th style="padding: 15px; font-weight: bold;">Équipe assignée</th>
                    <th style="padding: 15px; font-weight: bold;">Mise à jour</th>
                    <th style="padding: 15px; font-weight: bold; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr style="border-bottom: 1px solid #ecf0f1; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f9f9f9';" onmouseout="this.style.backgroundColor='transparent';">
                        
                        <td style="padding: 15px; font-weight: bold; color: #7f8c8d;">#{{ $ticket->id }}</td>

                        <td style="padding: 15px; color: #2c3e50; font-weight: bold;">
                            {{ $ticket->title }}
                        </td>

                        <td style="padding: 15px;">
                            <div style="color: #2c3e50; font-weight: bold; font-size: 0.9rem;">{{ $ticket->project->name ?? 'Projet inconnu' }}</div>
                            <div style="font-size: 0.8rem; color: #7f8c8d;">🏢 {{ $ticket->project?->client?->name ?? 'Client inconnu' }}</div>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            @php
                                // FIX : On utilise les clés reconnues par ta base de données
                                $statusStyles = [
                                    'todo' => 'color: #3498db; background-color: #ebf5fb;',
                                    'in_progress' => 'color: #27ae60; background-color: #e9f7ef;',
                                    'in_review' => 'color: #9b59b6; background-color: #f5eef8;',
                                    'completed' => 'color: #7f8c8d; background-color: #f2f4f4;'
                                ];
                                $style = $statusStyles[$ticket->status] ?? 'color: #7f8c8d; background-color: #f2f4f4;';
                                
                                $statusLabels = [
                                    'todo' => 'À faire',
                                    'in_progress' => 'En cours',
                                    'in_review' => 'En revue',
                                    'completed' => 'Terminé'
                                ];
                            @endphp
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; {{ $style }} border: 1px solid currentColor;">
                                {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                            </span>
                        </td>

                        <td style="padding: 15px; font-size: 0.9rem;">
                            @if($ticket->isUrgent())
                                <span style="color: #e74c3c; font-weight: bold;">🔴 {{ ucfirst($ticket->priority) }}</span>
                            @else
                                <span style="color: #2c3e50;">{{ ucfirst($ticket->priority) }}</span>
                            @endif
                        </td>

                        <td style="padding: 15px; font-size: 0.9rem;">
                            @if($ticket->assignees->isNotEmpty())
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    @foreach($ticket->assignees as $assignee)
                                        <span style="color: #2c3e50; font-weight: bold; font-size: 0.85rem;">👤 {{ $assignee->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color: #e74c3c; font-style: italic;">Non assigné</span>
                            @endif
                        </td>

                        <td style="padding: 15px; font-size: 0.85rem; color: #7f8c8d;">
                            {{ $ticket->updated_at->diffForHumans() }}
                        </td>

                        <td style="padding: 15px; text-align: right; font-weight: bold; font-size: 0.9rem;">
                            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                                <a href="{{ route('tickets.show', $ticket) }}" style="color: #3498db; text-decoration: none;">Détails</a>
                                
                                @can('update', $ticket)
                                    <a href="{{ route('tickets.edit', $ticket) }}" style="color: #f39c12; text-decoration: none;">Modifier</a>
                                @endcan
                                
                                @can('delete', $ticket)
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce ticket ?');" style="margin: 0; padding: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #e74c3c; font-weight: bold; cursor: pointer; padding: 0;">Supprimer</button>
                                    </form>
                                @endcan
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #7f8c8d; font-size: 1.1rem;">
                            Aucun ticket ne correspond à vos critères.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $tickets->links() }}
    </div>

</div>
@endsection