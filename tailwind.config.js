import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Merriweather', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // JamHR Brand Colors
                primary: {
                    DEFAULT: 'hsl(216, 90%, 35%)', // #09429A
                    foreground: '#ffffff',
                },
                secondary: {
                    DEFAULT: 'hsl(45, 90%, 55%)', // #F2C925
                    foreground: '#1a1a1a',
                },
                accent: {
                    DEFAULT: 'hsl(150, 80%, 35%)', // #12A156
                    foreground: '#ffffff',
                },
                sidebar: {
                    DEFAULT: 'hsl(220, 25%, 18%)',
                    foreground: '#ffffff',
                    border: 'hsl(220, 20%, 25%)',
                    primary: 'hsl(216, 90%, 45%)',
                    'primary-foreground': '#ffffff',
                    accent: 'hsl(220, 20%, 25%)',
                    'accent-foreground': '#ffffff',
                },
                destructive: {
                    DEFAULT: 'hsl(0, 84%, 60%)',
                    foreground: '#ffffff',
                },
                muted: {
                    DEFAULT: 'hsl(220, 14%, 96%)',
                    foreground: 'hsl(220, 9%, 46%)',
                },
                background: 'hsl(220, 14%, 96%)',
                foreground: 'hsl(220, 15%, 15%)',
                border: 'hsl(220, 13%, 91%)',
            },
        },
    },

    plugins: [forms],
};
