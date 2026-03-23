@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 20px; max-width: 900px; margin: auto;">

        <div style="margin-bottom: 20px;">
            <a href="{{ route('tickets.show', $ticket) }}"
                style="color: #7f8c8d; text-decoration: none; font-weight: bold;">← Annuler et retourner au ticket</a>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="font-size: 1.8rem; color: #2c3e50; margin: 0; font-weight: bold;">Mettre à jour le ticket</h1>
            <span
                style="background-color: #ecf0f1; color: #2c3e50; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;">ID:
                #{{ $ticket->id }}</span>
        </div>

        <div class="card"
            style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label for="project_id"
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Projet rattaché *</label>
                    <select name="project_id" id="project_id"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px; background-color: #f9f9f9;"
                        required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $ticket->project_id) == $project->id ? 'selected' : '' }}>
                                📁 {{ $project->name }} ({{ $project->client?->name ?? 'Client inconnu' }})
                            </option>
                        @endforeach
                    </select>
                    @error('project_id') <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="title" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Titre du ticket *</label>
                    <input type="text" name="title" id="title"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                        value="{{ old('title', $ticket->title) }}" required>
                    @error('title') <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="description" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Description détaillée</label>
                    <textarea name="description" id="description"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                        rows="5">{{ old('description', $ticket->description) }}</textarea>
                    @error('description') <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1; min-width: 200px;">
                        <label for="status" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Statut *</label>
                        <select name="status" id="status" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <option value="todo" {{ old('status', $ticket->status) == 'todo' ? 'selected' : '' }}>À faire</option>
                            <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="in_review" {{ old('status', $ticket->status) == 'in_review' ? 'selected' : '' }}>En revue</option>
                            <option value="completed" {{ old('status', $ticket->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <label for="priority" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Niveau d'urgence *</label>
                        <select name="priority" id="priority" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>🟢 Basse</option>
                            <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>🟡 Normale</option>
                            <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>🟠 Haute</option>
                            <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>🔴 Urgente</option>
                        </select>
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <label for="type" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Type de facturation *</label>
                        <select name="type" id="type" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <option value="included" {{ old('type', $ticket->type) == 'included' ? 'selected' : '' }}>📦 Inclus dans le forfait</option>
                            <option value="billable" {{ old('type', $ticket->type) == 'billable' ? 'selected' : '' }}>💸 Hors-forfait</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <label style="font-weight: bold; color: #34495e; margin: 0;">Équipe assignée</label>
                        <button type="button" onclick="openTeamModal()" style="background: #edf2f7; color: #2c3e50; border: 1px solid #cbd5e0; padding: 6px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; cursor: pointer; transition: 0.2s;">
                            🔍 Gérer l'équipe
                        </button>
                    </div>
                    
                    <div id="selected-team-display" style="display: flex; flex-wrap: wrap; gap: 10px; min-height: 40px; padding: 10px; border: 1px dashed #bdc3c7; border-radius: 6px; background: #fafafa;">
                        <span style="color: #95a5a6; font-size: 0.9rem; font-style: italic; margin: auto 0;">Chargement de l'équipe...</span>
                    </div>
                    @error('assignees') <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 30px; max-width: 200px;">
                    <label for="estimated_hours" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Estimation (Heures)</label>
                    <input type="number" step="0.5" min="0" name="estimated_hours" id="estimated_hours"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                        value="{{ old('estimated_hours', $ticket->estimated_hours) }}">
                </div>

                <div style="text-align: right; border-top: 1px solid #ecf0f1; padding-top: 20px;">
                    <button type="submit" style="background-color: #f39c12; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-weight: bold; font-size: 1rem; cursor: pointer;">
                        Enregistrer les modifications
                    </button>
                </div>

                <div id="teamModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
                    <div style="background: white; width: 90%; max-width: 500px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; flex-direction: column; max-height: 80vh; overflow: hidden;">
                        
                        <div style="padding: 20px; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                            <h3 style="margin: 0; color: #2c3e50; font-size: 1.1rem;">Assigner au ticket</h3>
                            <button type="button" onclick="closeTeamModal()" style="background: none; border: none; font-size: 1.5rem; color: #7f8c8d; cursor: pointer; line-height: 1;">&times;</button>
                        </div>

                        <div style="padding: 15px 20px; border-bottom: 1px solid #edf2f7;">
                            <input type="text" id="teamSearchInput" onkeyup="filterTeam()" placeholder="Rechercher par nom..." style="width: 100%; padding: 10px 15px; border: 1px solid #cbd5e0; border-radius: 20px; font-size: 0.95rem; outline: none;">
                        </div>

                        <div id="teamListContainer" style="overflow-y: auto; flex: 1; padding: 10px 20px;">
                            </div>
                        <div id="noResultsMsg" style="display: none; text-align: center; padding: 20px; color: #94a3b8; font-style: italic;">Aucun collaborateur trouvé.</div>

                        <div style="padding: 15px 20px; background: #f8fafc; border-top: 1px solid #edf2f7; text-align: right;">
                            <button type="button" onclick="closeTeamModal()" style="background: #3498db; color: white; border: none; padding: 8px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Valider la sélection</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const projectSelect = document.getElementById('project_id');
        const teamListContainer = document.getElementById('teamListContainer');
        const displayArea = document.getElementById('selected-team-display');
        const modal = document.getElementById('teamModal');
        
        // Identifiants cochés par défaut
        const currentAssignees = @json($ticket->assignees->pluck('id'));

        function openTeamModal() {
            modal.style.display = 'flex';
            document.getElementById('teamSearchInput').focus();
        }
        
        function closeTeamModal() {
            modal.style.display = 'none';
        }

        function filterTeam() {
            let input = document.getElementById('teamSearchInput').value.toLowerCase();
            let items = document.querySelectorAll('.team-member-item');
            let visibleCount = 0;

            items.forEach(item => {
                let name = item.querySelector('.member-name').innerText.toLowerCase();
                if (name.includes(input)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            document.getElementById('noResultsMsg').style.display = visibleCount === 0 ? 'block' : 'none';
        }

        // Mettre à jour l'UI des bulles
        function updateSelectedTeamUI() {
            displayArea.innerHTML = '';
            let hasSelection = false;
            
            // On cherche toutes les checkboxes qui ont été créées dans la modale
            const checkboxes = document.querySelectorAll('.team-member-item input[type="checkbox"]');

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    hasSelection = true;
                    let name = cb.getAttribute('data-name');
                    let avatar = cb.getAttribute('data-avatar');
                    let initial = cb.getAttribute('data-initial');

                    let badge = document.createElement('div');
                    badge.style.cssText = 'display: flex; align-items: center; gap: 8px; background: white; border: 1px solid #cbd5e0; padding: 4px 10px 4px 4px; border-radius: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);';
                    
                    let imgHtml = avatar 
                        ? `<img src="${avatar}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">` 
                        : `<div style="width: 24px; height: 24px; border-radius: 50%; background: #2c3e50; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: bold;">${initial}</div>`;

                    badge.innerHTML = `${imgHtml}<span style="font-size: 0.85rem; font-weight: 600; color: #2c3e50;">${name}</span>`;
                    displayArea.appendChild(badge);
                }
            });

            if (!hasSelection) {
                displayArea.innerHTML = '<span style="color: #95a5a6; font-size: 0.9rem; font-style: italic; margin: auto 0;">Aucun membre sélectionné</span>';
            }
        }

        // Requête AJAX pour charger les membres d'un projet
        function loadTeam(projectId) {
            if (!projectId) {
                teamListContainer.innerHTML = '';
                displayArea.innerHTML = '<span style="color: #95a5a6; font-size: 0.9rem; font-style: italic; margin: auto 0;">Sélectionnez d\'abord un projet...</span>';
                return;
            }

            teamListContainer.innerHTML = '<div style="text-align:center; padding: 20px; color: #3498db;">Chargement de l\'équipe...</div>';

            fetch(`/api/projects/${projectId}/team`)
                .then(response => response.json())
                .then(users => {
                    teamListContainer.innerHTML = ''; 

                    if(users.length === 0) {
                        teamListContainer.innerHTML = '<div style="text-align:center; padding: 20px; color: #e74c3c;">Aucun membre assigné au projet parent.</div>';
                        updateSelectedTeamUI();
                        return;
                    }

                    users.forEach(user => {
                        const isChecked = currentAssignees.includes(user.id) ? 'checked' : '';
                        const avatarSrc = user.avatar ? `/storage/avatars/${user.avatar}` : '';
                        const initial = user.name.substring(0, 1);
                        const roleLabel = user.role === 'admin' ? 'Manager' : 'Collaborateur';

                        const avatarHtml = user.avatar
                            ? `<img src="${avatarSrc}" style="width: 100%; height: 100%; object-fit: cover;">`
                            : `<span style="color: white; font-size: 0.75rem; font-weight: bold;">${initial}</span>`;

                        const label = document.createElement('label');
                        label.className = 'team-member-item';
                        label.style.cssText = 'display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: 0.2s;';
                        label.onmouseover = function() { this.style.background = '#f8fafc'; };
                        label.onmouseout = function() { this.style.background = 'transparent'; };

                        label.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; background: #2c3e50; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                    ${avatarHtml}
                                </div>
                                <div>
                                    <div style="color: #2c3e50; font-weight: bold; font-size: 0.95rem;" class="member-name">${user.name}</div>
                                    <div style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase;">${roleLabel}</div>
                                </div>
                            </div>
                            <input type="checkbox" name="assignees[]" value="${user.id}" data-name="${user.name}" data-avatar="${avatarSrc}" data-initial="${initial}" onchange="updateSelectedTeamUI()" style="transform: scale(1.2); accent-color: #3498db;" ${isChecked}>
                        `;

                        teamListContainer.appendChild(label);
                    });

                    updateSelectedTeamUI(); // Dessine les bulles une fois la modale remplie
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    teamListContainer.innerHTML = '<div style="text-align:center; padding: 20px; color: #e74c3c;">Erreur réseau.</div>';
                });
        }

        // Chargement initial
        if (projectSelect.value) {
            loadTeam(projectSelect.value);
        }

        // Recharger l'équipe si on change de projet
        projectSelect.addEventListener('change', function() {
            // Optionnel : On peut vider les anciens "currentAssignees" quand on change de projet
            currentAssignees.length = 0; 
            loadTeam(this.value);
        });
    </script>
@endsection