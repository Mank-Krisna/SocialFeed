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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Sora', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#0058bc',
                    hover: '#004493',
                    container: '#0070eb',
                },
                background: '#f9f9fd',
                surface: {
                    DEFAULT: '#ffffff',
                    dim: '#d9dade',
                    bright: '#f9f9fd',
                    lowest: '#ffffff',
                    low: '#f3f3f7',
                    container: '#ededf1',
                    high: '#e8e8ec',
                    highest: '#e2e2e6',
                },
                on: {
                    surface: '#1a1c1f',
                    'surface-variant': '#414754',
                    primary: '#ffffff',
                },
                outline: {
                    DEFAULT: '#727785',
                    variant: '#c1c6d6',
                }
            },
        },
    },

    plugins: [forms],
};
