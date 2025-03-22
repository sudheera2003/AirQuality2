import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/Header.css', 'resources/css/footer.css', 'resources/css/flash.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
