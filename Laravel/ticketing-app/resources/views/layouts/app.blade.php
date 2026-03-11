<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticketing App - Laravel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-container" style="display: flex;">
        <aside class="sidebar">
            <h2>Ticketing App</h2>
            <nav>
                <ul>
                    <li><a href="{{ url('/') }}">Tableau de bord</a></li>
                    <li><a href="{{ url('/projects') }}">Projets</a></li>
                    <li><a href="{{ url('/tickets') }}">Tickets</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content" style="flex: 1; padding: 20px;">
            @yield('content') </main>
    </div>
</body>
</html>