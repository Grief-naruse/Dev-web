@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px; max-width: 800px; margin: auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/projects') }}" style="color: #3498db; text-decoration: none; font-weight: bold;">← Annuler et retourner à la liste</a>
    </div>

    <div class="card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ecf0f1; padding-bottom: 15px; margin-bottom: 25px;">
            <h1 style="font-size: 1.8rem; color: #2c3e50; margin: 0;">
                Modifier le projet : <span style="color: #3498db;">{{ $project->name }}</span>
            </h1>
            <span style="background-color: #ecf0f1; padding: 5px 10px; border-radius: 4px; font-size: 0.9rem; color: #7f8c8d; font-weight: bold;">
                ID: #{{ $project->id }}
            </span>
        </div>

        <form action="{{ url('/projects/' . $project->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="client_id" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Client facturé *</label>
                <select name="client_id" id="client_id" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px; background-color: #f9f9f9;">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Nom du Projet *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                @error('name')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Cahier des charges / Description</label>
                <textarea name="description" id="description" rows="5" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div style="flex: 1;">
                    <label for="status" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Statut</label>
                    <select name="status" id="status" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>En cours (Actif)</option>
                        <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>En attente</option>
                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                    </select>
                    @error('status')
                        <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="flex: 1;">
                    <label for="included_hours" style="display: block; font-weight: bold; margin-bottom: 8px; color: #34495e;">Forfait d'heures vendues *</label>
                    <input type="number" name="included_hours" id="included_hours" value="{{ old('included_hours', $project->included_hours) }}" min="0" style="width: 100%; padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px;">
                    @error('included_hours')
                        <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <label style="font-weight: bold; color: #34495e; margin: 0;">Équipe affectée au projet</label>
                    <button type="button" onclick="openTeamModal()" style="background: #edf2f7; color: #2c3e50; border: 1px solid #cbd5e0; padding: 6px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; cursor: pointer; transition: 0.2s;">
                        🔍 Gérer l'équipe
                    </button>
                </div>
                
                <div id="selected-team-display" style="display: flex; flex-wrap: wrap; gap: 10px; min-height: 40px; padding: 10px; border: 1px dashed #bdc3c7; border-radius: 6px; background: #fafafa;">
                    <span style="color: #95a5a6; font-size: 0.9rem; font-style: italic; margin: auto 0;">Aucun membre sélectionné</span>
                </div>
                
                @error('users')
                    <span style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" style="width: 100%; background-color: #f39c12; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer;">
                Sauvegarder les modifications
            </button>

            <div id="teamModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
                <div style="background: white; width: 90%; max-width: 500px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; flex-direction: column; max-height: 80vh; overflow: hidden;">
                    
                    <div style="padding: 20px; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                        <h3 style="margin: 0; color: #2c3e50; font-size: 1.1rem;">Assigner des collaborateurs</h3>
                        <button type="button" onclick="closeTeamModal()" style="background: none; border: none; font-size: 1.5rem; color: #7f8c8d; cursor: pointer; line-height: 1;">&times;</button>
                    </div>

                    <div style="padding: 15px 20px; border-bottom: 1px solid #edf2f7;">
                        <input type="text" id="teamSearchInput" onkeyup="filterTeam()" placeholder="Rechercher par nom..." style="width: 100%; padding: 10px 15px; border: 1px solid #cbd5e0; border-radius: 20px; font-size: 0.95rem; outline: none;">
                    </div>

                    <div id="teamListContainer" style="overflow-y: auto; flex: 1; padding: 10px 20px;">
                        @foreach($users as $u)
                            <label class="team-member-item" style="display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; background: #2c3e50; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                        @if($u->avatar)
                                            <img src="{{ asset('storage/avatars/' . $u->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span style="color: white; font-size: 0.75rem; font-weight: bold;">{{ substr($u->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="color: #2c3e50; font-weight: bold; font-size: 0.95rem;" class="member-name">{{ $u->name }}</div>
                                        <div style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase;">{{ $u->role === 'admin' ? 'Manager' : 'Collaborateur' }}</div>
                                    </div>
                                </div>
                                <input type="checkbox" name="users[]" value="{{ $u->id }}" data-name="{{ $u->name }}" data-avatar="{{ $u->avatar ? asset('storage/avatars/' . $u->avatar) : '' }}" data-initial="{{ substr($u->name, 0, 1) }}" onchange="updateSelectedTeamUI()" style="transform: scale(1.2); accent-color: #3498db;" 
                                @if(in_array($u->id, old('users', $project->users->pluck('id')->toArray()))) checked @endif>
                            </label>
                        @endforeach
                        <div id="noResultsMsg" style="display: none; text-align: center; padding: 20px; color: #94a3b8; font-style: italic;">Aucun collaborateur trouvé.</div>
                    </div>

                    <div style="padding: 15px 20px; background: #f8fafc; border-top: 1px solid #edf2f7; text-align: right;">
                        <button type="button" onclick="closeTeamModal()" style="background: #3498db; color: white; border: none; padding: 8px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Valider la sélection</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('teamModal');
    const displayArea = document.getElementById('selected-team-display');
    const checkboxes = document.querySelectorAll('.team-member-item input[type="checkbox"]');

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

    function updateSelectedTeamUI() {
        displayArea.innerHTML = '';
        let hasSelection = false;

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

    document.addEventListener('DOMContentLoaded', updateSelectedTeamUI);
</script>
@endsection