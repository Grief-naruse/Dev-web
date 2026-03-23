@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 25px; max-width: 1400px; margin: auto;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 style="font-size: 1.8rem; color: #2c3e50; margin: 0; font-weight: 800;">Gestion des Tickets</h1>
            <p style="color: #7f8c8d; margin: 5px 0 0 0;">Console de suivi des incidents et demandes clients.</p>
        </div>
        
        @can('create', App\Models\Ticket::class)
            <a href="{{ route('tickets.create') }}" style="background-color: #3498db; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
                + Nouveau Ticket
            </a>
        @endcan
    </div>

    <div class="card" style="background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; padding: 0; border: 1px solid #ecf0f1;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #2c3e50; color: white;">
                    <th style="padding: 15px; font-weight: bold; font-size: 0.85rem;">N°</th>
                    <th style="padding: 15px; font-weight: bold; font-size: 0.85rem;">Sujet</th>
                    <th style="padding: 15px; font-weight: bold; font-size: 0.85rem;">Projet / Client</th>
                    <th style="padding: 15px; font-weight: bold; text-align: center; font-size: 0.85rem;">Statut</th>
                    <th style="padding: 15px; font-weight: bold; font-size: 0.85rem;">Priorité</th>
                    <th style="padding: 15px; font-weight: bold; font-size: 0.85rem;">Équipe</th>
                    <th style="padding: 15px; font-weight: bold; font-size: 0.85rem;">Mise à jour</th>
                    <th style="padding: 15px; font-weight: bold; text-align: right; font-size: 0.85rem;">Actions</th>
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
                            <div style="color: #2c3e50; font-weight: bold; font-size: 0.9rem;">{{ $ticket->project->name ?? 'N/A' }}</div>
                            <div style="font-size: 0.8rem; color: #7f8c8d;">🏢 {{ $ticket->project?->client?->name ?? 'Client inconnu' }}</div>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            {!! $ticket->status_badge !!}
                        </td>

                        <td style="padding: 15px; font-size: 0.9rem;">
                            {!! $ticket->priority_label !!}
                        </td>

                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center;">
                                @forelse($ticket->assignees as $assignee)
                                    <div title="{{ $assignee->name }}" style="width: 28px; height: 28px; border-radius: 50%; border: 2px solid white; margin-left: -10px; overflow: hidden; background: #2c3e50; display: flex; align-items: center; justify-content: center; position: relative;">
                                        @if($assignee->avatar)
                                            <img src="{{ asset('storage/avatars/' . $assignee->avatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span style="color: white; font-size: 0.65rem; font-weight: bold;">{{ substr($assignee->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                @empty
                                    <span style="color: #e74c3c; font-size: 0.8rem; font-style: italic;">Non assigné</span>
                                @endforelse
                            </div>
                        </td>

                        <td style="padding: 15px; font-size: 0.85rem; color: #7f8c8d;">
                            {{ $ticket->updated_at->diffForHumans() }}
                        </td>

                        <td style="padding: 15px; text-align: right; font-weight: bold; font-size: 0.85rem;">
                            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                                <a href="{{ route('tickets.show', $ticket) }}" style="color: #3498db; text-decoration: none;">Détails</a>
                                
                                @can('update', $ticket)
                                    <a href="{{ route('tickets.edit', $ticket) }}" style="color: #f39c12; text-decoration: none;">Modifier</a>
                                @endcan
                                
                                @can('delete', $ticket)
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce ticket ?');" style="margin: 0; padding: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #e74c3c; font-weight: bold; cursor: pointer; padding: 0; font-size: 0.85rem; font-family: inherit;">Supprimer</button>
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