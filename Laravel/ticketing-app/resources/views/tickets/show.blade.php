@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px; max-width: 1200px; margin: auto;">

    @if(session('success'))
        <div style="background-color: #e9f7ef; color: #27ae60; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 5px solid #27ae60; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
            <span>✓ {{ session('success') }}</span>
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <a href="{{ route('tickets.index') }}" style="color: #7f8c8d; text-decoration: none; font-weight: bold; font-size: 0.95rem;">
            ← Retour aux tickets
        </a>
        <div style="display: flex; gap: 10px;">
            @can('update', $ticket)
                <a href="{{ route('tickets.edit', $ticket) }}" style="background-color: #f39c12; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">Modifier</a>
            @endcan
            @can('delete', $ticket)
                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce ticket ?');" style="margin: 0;">
                    @csrf @method('DELETE')
                    <button type="submit" style="background-color: transparent; color: #e74c3c; border: 1px solid #e74c3c; padding: 8px 15px; border-radius: 4px; font-weight: bold; font-size: 0.9rem; cursor: pointer;">Supprimer</button>
                </form>
            @endcan
        </div>
    </div>

    <div style="margin-bottom: 30px; border-bottom: 2px solid #ecf0f1; padding-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                    <span style="background-color: #2c3e50; color: white; padding: 5px 12px; border-radius: 4px; font-weight: bold; font-size: 1rem;">#{{ $ticket->id }}</span>
                    <h1 style="font-size: 2.2rem; color: #2c3e50; margin: 0; font-weight: 800; line-height: 1.2;">{{ $ticket->title }}</h1>
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
            
            <div style="text-align: right; transform: scale(1.2); transform-origin: right center;">
                {!! $ticket->status_badge !!}
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 7fr 3fr; gap: 30px; align-items: start;">

        <div style="display: flex; flex-direction: column; gap: 30px;">

            <div>
                <h3 style="color: #2c3e50; font-size: 1.1rem; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">📝 Description</h3>
                <div style="background-color: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ecf0f1; font-size: 1.05rem; line-height: 1.6; color: #34495e; white-space: pre-wrap; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">{{ $ticket->description ?: 'Aucune description fournie.' }}</div>
            </div>

            <div>
                <h3 style="color: #2c3e50; font-size: 1.1rem; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">📋 Historique technique</h3>
                <div style="background: #fff; border-radius: 8px; border: 1px solid #ecf0f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #ecf0f1;">
                            <tr>
                                <th style="padding: 15px; color: #34495e; font-size: 0.9rem;">Date</th>
                                <th style="padding: 15px; color: #34495e; font-size: 0.9rem;">Durée</th>
                                <th style="padding: 15px; color: #34495e; font-size: 0.9rem;">Intervenant</th>
                                <th style="padding: 15px; color: #34495e; font-size: 0.9rem;">Détail</th>
                                @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                                    <th style="padding: 15px; text-align: right;"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ticket->timeEntries()->orderByDesc('date')->get() as $entry)
                                <tr style="border-bottom: 1px solid #ecf0f1;">
                                    <td style="padding: 15px; font-weight: bold; color: #34495e; font-size: 0.9rem;">{{ $entry->date->format('d/m/Y') }}</td>
                                    <td style="padding: 15px; color: #27ae60; font-weight: bold; font-size: 0.9rem;">{{ $entry->duration }}h</td>
                                    <td style="padding: 15px; font-size: 0.9rem; color: #2c3e50;">{{ $entry->user?->name ?? 'Système' }}</td>
                                    <td style="padding: 15px; font-size: 0.9rem; color: #7f8c8d;">{{ $entry->description }}</td>
                                    @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                                        <td style="padding: 15px; text-align: right;">
                                            <form action="{{ route('time-entries.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Supprimer cette saisie ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 1.1rem;">×</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr><td colspan="5" style="padding: 20px; text-align: center; color: #bdc3c7; font-style: italic;">Aucune intervention enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h3 style="color: #2c3e50; font-size: 1.1rem; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">💬 Discussion</h3>
                <div style="background: #fff; border-radius: 8px; border: 1px solid #ecf0f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden;">
                    
                    <div id="chat-window" style="padding: 25px; display: flex; flex-direction: column; gap: 20px; background-color: #fdfdfd; max-height: 450px; overflow-y: auto; scroll-behavior: smooth;">
                        <div id="messages-container" style="display: flex; flex-direction: column; gap: 20px;">
                            @forelse($ticket->comments as $comment)
                                @php $isMe = $comment->user_id === Auth::id(); @endphp
                                <div style="display: flex; flex-direction: column; align-items: {{ $isMe ? 'flex-end' : 'flex-start' }};">
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px; flex-direction: {{ $isMe ? 'row-reverse' : 'row' }};">
                                        <div style="width: 24px; height: 24px; border-radius: 50%; overflow: hidden; background: #2c3e50; display: flex; align-items: center; justify-content: center;">
                                            @if($comment->user->avatar)
                                                <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <span style="color: white; font-size: 0.6rem;">{{ substr($comment->user->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 0.8rem; color: #7f8c8d;">
                                            <strong>{{ $isMe ? 'Vous' : $comment->user->name }}</strong> • {{ $comment->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div style="background-color: {{ $isMe ? '#2c3e50' : '#ecf0f1' }}; color: {{ $isMe ? 'white' : '#2c3e50' }}; padding: 12px 18px; border-radius: 8px; border-top-{{ $isMe ? 'right' : 'left' }}-radius: 0; max-width: 85%; font-size: 0.95rem; line-height: 1.5; white-space: pre-wrap;">{{ $comment->content }}</div>
                                </div>
                            @empty
                                <div id="no-messages" style="text-align: center; color: #bdc3c7; font-style: italic; padding: 20px 0;">Aucun message. Lancez la discussion !</div>
                            @endforelse
                        </div>
                    </div>

                    <div style="background-color: #fff; padding: 20px; border-top: 1px solid #ecf0f1;">
                        <form id="comment-form" action="{{ route('tickets.comments.store', $ticket) }}" method="POST">
                            @csrf
                            <textarea name="content" id="comment-content" style="width: 100%; padding: 12px; border: 1px solid #bdc3c7; border-radius: 6px; font-family: inherit; resize: vertical; margin-bottom: 10px;" rows="2" placeholder="Écrire un message..." required></textarea>
                            <div style="text-align: right;">
                                <button type="submit" id="submit-button" style="background-color: #3498db; color: white; padding: 8px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ecf0f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h3 style="color: #2c3e50; font-size: 1rem; margin: 0 0 15px 0; border-bottom: 1px solid #ecf0f1; padding-bottom: 10px; text-transform: uppercase;">Détails</h3>
                <div style="display: flex; flex-direction: column; gap: 15px; font-size: 0.95rem;">
                    
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <span style="color: #7f8c8d;">Équipe assignée :</span>
                        @forelse($ticket->assignees as $assignee)
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; background: #2c3e50; display: flex; align-items: center; justify-content: center; border: 1px solid #edf2f7;">
                                    @if($assignee->avatar)
                                        <img src="{{ asset('storage/avatars/' . $assignee->avatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span style="color: white; font-size: 0.7rem;">{{ substr($assignee->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <strong style="color: #2c3e50; font-size: 0.9rem;">{{ $assignee->name }}</strong>
                            </div>
                        @empty
                            <strong style="color: #e74c3c; font-size: 0.9rem; font-style: italic;">Non assigné</strong>
                        @endforelse
                    </div>

                    <div style="display: flex; justify-content: space-between; border-top: 1px solid #f8f9fa; padding-top: 10px;">
                        <span style="color: #7f8c8d;">Priorité :</span>
                        {!! $ticket->priority_label !!}
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #7f8c8d;">Type :</span>
                        <strong style="color: #2c3e50;">{{ $ticket->type === 'included' ? '📦 Forfait' : '💸 Facturable' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #7f8c8d;">Créé par :</span>
                        <strong style="color: #2c3e50;">{{ $ticket->author->name ?? 'Système' }}</strong>
                    </div>
                </div>
            </div>

            <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ecf0f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h3 style="color: #2c3e50; font-size: 1rem; margin: 0 0 15px 0; border-bottom: 1px solid #ecf0f1; padding-bottom: 10px; text-transform: uppercase;">Budget Temps</h3>
                @php
                    $totalHours = $ticket->timeEntries->sum('duration');
                    $estimated = $ticket->estimated_hours > 0 ? $ticket->estimated_hours : 1;
                    $progressPercentage = min(($totalHours / $estimated) * 100, 100);
                    $isOver = $totalHours > $ticket->estimated_hours;
                @endphp
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.95rem;">
                    <span style="color: #7f8c8d;">Consommé</span>
                    <strong style="color: {{ $isOver ? '#e74c3c' : '#27ae60' }}; font-size: 1.1rem;">{{ $totalHours }}h / {{ $ticket->estimated_hours }}h</strong>
                </div>
                <div style="background-color: #ecf0f1; border-radius: 10px; height: 8px; width: 100%; overflow: hidden;">
                    <div style="background-color: {{ $isOver ? '#e74c3c' : '#3498db' }}; width: {{ $progressPercentage }}%; height: 100%;"></div>
                </div>
                @if($isOver)
                    <div style="color: #e74c3c; font-size: 0.8rem; margin-top: 5px; text-align: right;">Attention : Dépassement !</div>
                @endif
            </div>

            @if(Auth::user()->isCollaborator() || Auth::user()->isAdmin())
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <h3 style="color: #2c3e50; font-size: 1rem; margin: 0 0 15px 0; text-transform: uppercase;">⏱️ Ajouter du temps</h3>
                    <form action="{{ route('time-entries.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <div style="flex: 2;"><input type="date" name="date" style="width: 100%; padding: 8px; border: 1px solid #bdc3c7; border-radius: 4px;" value="{{ date('Y-m-d') }}" required></div>
                            <div style="flex: 1;"><input type="number" step="0.1" name="duration" style="width: 100%; padding: 8px; border: 1px solid #bdc3c7; border-radius: 4px;" placeholder="H" required></div>
                        </div>
                        <textarea name="description" style="width: 100%; padding: 8px; border: 1px solid #bdc3c7; border-radius: 4px; margin-bottom: 10px; font-family: inherit;" rows="2" placeholder="Action réalisée..." required></textarea>
                        <button type="submit" style="background-color: #27ae60; color: white; padding: 8px; border: none; border-radius: 4px; width: 100%; font-weight: bold; cursor: pointer;">Enregistrer</button>
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
                newMsg.style.display = 'flex';
                newMsg.style.flexDirection = 'column';
                newMsg.style.alignItems = 'flex-end';
                
                const myAvatar = '{{ Auth::user()->avatar ? asset("storage/avatars/" . Auth::user()->avatar) : "" }}';
                const myInitial = '{{ substr(Auth::user()->name, 0, 1) }}';

                let avatarHtml = myAvatar 
                    ? `<img src="${myAvatar}" style="width: 100%; height: 100%; object-fit: cover;">`
                    : `<span style="color: white; font-size: 0.6rem;">${myInitial}</span>`;

                newMsg.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px; flex-direction: row-reverse;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; overflow: hidden; background: #2c3e50; display: flex; align-items: center; justify-content: center;">
                            ${avatarHtml}
                        </div>
                        <div style="font-size: 0.8rem; color: #7f8c8d;">
                            <strong>Vous</strong> • à l'instant
                        </div>
                    </div>
                    <div style="background-color: #2c3e50; color: white; padding: 12px 18px; border-radius: 8px; border-top-right-radius: 0; max-width: 85%; font-size: 0.95rem; line-height: 1.5; white-space: pre-wrap;">${content}</div>
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