import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
                display: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#1e1b4b',
                    hover: '#15133a',
                    light: '#312e81',
                    container: '#e8e6f0',
                },
                accent: {
                    DEFAULT: '#c9a84c',
                    hover: '#b8963f',
                    container: '#fdf8ec',
                },
                background: '#faf8f5',
                surface: {
                    DEFAULT: '#ffffff',
                    dim: '#d9d6d0',
                    bright: '#faf8f5',
                    lowest: '#ffffff',
                    low: '#f5f3f0',
                    container: '#ededea',
                    high: '#e8e5e0',
                    highest: '#e2e0db',
                },
                on: {
                    surface: '#1a1c1f',
                    'surface-variant': '#4a4540',
                    primary: '#ffffff',
                },
                outline: {
                    DEFAULT: '#6b6560',
                    variant: '#c8c3bc',
                }
            },
        },
    },

    plugins: [forms],
};
