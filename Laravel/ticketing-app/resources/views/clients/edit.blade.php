<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client - {{ $client->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-2xl mx-auto">
        <nav class="mb-6">
            <a href="{{ route('clients.index') }}" class="text-blue-600 hover:underline">← Annuler</a>
        </nav>

        <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier le client : {{ $client->name }}</h1>

            <form action="{{ route('clients.update', $client) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom de l'entreprise</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $client->name) }}"
                           class="w-full px-4 py-3 rounded-lg border @error('name') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition">
                        Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>