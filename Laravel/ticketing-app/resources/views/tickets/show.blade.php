@extends('layouts.app')

@section('content')
<div class="erp-container-md">

    @if(session('success'))
        <div class="alert-success">
            <span>✓ {{ session('success') }}</span>
        </div>
    @endif

    <div class="ticket-header-block">
        <div>
            <div class="mb-3">
                <a href="{{ route('tickets.index') }}" class="text-muted font-bold text-sm">
                    ← Retour aux tickets
                </a>
            </div>
            
            <div class="flex align-center gap-1 mb-1">
                <span class="ticket-id-badge">#{{ $ticket->id }}</span>
                <h1 class="ticket-title-large">{{ $ticket->title }}</h1>
            </div>
            
            <div class="flex align-center text-muted font-bold" style="gap: 15px;">
                @if($ticket->project)
                    <span style="color: var(--primary-color);">📁 {{ $ticket->project->name }}</span>
                    <span>•</span>
                    <span>🏢 {{ $ticket->project->client?->name ?? 'Client inconnu' }}</span>
                @else
                    <span class="text-error">⚠️ Projet supprimé</span>
                @endif
                <span>•</span>
                <span style="font-weight: normal;">Créé {{ $ticket->created_at->diffForHumans() }}</span>
            </div>
        </div>
        
        <div class="text-right flex flex-col align-end gap-1">
            <div style="transform: scale(1.1); transform-origin: right top; margin-bottom: 15px;">
                {!! $ticket->status_badge !!}
            </div>
            
            <div class="flex gap-1">
                @can('update', $ticket)
                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn" style="background-color: var(--warning-color); padding: 8px 15px; font-size: 0.85rem;">Modifier</a>
                @endcan
                @can('delete', $ticket)
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce ticket ?');" style="margin: 0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-outline" style="border-color: var(--danger-color); color: var(--danger-color); padding: 8px 15px; font-size: 0.85rem;">Supprimer</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="grid-show">

        <div class="flex flex-col gap-2">

            <div class="section-box">
                <h3 class="section-title-sm">📝 Description</h3>
                <div class="desc-box">
                    {{ $ticket->description ?: 'Aucune description fournie.' }}
                </div>
            </div>

            <div class="section-box">
                <h3 class="section-title-sm">📋 Historique technique</h3>
                <div class="erp-card p-0 table-responsive">
                    <table class="erp-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Durée</th>
                                <th>Intervenant</th>
                                <th>Détail</th>
                                @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                                    <th class="text-right"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ticket->timeEntries()->orderByDesc('date')->get() as $entry)
                                <tr>
                                    <td class="font-bold text-sm">{{ $entry->date->format('d/m/Y') }}</td>
                                    <td class="font-bold text-sm" style="color: var(--success-color);">{{ $entry->duration }}h</td>
                                    <td class="text-sm font-bold">{{ $entry->user?->name ?? 'Système' }}</td>
                                    <td class="text-sm text-muted">{{ $entry->description }}</td>
                                    @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                                        <td class="text-right">
                                            <form action="{{ route('time-entries.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Supprimer cette saisie ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-text-danger text-sm" style="font-size: 1.1rem;">×</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted" style="padding: 20px; font-style: italic;">Aucune intervention enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section-box">
                <h3 class="section-title-sm">💬 Discussion</h3>
                <div class="erp-card p-0">
                    
                    <div id="chat-window" class="chat-window">
                        <div id="messages-container" class="flex flex-col gap-2">
                            @forelse($ticket->comments as $comment)
                                @php $isMe = $comment->user_id === Auth::id(); @endphp
                                <div class="chat-msg {{ $isMe ? 'me' : 'other' }}">
                                    <div class="chat-meta">
                                        <div class="avatar-sm">
                                            @if($comment->user->avatar)
                                                <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="">
                                            @else
                                                <span>{{ substr($comment->user->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $isMe ? 'Vous' : $comment->user->name }}</strong> • {{ $comment->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="chat-bubble">{{ $comment->content }}</div>
                                </div>
                            @empty
                                <div id="no-messages" class="text-center text-muted" style="font-style: italic; padding: 20px 0;">Aucun message. Lancez la discussion !</div>
                            @endforelse
                        </div>
                    </div>

                    <div style="background-color: var(--card-bg); padding: 20px; border-top: 1px solid var(--border-color);">
                        <form id="comment-form" action="{{ route('tickets.comments.store', $ticket) }}" method="POST">
                            @csrf
                            <textarea name="content" id="comment-content" class="form-textarea mb-1" rows="2" placeholder="Écrire un message..." required style="resize: vertical;"></textarea>
                            <div class="text-right">
                                <button type="submit" id="submit-button" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem;">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex flex-col gap-2">
            
            <div class="erp-card section-box">
                <h3 class="section-title-sm" style="border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 15px;">Détails</h3>
                
                <div class="flex flex-col gap-1 text-sm">
                    <div class="flex flex-col gap-1 mb-1">
                        <span class="text-muted">Équipe assignée :</span>
                        @forelse($ticket->assignees as $assignee)
                            <div class="flex align-center gap-1">
                                <div class="avatar-sm" style="margin: 0; width: 32px; height: 32px; font-size: 0.8rem;">
                                    @if($assignee->avatar)
                                        <img src="{{ asset('storage/avatars/' . $assignee->avatar) }}" alt="">
                                    @else
                                        <span>{{ substr($assignee->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <strong class="font-bold">{{ $assignee->name }}</strong>
                            </div>
                        @empty
                            <strong class="text-error" style="font-style: italic;">Non assigné</strong>
                        @endforelse
                    </div>

                    <div class="flex flex-between align-center" style="border-top: 1px solid var(--border-color); padding-top: 15px;">
                        <span class="text-muted">Priorité :</span>
                        {!! $ticket->priority_label !!}
                    </div>
                    <div class="flex flex-between align-center">
                        <span class="text-muted">Type :</span>
                        <strong class="font-bold">{{ $ticket->type === 'included' ? '📦 Forfait' : '💸 Facturable' }}</strong>
                    </div>
                    <div class="flex flex-between align-center">
                        <span class="text-muted">Créé par :</span>
                        <strong class="font-bold">{{ $ticket->author->name ?? 'Système' }}</strong>
                    </div>
                </div>
            </div>

            <div class="erp-card section-box">
                <h3 class="section-title-sm" style="border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 15px;">Budget Temps</h3>
                @php
                    $totalHours = $ticket->timeEntries->sum('duration');
                    $estimated = $ticket->estimated_hours > 0 ? $ticket->estimated_hours : 1;
                    $progressPercentage = min(($totalHours / $estimated) * 100, 100);
                    $isOver = $totalHours > $ticket->estimated_hours;
                @endphp
                <div class="flex flex-between mb-1 text-sm">
                    <span class="text-muted">Consommé</span>
                    <strong style="color: {{ $isOver ? 'var(--danger-color)' : 'var(--success-color)' }}; font-size: 1.1rem;">
                        {{ $totalHours }}h / {{ $ticket->estimated_hours }}h
                    </strong>
                </div>
                <div class="progress-bg">
                    <div class="progress-bar {{ $isOver ? 'over' : 'ok' }}" style="width: {{ $progressPercentage }}%;"></div>
                </div>
                @if($isOver)
                    <div class="text-error text-right mt-1" style="font-size: 0.8rem;">Attention : Dépassement !</div>
                @endif
            </div>

            @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                <div class="erp-card section-box" style="background-color: rgba(0,0,0,0.02);">
                    <h3 class="section-title-sm mb-3">⏱️ Ajouter du temps</h3>
                    <form action="{{ route('time-entries.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        
                        <div class="flex gap-1 mb-1">
                            <div style="flex: 2;">
                                <input type="date" name="date" class="form-input" style="padding: 8px;" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div style="flex: 1;">
                                <input type="number" step="0.1" name="duration" class="form-input" style="padding: 8px;" placeholder="H" required>
                            </div>
                        </div>
                        
                        <textarea name="description" class="form-textarea mb-1" style="padding: 8px;" rows="2" placeholder="Action réalisée..." required></textarea>
                        
                        <button type="submit" class="btn btn-success" style="width: 100%; padding: 8px;">Enregistrer</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketId = {{ $ticket->id }}; 
    const commentForm = document.getElementById('comment-form');
    const messagesContainer = document.getElementById('messages-container');
    const chatWindow = document.getElementById('chat-window');

    // Auto-scroll en bas au chargement
    chatWindow.scrollTop = chatWindow.scrollHeight;

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-button');
        const contentArea = document.getElementById('comment-content');
        const content = contentArea.value.trim();

        if(!content) return;

        submitBtn.disabled = true;
        submitBtn.innerText = 'Envoi...';

        fetch(`/api/tickets/${ticketId}/comments`, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Erreur serveur');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const noMsg = document.getElementById('no-messages');
                if(noMsg) noMsg.remove();

                const newMsg = document.createElement('div');
                newMsg.className = 'chat-msg me';
                
                const myAvatar = '{{ Auth::user()->avatar ? asset("storage/avatars/" . Auth::user()->avatar) : "" }}';
                const myInitial = '{{ substr(Auth::user()->name, 0, 1) }}';

                let avatarHtml = myAvatar 
                    ? `<img src="${myAvatar}" style="width: 100%; height: 100%; object-fit: cover;">`
                    : `<span>${myInitial}</span>`;

                newMsg.innerHTML = `
                    <div class="chat-meta">
                        <div class="avatar-sm">
                            ${avatarHtml}
                        </div>
                        <div>
                            <strong>Vous</strong> • à l'instant
                        </div>
                    </div>
                    <div class="chat-bubble">${content}</div>
                `;

                messagesContainer.appendChild(newMsg);
                contentArea.value = ''; 
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
        })
        .catch(error => {
            console.error('Erreur :', error);
            alert("Erreur lors de l'envoi du message.");
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Envoyer';
        });
    });
});
</script>
@endsection