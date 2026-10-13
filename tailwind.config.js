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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta de grises neutra para construcción base del layout
                'primary': {
                    50: '#f9fafb',   // Muy claro
                    100: '#f3f4f6',  // Claro
                    200: '#e5e7eb',  // Borde claro
                    300: '#d1d5db',  // Borde
                    400: '#9ca3af',  // Texto secundario
                    500: '#6b7280',  // Texto principal
                    600: '#4b5563',  // Texto oscuro
                    700: '#374151',  // Muy oscuro
                    800: '#1f2937',  // Casi negro
                    900: '#111827',  // Negro
                },
                // Sobreescribir grays por defecto
                'gray': {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                },
            },
            spacing: {
                // Espaciamientos atómicos base
                'xs': '4px',
                'sm': '8px',
                'md': '16px',
                'lg': '24px',
                'xl': '32px',
                '2xl': '48px',
                '3xl': '64px',
            },
            borderRadius: {
                // Radios de borde neutros
                'none': '0',
                'xs': '2px',
                'sm': '4px',
                'base': '6px',
                'md': '8px',
                'lg': '12px',
                'xl': '16px',
            },
            fontSize: {
                // Escala tipográfica neutra
                'xs': ['12px', { lineHeight: '16px' }],
                'sm': ['14px', { lineHeight: '20px' }],
                'base': ['16px', { lineHeight: '24px' }],
                'md': ['18px', { lineHeight: '28px' }],
                'lg': ['20px', { lineHeight: '28px' }],
                'xl': ['24px', { lineHeight: '32px' }],
                '2xl': ['28px', { lineHeight: '36px' }],
            },
            boxShadow: {
                'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'sm': '0 1px 3px 0 rgba(0, 0, 0, 0.1)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
                'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
                'focus': '0 0 0 3px rgba(107, 114, 128, 0.1)',
            },
        },
    },

    plugins: [forms],
};
