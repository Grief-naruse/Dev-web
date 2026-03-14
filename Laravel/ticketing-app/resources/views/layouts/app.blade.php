<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing App - Laravel</title>
    
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <h2>Gestion-Tickets</h2>
            <nav>
                <ul>
                    <li><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">📊 Tableau de bord</a></li>
                    
                    <li>
                        <a href="{{ route('clients.index') }}" class="{{ Request::is('clients*') ? 'active' : '' }}">🏢 Clients</a>
                    </li>

                    <li><a href="{{ url('/projects') }}" class="{{ Request::is('projects*') ? 'active' : '' }}">📁 Projets</a></li>
                    <li><a href="{{ url('/tickets') }}" class="{{ Request::is('tickets*') ? 'active' : '' }}">🎫 Tickets</a></li>
                </ul>
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid rgba(255,255,255,0.1);">
                <ul>
                    <li><a href="{{ url('/profile') }}" class="{{ Request::is('profile') ? 'active' : '' }}">👤 Mon Profil</a></li>
                    <li><a href="{{ url('/settings') }}" class="{{ Request::is('settings') ? 'active' : '' }}">⚙️ Paramètres</a></li>
                </ul>
            </nav>
            <div class="user-info" style="margin-top: auto; padding-top: 20px; font-size: 0.9rem; opacity: 0.8;">
                <p>Connecté en tant que :</p>
                <strong>{{ auth()->user()->name ?? 'Ilan Rubaud' }}</strong>
            </div>
        </aside>

        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success" style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>