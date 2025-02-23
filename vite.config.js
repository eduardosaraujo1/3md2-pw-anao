import { defineConfig, loadEnv } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd());

    return {
        server: {
            host: env.VITE_HOST ?? '0.0.0.0',
            port: env.VITE_PORT ?? 5173,
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
    };
});
