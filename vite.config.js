import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

const isDdev = process.env.IS_DDEV_PROJECT === 'true';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        ...(isDdev ? {
            origin: 'https://shopping-list-app-2026.ddev.site:5173',
        } : {}),
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
