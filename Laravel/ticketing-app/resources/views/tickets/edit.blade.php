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
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Projet rattaché
                        *</label>
                    <select name="project_id" id="project_id"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px; background-color: #f9f9f9;"
                        required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $ticket->project_id) == $project->id ? 'selected' : '' }}>
                                📁 {{ $project->name }} ({{ $project->client?->name ?? 'Client inconnu' }})
                            </option>
                        @endforeach
                    </select>
                    @error('project_id') <span
                        style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="title" style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Titre
                        du ticket *</label>
                    <input type="text" name="title" id="title"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                        value="{{ old('title', $ticket->title) }}" required>
                    @error('title') <span
                        style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="description"
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Description
                        détaillée</label>
                    <textarea name="description" id="description"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                        rows="5">{{ old('description', $ticket->description) }}</textarea>
                    @error('description') <span
                        style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">

                    <div style="flex: 1; min-width: 250px;" id="assignees-wrapper">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Équipe
                            assignée</label>

                        <div style="position: relative;">
                            <button type="button" id="toggle-assignees"
                                style="width: 100%; padding: 10px 15px; border: 1px solid #bdc3c7; border-radius: 4px; background: #fff; text-align: left; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem; color: #2c3e50;">
                                <span id="assignees-button-text">Sélectionner l'équipe...</span>
                                <span style="font-size: 0.8rem; color: #7f8c8d;">▼</span>
                            </button>

                            <div id="assignees-dropdown"
                                style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #bdc3c7; border-top: none; border-radius: 0 0 4px 4px; z-index: 100; max-height: 250px; overflow-y: auto; box-shadow: 0 10px 15px rgba(0,0,0,0.1);">
                                <div id="assignees-list"
                                    style="padding: 15px; display: flex; flex-direction: column; gap: 12px;">
                                    <span style="color: #7f8c8d; font-style: italic; font-size: 0.9rem;">Sélectionnez
                                        d'abord un projet...</span>
                                </div>
                            </div>
                        </div>

                        @error('assignees') <span
                            style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <label for="status"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Statut *</label>
                        <select name="status" id="status"
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <option value="todo" {{ old('status', $ticket->status) == 'todo' ? 'selected' : '' }}>À faire
                            </option>
                            <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="in_review" {{ old('status', $ticket->status) == 'in_review' ? 'selected' : '' }}>En
                                revue</option>
                            <option value="completed" {{ old('status', $ticket->status) == 'completed' ? 'selected' : '' }}>
                                Terminé</option>
                        </select>
                        @error('status') <span
                            style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <label for="priority"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Niveau d'urgence
                            *</label>
                        <select name="priority" id="priority"
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>🟢 Basse
                            </option>
                            <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>🟡
                                Normale</option>
                            <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>🟠 Haute
                            </option>
                            <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>🔴
                                Urgente</option>
                        </select>
                        @error('priority') <span
                            style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <label for="type"
                            style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Type de
                            facturation *</label>
                        <select name="type" id="type"
                            style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <option value="included" {{ old('type', $ticket->type) == 'included' ? 'selected' : '' }}>📦
                                Inclus dans le forfait</option>
                            <option value="billable" {{ old('type', $ticket->type) == 'billable' ? 'selected' : '' }}>💸
                                Hors-forfait (Facturable)</option>
                        </select>
                        @error('type') <span
                            style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="margin-bottom: 30px; max-width: 200px;">
                    <label for="estimated_hours"
                        style="display: block; font-weight: bold; margin-bottom: 8px; color: #2c3e50;">Estimation
                        (Heures)</label>
                    <input type="number" step="0.5" min="0" name="estimated_hours" id="estimated_hours"
                        style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;"
                        value="{{ old('estimated_hours', $ticket->estimated_hours) }}">
                </div>

                <div style="text-align: right; border-top: 1px solid #ecf0f1; padding-top: 20px;">
                    <button type="submit"
                        style="background-color: #f39c12; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-weight: bold; font-size: 1rem; cursor: pointer;">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectSelect = document.getElementById('project_id');
        const assigneesList = document.getElementById('assignees-list');
        const toggleButton = document.getElementById('toggle-assignees');
        const dropdown = document.getElementById('assignees-dropdown');
        const buttonText = document.getElementById('assignees-button-text');
        
        // Les identifiants de l'équipe actuellement assignée (fournis par Laravel)
        const currentAssignees = @json($ticket->assignees->pluck('id'));

        // 1. GESTION DE L'AFFICHAGE (Ouvrir/Fermer la liste)
        toggleButton.addEventListener('click', function() {
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });

        // Fermer la liste si on clique ailleurs sur la page
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#assignees-wrapper')) {
                dropdown.style.display = 'none';
            }
        });

        // 2. METTRE À JOUR LE TEXTE DU BOUTON
        function updateButtonText() {
            const checkedBoxes = document.querySelectorAll('.assignee-checkbox:checked');
            if (checkedBoxes.length === 0) {
                buttonText.textContent = "Aucune personne assignée";
                buttonText.style.color = "#7f8c8d";
            } else if (checkedBoxes.length === 1) {
                // S'il n'y a qu'une personne, on affiche son nom
                buttonText.textContent = "👤 " + checkedBoxes[0].dataset.name;
                buttonText.style.color = "#2c3e50";
                buttonText.style.fontWeight = "bold";
            } else {
                buttonText.textContent = `👥 ${checkedBoxes.length} personnes sélectionnées`;
                buttonText.style.color = "#3498db";
                buttonText.style.fontWeight = "bold";
            }
        }

        // 3. LA REQUÊTE AJAX POUR RÉCUPÉRER L'ÉQUIPE
        function loadTeam(projectId) {
            if (!projectId) {
                assigneesList.innerHTML = '<span style="color: #7f8c8d; font-style: italic; font-size: 0.9rem;">Sélectionnez d\'abord un projet...</span>';
                updateButtonText();
                return;
            }

            assigneesList.innerHTML = '<span style="color: #3498db; font-size: 0.9rem;">Chargement...</span>';

            fetch(`/api/projects/${projectId}/team`)
                .then(response => response.json())
                .then(users => {
                    assigneesList.innerHTML = ''; // On vide la liste

                    if(users.length === 0) {
                        assigneesList.innerHTML = '<span style="color: #e74c3c; font-size: 0.9rem;">Aucun membre sur ce projet</span>';
                        updateButtonText();
                        return;
                    }

                    // On fabrique les cases à cocher pour chaque utilisateur
                    users.forEach(user => {
                        const isChecked = currentAssignees.includes(user.id) ? 'checked' : '';
                        
                        // Création du label cliquable
                        const label = document.createElement('label');
                        label.style.display = 'flex';
                        label.style.alignItems = 'center';
                        label.style.gap = '10px';
                        label.style.cursor = 'pointer';
                        label.style.fontSize = '0.95rem';
                        label.style.color = '#34495e';

                        label.innerHTML = `
                            <input type="checkbox" name="assignees[]" value="${user.id}" data-name="${user.name}" class="assignee-checkbox" ${isChecked} style="width: 16px; height: 16px; cursor: pointer;">
                            👤 ${user.name}
                        `;
                        
                        assigneesList.appendChild(label);
                    });

                    // On ajoute un "écouteur" sur chaque case pour mettre à jour le bouton quand on clique
                    document.querySelectorAll('.assignee-checkbox').forEach(cb => {
                        cb.addEventListener('change', updateButtonText);
                    });

                    updateButtonText(); // Mise à jour initiale
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    assigneesList.innerHTML = '<span style="color: #e74c3c; font-size: 0.9rem;">Erreur réseau.</span>';
                });
        }

        // 4. DÉCLENCHEURS (Au chargement, et quand on change de projet)
        if (projectSelect.value) {
            loadTeam(projectSelect.value);
        }

        projectSelect.addEventListener('change', function() {
            loadTeam(this.value);
        });
    });
</script>
@endsection