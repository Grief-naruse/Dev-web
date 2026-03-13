@extends('layouts.app')

@section('content')
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>🎫 Gestion des Tickets</h1>
        <a href="{{ url('/tickets/create') }}" class="btn">➕ Ouvrir un Ticket</a>
    </header>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre du Ticket</th>
                    <th>Projet Associé</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>#{{ $ticket['id'] }}</td>
                        <td><strong>{{ $ticket['title'] }}</strong></td>
                        <td>{{ $ticket['project_name'] }}</td>
                        <td>
                            <span class="badge {{ 'status-'.$ticket['status'] }}">
                                {{ ucfirst($ticket['status']) }}
                            </span>
                        </td>
                        <td>{{ $ticket['priority'] }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ url('/tickets/'.$ticket['id']) }}" class="btn" style="background: var(--accent-color); padding: 5px 10px; font-size: 0.8rem;">Détails</a>
                                <a href="{{ url('/tickets/'.$ticket['id'].'/edit') }}" class="btn" style="background: var(--warning-color); padding: 5px 10px; font-size: 0.8rem;">Modifier</a>
                                
                                <form action="{{ url('/tickets/'.$ticket['id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background: var(--danger-color); padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Supprimer ce ticket ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">Aucun ticket ouvert pour le moment. ☕</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection