import { defineConfig, loadEnv } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';
import path from 'path';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd());

    return {
        server: {
            host: env.VITE_HOST ?? 'localhost',
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
        plugins: [
            tailwindcss(),
            {
                // creates a file containing the server ip while the server is running
                // used by framework to determine if should use vite server or
                name: 'hot-file-handler',
                configureServer() {
                    const hotFilePath = path.resolve(__dirname, 'public/hot');
                    const devServerUrl = `http://localhost:${
                        env.VITE_PORT ?? 5173
                    }`;

                    fs.writeFileSync(hotFilePath, devServerUrl);

                    const cleanUp = () => {
                        if (fs.existsSync(hotFilePath)) {
                            fs.rmSync(hotFilePath);
                        }
                    };

                    process.on('exit', cleanUp);
                    process.on('SIGINT', () => process.exit());
                    process.on('SIGTERM', () => process.exit());
                    process.on('SIGHUP', () => process.exit());
                },
            },
        ],
        publicDir: false,
    };
});
