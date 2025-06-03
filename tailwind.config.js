import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            keyframes: {
                'purple-to-blue': {
                    '0%': { color: '#8b5cf6' }, // purple-500
                    '100%': { color: '#3b82f6' }, // blue-500
                }
            },
            animation: {
                'purple-to-blue': 'purple-to-blue 2s ease-in-out infinite alternate',
            }
        },
        fontFamily: {
            sans: ['Figtree', ...defaultTheme.fontFamily.sans],
        },
    },

    plugins: [forms,
        require('flowbite/plugin')
    ],
};
