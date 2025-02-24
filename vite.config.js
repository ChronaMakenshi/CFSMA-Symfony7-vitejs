import { defineConfig } from 'vite';
import symfonyPlugin from 'vite-plugin-symfony';
import path from 'path';

export default defineConfig({
    plugins: [
        symfonyPlugin(),
    ],
    resolve: {
        alias: {
            '@images': '/assets/images',
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
    build: {
        manifest: true,
        rollupOptions: {
            input: {
                app: "./assets/app.js"
            },
        }
    },
});
