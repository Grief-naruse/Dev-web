<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion-Tickets ERP</title>
    
    <style>
        body { 
            margin: 0; 
            font-family: system-ui, -apple-system, sans-serif; 
            background-color: #f4f6f9; 
            display: flex; 
            min-height: 100vh; 
        }
        
        /* La fameuse barre latérale sombre */
        .sidebar { 
            width: 250px; 
            background-color: #2c3e50; 
            color: white; 
            display: flex; 
            flex-direction: column; 
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 10;
        }
        
        .sidebar-header { 
            padding: 20px; 
            font-size: 1.5rem; 
            font-weight: bold; 
            border-bottom: 1px solid #34495e; 
            text-align: center;
        }
        
        .nav-link { 
            display: block; 
            padding: 15px 25px; 
            color: #bdc3c7; 
            text-decoration: none; 
            font-weight: bold;
            transition: all 0.2s; 
            border-left: 4px solid transparent;
        }
        
        .nav-link:hover { 
            background-color: #34495e; 
            color: white; 
            border-left: 4px solid #3498db;
        }
        
        /* Conteneur de la page de droite */
        .main-content { 
            flex: 1; 
            overflow-y: auto; 
            padding: 20px;
        }
        
        /* Le bloc en bas à gauche pour l'utilisateur connecté */
        .user-info { 
            margin-top: auto; 
            padding: 20px; 
            background-color: #1a252f; 
        }
        
        .logout-btn { 
            background-color: transparent; 
            border: 1px solid #e74c3c; 
            color: #e74c3c; 
            cursor: pointer; 
            padding: 8px 15px; 
            font-weight: bold; 
            border-radius: 4px;
            width: 100%;
            transition: 0.2s;
            margin-top: 15px;
        }
        
        .logout-btn:hover {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            Gestion-Tickets
        </div>
        
        <nav style="flex: 1; margin-top: 20px;">
            <a href="{{ url('/dashboard') }}" class="nav-link">📊 Tableau de bord</a>
            
            @if(Auth::user()->isAdmin())
                <a href="{{ url('/clients') }}" class="nav-link">🏢 Clients</a>
            @endif

            <a href="{{ url('/projects') }}" class="nav-link">📁 Projets</a>
            <a href="{{ url('/tickets') }}" class="nav-link">🎫 Tickets</a>
        </nav>

        <div class="user-info">
            <div style="font-size: 0.85rem; color: #7f8c8d; margin-bottom: 5px;">Connecté en tant que :</div>
            
            <div style="font-weight: bold; font-size: 1.1rem; color: white;">
                {{ Auth::user()->name }}
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Se déconnecter</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>