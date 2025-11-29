import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { fileURLToPath } from 'url';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/pages/alternatives.js',
                'resources/js/pages/anp.js',
                'resources/js/pages/criteria.js',
                'resources/js/pages/dashboard.js',
                'resources/js/pages/decision-makers.js',
                'resources/js/pages/pairwise.js',
                'resources/js/pages/profile.js',
                'resources/js/pages/ratings.js',
                'resources/js/pages/results.js',
                'resources/js/pages/my-results.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url))
        }
    }
});
