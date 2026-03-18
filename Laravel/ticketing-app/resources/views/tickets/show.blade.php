@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 20px; max-width: 1200px; margin: auto;">

        @if(session('success'))
            <div
                style="background-color: #e9f7ef; color: #27ae60; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 5px solid #27ae60; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
                <span>✓ {{ session('success') }}</span>
            </div>
        @endif

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <a href="{{ route('tickets.index') }}"
                style="color: #7f8c8d; text-decoration: none; font-weight: bold; font-size: 0.95rem;">
                ← Retour aux tickets
            </a>
            <div style="display: flex; gap: 10px;">
                @can('update', $ticket)
                    <a href="{{ route('tickets.edit', $ticket) }}"
                        style="background-color: #f39c12; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">Modifier</a>
                @endcan
                @can('delete', $ticket)
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST"
                        onsubmit="return confirm('Supprimer définitivement ce ticket ?');" style="margin: 0;">
                        @csrf @method('DELETE')
                        <button type="submit"
                            style="background-color: transparent; color: #e74c3c; border: 1px solid #e74c3c; padding: 8px 15px; border-radius: 4px; font-weight: bold; font-size: 0.9rem; cursor: pointer;">Supprimer</button>
                    </form>
                @endcan
            </div>
        </div>

        <div style="margin-bottom: 30px; border-bottom: 2px solid #ecf0f1; padding-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <span
                            style="background-color: #2c3e50; color: white; padding: 5px 12px; border-radius: 4px; font-weight: bold; font-size: 1rem;">#{{ $ticket->id }}</span>
                        <h1 style="font-size: 2.2rem; color: #2c3e50; margin: 0; font-weight: 800; line-height: 1.2;">
                            {{ $ticket->title }}</h1>
                    </div>
                    <div style="color: #7f8c8d; font-size: 1rem; display: flex; align-items: center; gap: 10px;">
                        @if($ticket->project)
                            <span style="color: #34495e; font-weight: bold;">📁 {{ $ticket->project->name }}</span>
                            <span>•</span>
                            <span>🏢 {{ $ticket->project->client?->name ?? 'Client inconnu' }}</span>
                        @else
                            <span style="color: #e74c3c; font-weight: bold;">⚠️ Projet supprimé</span>
                        @endif
                        <span>•</span>
                        <span>Créé {{ $ticket->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div style="text-align: right;">
                    @php
                        $statusStyles = [
                            'new' => 'color: #3498db; background-color: #ebf5fb;',
                            'in_progress' => 'color: #27ae60; background-color: #e9f7ef;',
                            'waiting' => 'color: #9b59b6; background-color: #f5eef8;',
                            'resolved' => 'color: #f39c12; background-color: #fef5e7;',
                            'closed' => 'color: #7f8c8d; background-color: #f2f4f4;'
                        ];
                        $style = $statusStyles[$ticket->status] ?? 'color: #7f8c8d; background-color: #f2f4f4;';
                        $statusLabels = ['new' => 'Nouveau', 'in_progress' => 'En cours', 'waiting' => 'En attente', 'resolved' => 'Résolu', 'closed' => 'Fermé'];
                    @endphp
                    <span
                        style="padding: 8px 15px; border-radius: 20px; font-size: 1rem; font-weight: bold; {{ $style }} border: 1px solid currentColor;">
                        {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 7fr 3fr; gap: 30px; align-items: start;">

            <div style="display: flex; flex-direction: column; gap: 30px;">

                <div>
                    <h3
                        style="color: #2c3e50; font-size: 1.1rem; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                        📝 Description</h3>
                    <div
                        style="background-color: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ecf0f1; font-size: 1.05rem; line-height: 1.6; color: #34495e; white-space: pre-wrap; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                        {{ $ticket->description ?: 'Aucune description fournie.' }}</div>
                </div>

                <div>
                    <h3
                        style="color: #2c3e50; font-size: 1.1rem; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                        💬 Discussion</h3>
                    <div
                        style="background: #fff; border-radius: 8px; border: 1px solid #ecf0f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden;">

                        <div
                            style="padding: 25px; display: flex; flex-direction: column; gap: 20px; background-color: #fdfdfd; max-height: 500px; overflow-y: auto;">
                            @forelse($ticket->comments as $comment)
                                @php $isMe = $comment->user_id === Auth::id(); @endphp
                                <div
                                    style="display: flex; flex-direction: column; align-items: {{ $isMe ? 'flex-end' : 'flex-start' }};">
                                    <div
                                        style="font-size: 0.8rem; color: #7f8c8d; margin-bottom: 5px; margin-left: 2px; margin-right: 2px;">
                                        <strong>{{ $isMe ? 'Vous' : $comment->user->name }}</strong>
                                        @if(!$isMe && $comment->user->isClient()) <span
                                            style="background-color: #f39c12; color: white; padding: 2px 5px; border-radius: 4px; font-size: 0.65rem;">Client</span>
                                        @endif
                                        • {{ $comment->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div
                                        style="background-color: {{ $isMe ? '#2c3e50' : '#ecf0f1' }}; color: {{ $isMe ? 'white' : '#2c3e50' }}; padding: 12px 18px; border-radius: 8px; border-top-right-radius: {{ $isMe ? '0' : '8px' }}; border-top-left-radius: {{ $isMe ? '8px' : '0' }}; max-width: 85%; font-size: 0.95rem; line-height: 1.5; white-space: pre-wrap;">
                                        {{ $comment->content }}</div>
                                </div>
                            @empty
                                <div style="text-align: center; color: #bdc3c7; font-style: italic; padding: 20px 0;">Aucun
                                    message. Lancez la discussion !</div>
                            @endforelse
                        </div>

                        <div style="background-color: #fff; padding: 20px; border-top: 1px solid #ecf0f1;">
                            <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST">
                                @csrf
                                <textarea name="content"
                                    style="width: 100%; padding: 12px; border: 1px solid #bdc3c7; border-radius: 6px; font-family: inherit; resize: vertical; margin-bottom: 10px;"
                                    rows="2" placeholder="Écrire un message..." required></textarea>
                                @error('content') <span
                                    style="color: #e74c3c; font-size: 0.85rem; display: block; margin-bottom: 10px;">{{ $message }}</span>
                                @enderror
                                <div style="text-align: right;">
                                    <button type="submit"
                                        style="background-color: #3498db; color: white; padding: 8px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Envoyer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div>
                    <h3
                        style="color: #2c3e50; font-size: 1.1rem; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                        📋 Historique technique</h3>
                    <div
                        style="background: #fff; border-radius: 8px; border: 1px solid #ecf0f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead style="background-color: #f8f9fa; border-bottom: 2px solid #ecf0f1;">
                                <tr>
                                    <th style="padding: 15px; color: #34495e; font-size: 0.9rem;">Date</th>
                                    <th style="padding: 15px; color: #34495e; font-size: 0.9rem;">Durée</th>
                                    <th style="padding: 15px; color: #34495e; font-size: 0.9rem;">Technicien</th>
                                    <th style="padding: 15px; color: #34495e; font-size: 0.9rem;">Action réalisée</th>
                                    @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                                        <th style="padding: 15px; text-align: right;"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ticket->timeEntries()->orderByDesc('date')->get() as $entry)
                                    <tr style="border-bottom: 1px solid #ecf0f1;">
                                        <td style="padding: 15px; font-weight: bold; color: #34495e; font-size: 0.9rem;">
                                            {{ $entry->date->format('d/m/Y') }}</td>
                                        <td style="padding: 15px; color: #27ae60; font-weight: bold; font-size: 0.9rem;">
                                            {{ $entry->duration }}h</td>
                                        <td style="padding: 15px; font-size: 0.9rem; color: #2c3e50;">
                                            {{ $entry->user?->name ?? 'N/A' }}</td>
                                        <td style="padding: 15px; font-size: 0.9rem; color: #7f8c8d;">{{ $entry->description }}
                                        </td>
                                        @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                                            <td style="padding: 15px; text-align: right;">
                                                <form action="{{ route('time-entries.destroy', $entry->id) }}" method="POST"
                                                    onsubmit="return confirm('Supprimer cette saisie ?');" style="margin: 0;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 1.1rem;"
                                                        title="Supprimer">×</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            style="padding: 20px; text-align: center; color: #bdc3c7; font-style: italic;">
                                            Aucune intervention technique enregistrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">

                <div
                    style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ecf0f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <h3
                        style="color: #2c3e50; font-size: 1rem; margin: 0 0 15px 0; border-bottom: 1px solid #ecf0f1; padding-bottom: 10px; text-transform: uppercase;">
                        Détails</h3>

                    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.95rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <span style="color: #7f8c8d;">Équipe :</span>
                            <div style="text-align: right; display: flex; flex-direction: column; gap: 5px;">
                                @forelse($ticket->assignees as $assignee)
                                    <strong style="color: #2c3e50; font-size: 0.9rem;">👤 {{ $assignee->name }}</strong>
                                @empty
                                    <strong style="color: #e74c3c; font-size: 0.9rem;">Non assigné</strong>
                                @endforelse
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #7f8c8d;">Priorité :</span>
                            <strong style="color: {{ $ticket->isUrgent() ? '#e74c3c' : '#2c3e50' }}">
                                @if($ticket->isUrgent()) 🔴 @endif {{ ucfirst($ticket->priority) }}
                            </strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #7f8c8d;">Type :</span>
                            <strong
                                style="color: #2c3e50;">{{ $ticket->type === 'included' ? '📦 Forfait' : '💸 Facturable' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #7f8c8d;">Créé par :</span>
                            <strong style="color: #2c3e50;">{{ $ticket->author->name ?? 'Système' }}</strong>
                        </div>
                    </div>
                </div>

                <div
                    style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ecf0f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <h3
                        style="color: #2c3e50; font-size: 1rem; margin: 0 0 15px 0; border-bottom: 1px solid #ecf0f1; padding-bottom: 10px; text-transform: uppercase;">
                        Budget Temps</h3>

                    @php
                        $totalHours = $ticket->timeEntries->sum('duration');
                        $estimated = $ticket->estimated_hours > 0 ? $ticket->estimated_hours : 1;
                        $progressPercentage = min(($totalHours / $estimated) * 100, 100);
                        $isOver = $totalHours > $ticket->estimated_hours;
                    @endphp

                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.95rem;">
                        <span style="color: #7f8c8d;">Consommé</span>
                        <strong style="color: {{ $isOver ? '#e74c3c' : '#27ae60' }}; font-size: 1.1rem;">
                            {{ $totalHours }}h / {{ $ticket->estimated_hours }}h
                        </strong>
                    </div>

                    <div
                        style="background-color: #ecf0f1; border-radius: 10px; height: 8px; width: 100%; overflow: hidden;">
                        <div
                            style="background-color: {{ $isOver ? '#e74c3c' : '#3498db' }}; width: {{ $progressPercentage }}%; height: 100%;">
                        </div>
                    </div>
                    @if($isOver)
                        <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px; text-align: right;">Dépassement de
                            budget !</div>
                    @endif
                </div>

                @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <h3 style="color: #2c3e50; font-size: 1rem; margin: 0 0 15px 0; text-transform: uppercase;">⏱️ Ajouter
                            du temps</h3>

                        <form action="{{ route('time-entries.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <div style="flex: 2;">
                                    <input type="date" name="date"
                                        style="width: 100%; padding: 8px; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 0.9rem;"
                                        value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                                        title="Date">
                                </div>
                                <div style="flex: 1;">
                                    <input type="number" step="0.1" min="0.1" name="duration"
                                        style="width: 100%; padding: 8px; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 0.9rem;"
                                        value="{{ old('duration') }}" placeholder="Heures" required title="Durée (h)">
                                </div>
                            </div>

                            <div style="margin-bottom: 10px;">
                                <textarea name="description"
                                    style="width: 100%; padding: 8px; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 0.9rem; resize: vertical;"
                                    rows="2" placeholder="Description de l'intervention..." required></textarea>
                            </div>

                            <button type="submit"
                                style="background-color: #27ae60; color: white; padding: 8px; border: none; border-radius: 4px; font-weight: bold; width: 100%; cursor: pointer; font-size: 0.95rem;">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection