import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
        // HMR seguro para HTTPS
        hmr: {
            protocol: 'wss',
            host: 'gestion-mn13.onrender.com', // tu dominio en Render
        },
    },
    build: {
        // base path relativo, importante para producción HTTPS
        // si tus assets están en root, déjalo como '/'
        // si usas un subdirectorio, pon '/subdir/'
        // Esto asegura que los assets generados apunten a HTTPS
        outDir: 'public/build',
        emptyOutDir: true,
    },
});
