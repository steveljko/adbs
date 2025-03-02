import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/js/app.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                sm: ['0.9rem', '1rem'],
                md: '0.938rem',
                base: ['1rem', '1.188rem'],
                lg: ['1.25rem', '1.375rem'],
            },
            colors: {
                orange: {
                    500: '#f15a22',
                }
            },
            padding: {
                '0.75': '0.188rem',
            },
        },
    },
    plugins: [],
};
