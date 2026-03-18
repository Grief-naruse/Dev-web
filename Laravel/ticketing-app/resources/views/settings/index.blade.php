@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 1200px; margin: auto;">

    <div style="margin-bottom: 30px;">
        <h1 class="page-title">Paramètres de l'application</h1>
        <p class="text-muted">Personnalisez votre expérience, la langue et vos alertes sur l'ERP.</p>
    </div>

    @if (session('success'))
        <div style="background-color: var(--success-color); color: white; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf

        <div class="grid-2" style="align-items: start;">
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                
                <div class="card" style="margin-bottom: 0;">
                    <h2 class="section-title">🎨 Apparence</h2>
                    <p class="text-muted" style="font-size: 0.85rem;">Gérez le thème visuel de l'interface.</p>
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background-color: var(--bg-color); border-radius: 4px; border: 1px solid var(--border-color);">
                        <div>
                            <div style="font-weight: bold; color: var(--text-color);">Mode Sombre</div>
                            <div style="font-size: 0.8rem; color: #7f8c8d;">Réduit la fatigue visuelle</div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="darkModeToggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <h2 class="section-title">🌍 Localisation</h2>
                    <p class="text-muted" style="font-size: 0.85rem;">Langue et formats de date.</p>
                    
                    <div class="form-group">
                        <label class="form-label">Langue de l'interface</label>
                        <select class="form-input" name="language">
                            <option value="fr" selected>🇫🇷 Français</option>
                            <option value="en">🇬🇧 English</option>
                            <option value="es">🇪🇸 Español</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Fuseau horaire</label>
                        <select class="form-input" name="timezone">
                            <option value="Europe/Paris" selected>Europe/Paris (UTC+1)</option>
                            <option value="America/New_York">America/New_York (UTC-5)</option>
                        </select>
                    </div>
                </div>

            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                
                <div class="card" style="margin-bottom: 0;">
                    <h2 class="section-title">🔔 Notifications par e-mail</h2>
                    <p class="text-muted" style="font-size: 0.85rem;">Sélectionnez les alertes que vous souhaitez recevoir.</p>
                    
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="notif_new_ticket" checked style="width: 18px; height: 18px; accent-color: var(--accent-color);">
                            <span style="color: var(--text-color);">Lorsqu'un ticket m'est assigné</span>
                        </label>
                        
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="notif_ticket_closed" checked style="width: 18px; height: 18px; accent-color: var(--accent-color);">
                            <span style="color: var(--text-color);">Lorsqu'un de mes tickets est clôturé</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="notif_daily_summary" style="width: 18px; height: 18px; accent-color: var(--accent-color);">
                            <span style="color: var(--text-color);">Recevoir un résumé d'activité quotidien</span>
                        </label>
                    </div>
                </div>

            </div>

        </div>

        </div> <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; width: 100%;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 25px; font-size: 1rem;">
                💾 Enregistrer les préférences
            </button>
        </div>

    </form>

</div>
@endsection