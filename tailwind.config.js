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
            colors: {
                'sidebar':       '#1B3A2D',
                'gold':          '#C8973A',
                'gold-hover':    '#B8832A',
                'gold-muted':    'rgba(200,151,58,0.1)',
                'cream':         '#F0EDE8',
                'surface':       '#E8E4DE',
                'panel':         '#FFFFFF',
                'text-main':     '#1B2E1F',
                'text-muted':    '#6B7B6E',
                'text-gold':     '#C8973A',
                'sidebar-text':  '#E8F0EA',
                'border-soft':   '#D4CFC8',
                'border-light':  '#E8E4DE',
                'active':        '#16A34A',
                'danger':        '#DC2626',
                'warning':       '#D97706',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
