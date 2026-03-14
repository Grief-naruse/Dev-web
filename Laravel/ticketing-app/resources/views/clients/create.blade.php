<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Client - Enterprise Ready</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-2xl mx-auto">
        <nav class="mb-6">
            <a href="{{ route('clients.index') }}" class="text-blue-600 hover:underline">← Annuler et retourner à la liste</a>
        </nav>

        <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Enregistrer un nouveau client</h1>

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom de l'entreprise</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 rounded-lg border @error('name') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="ex: Google France">
                    
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                    Créer la fiche client
                </button>
            </form>
        </div>
    </div>
</body>
</html>