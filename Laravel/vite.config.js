import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // On indique à Vite le fichier exact qu'il doit compiler pour Laravel
            input: ['resources/js/app.js'],
            refresh: true,
        }),
    ],
});