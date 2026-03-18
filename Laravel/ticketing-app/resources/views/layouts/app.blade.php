<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion-Tickets ERP</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="app-layout">
        
        <aside class="sidebar">
            <h2 style="padding-bottom: 20px; border-bottom: 1px solid #34495e; text-align: center; margin-bottom: 0;">ERP</h2>
            
            <nav style="flex: 1;">
                <ul>
                    <li><a href="{{ url('/dashboard') }}">📊 Tableau de bord</a></li>
                    
                    @if(Auth::user()->isAdmin())
                        <li><a href="{{ url('/clients') }}">🏢 Clients</a></li>
                    @endif

                    <li><a href="{{ url('/projects') }}">📁 Projets</a></li>
                    <li><a href="{{ url('/tickets') }}">🎫 Tickets</a></li>
                    
                    <li style="margin-top: 20px; border-top: 1px solid #34495e; padding-top: 10px;">
                        <a href="{{ url('/profile') }}">👤 Mon Profil</a>
                    </li>
                    
                    <li>
                        <a href="{{ route('settings.index') }}">⚙️ Paramètres</a>
                    </li>
                </ul>
            </nav>

            <div style="background-color: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background-color: rgba(255,255,255,0.1); border: 2px solid #ecf0f1; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; overflow: hidden;">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatarUrl() }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="color: #bdc3c7;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: #bdc3c7;">Connecté(e) :</div>
                        <div style="font-weight: bold; color: white;">{{ Auth::user()->name }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width: 100%; padding: 8px; font-size: 0.85rem;">🗑️ Déconnexion</button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>
        
    </div>

    <script>
        const body = document.body;

        // 1. Applique le thème globalement sur TOUTES les pages
        if (localStorage.getItem('dark-mode') === 'enabled') {
            body.classList.add('dark-mode');
        }

        // 2. Gère le bouton UNIQUEMENT s'il est présent sur la page (page paramètres)
        const toggle = document.getElementById('darkModeToggle');
        if (toggle) {
            // Met le bouton dans le bon état au chargement
            if (localStorage.getItem('dark-mode') === 'enabled') {
                toggle.checked = true;
            }

            // Écoute les clics
            toggle.addEventListener('change', () => {
                if (toggle.checked) {
                    body.classList.add('dark-mode');
                    localStorage.setItem('dark-mode', 'enabled');
                } else {
                    body.classList.remove('dark-mode');
                    localStorage.setItem('dark-mode', 'disabled');
                }
            });
        }
    </script>
</body>
</html>