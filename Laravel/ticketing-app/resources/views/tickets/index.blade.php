@extends('layouts.app')

@section('content')
<div class="erp-container">

    <div class="erp-header-box">
        <div>
            <h1 class="erp-title">Gestion des Tickets</h1>
            <p class="erp-subtitle">Console de suivi des incidents et demandes clients.</p>
        </div>
        
        @can('create', App\Models\Ticket::class)
            <a href="{{ route('tickets.create') }}" class="btn-primary">
                + Nouveau Ticket
            </a>
        @endcan
    </div>

    <div class="erp-card p-0">
        <div style="overflow-x: auto;">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Sujet</th>
                        <th>Projet / Client</th>
                        <th class="text-center">Statut</th>
                        <th>Priorité</th>
                        <th>Équipe</th>
                        <th>Mise à jour</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td class="font-bold text-muted">#{{ $ticket->id }}</td>

                            <td class="font-bold">
                                {{ $ticket->title }}
                            </td>

                            <td>
                                <div class="font-bold" style="font-size: 0.9rem;">{{ $ticket->project->name ?? 'N/A' }}</div>
                                <div class="text-muted text-sm" style="margin: 0;">🏢 {{ $ticket->project?->client?->name ?? 'Client inconnu' }}</div>
                            </td>

                            <td class="text-center">
                                {!! $ticket->status_badge !!}
                            </td>

                            <td class="text-sm">
                                {!! $ticket->priority_label !!}
                            </td>

                            <td>
                                <div class="avatar-group">
                                    @forelse($ticket->assignees as $assignee)
                                        <div class="avatar-sm" title="{{ $assignee->name }}">
                                            @if($assignee->avatar)
                                                <img src="{{ asset('storage/avatars/' . $assignee->avatar) }}" alt="">
                                            @else
                                                <span>{{ substr($assignee->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-muted text-sm" style="font-style: italic;">Non assigné</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="text-muted text-sm">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>

                            <td class="text-right font-bold text-sm">
                                <div class="flex gap-1 justify-end">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-accent">Détails</a>
                                    
                                    @can('update', $ticket)
                                        <a href="{{ route('tickets.edit', $ticket) }}" class="text-warning">Modifier</a>
                                    @endcan
                                    
                                    @can('delete', $ticket)
                                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce ticket ?');" style="margin: 0; padding: 0;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-text-danger">Supprimer</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 40px; font-size: 1.1rem;">
                                Aucun ticket ne correspond à vos critères.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 20px;">
        {{ $tickets->links() }}
    </div>

</div>
@endsection