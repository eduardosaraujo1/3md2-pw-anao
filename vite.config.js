import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
        allowedHosts: [
            'vite', // for accessing the vite server from PHP
        ],
        host: process.env.VITE_HOST || '0.0.0.0',
        port: process.env.VITE_PORT ?? 5173,
    },
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: ['resources/css/app.css', 'resources/js/app.js'],
        },
    },
    plugins: [tailwindcss()],
    publicDir: false,
});
