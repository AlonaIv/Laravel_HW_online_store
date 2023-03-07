import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/images-preview.js',
                'resources/js/payments/paypal.js'
            ],
            refresh: true,
        }),
    ],
});
