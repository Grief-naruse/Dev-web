<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Client - {{ $client->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-6xl mx-auto">
        <nav class="mb-6">
            <a href="{{ route('clients.index') }}" class="text-blue-600 hover:underline">← Retour à la liste</a>
        </nav>

        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900">{{ $client->name }}</h1>
                <p class="text-gray-500">Fiche client détaillée et suivi des contrats</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <span class="block text-sm text-gray-500 uppercase font-bold">Projets totaux</span>
                <span class="text-2xl font-bold text-blue-600">{{ $client->projects->count() }}</span>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-4">Projets en cours</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($client->projects as $project)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-900">{{ $project->name }}</h3>
                        <span class="px-2 py-1 text-xs font-bold uppercase rounded {{ $project->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $project->status }}
                        </span>
                    </div>

                    <p class="text-gray-600 text-sm mb-6 line-clamp-2">{{ $project->description }}</p>

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm font-medium">
                            <span class="text-gray-500">Consommation du forfait</span>
                            <span class="{{ $project->remaining_hours < 0 ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $project->total_spent_hours }} / {{ $project->included_hours }} h
                            </span>
                        </div>
                        
                        @php
                            $percentage = ($project->total_spent_hours / $project->included_hours) * 100;
                            $barColor = $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-orange-400' : 'bg-blue-500');
                        @endphp

                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="{{ $barColor }} h-2.5 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                        
                        <p class="text-xs text-right {{ $project->remaining_hours < 0 ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                            @if($project->remaining_hours < 0)
                                Dépassement de {{ abs($project->remaining_hours) }} heures
                            @else
                                Reste {{ $project->remaining_hours }} heures disponibles
                            @endif
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-500">{{ $project->tickets->count() }} tickets ouverts</span>
                    <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-800">Gérer le projet →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>