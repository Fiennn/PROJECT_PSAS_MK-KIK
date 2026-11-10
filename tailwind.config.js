import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    forest: '#13391b',
                    forestLight: '#1b4d24',
                    forestDark: '#0e2c14',
                    orange: '#ea580c',
                    orangeHover: '#c2410c',
                },
                forest: {
                    50: '#f3f7f2',
                    100: '#e3ede0',
                    200: '#c8ddc4',
                    300: '#a2c39b',
                    400: '#77a46e',
                    500: '#55884c',
                    600: '#416d3a',
                    700: '#35572f',
                    800: '#2d4629',
                    900: '#263b23',
                    950: '#12200f',
                },
                sand: {
                    50: '#fdfbf7',
                    100: '#faf4e8',
                    200: '#f2e6ce',
                    300: '#e8d3a9',
                    400: '#dcb97a',
                    500: '#d1a258',
                    600: '#c38a44',
                    700: '#a26f3a',
                    800: '#835a34',
                    900: '#6b4a2d',
                    950: '#3a2617',
                },
                clay: {
                    50: '#fdf5f2',
                    100: '#fbe8e1',
                    200: '#f6d0c3',
                    300: '#efad97',
                    400: '#e58262',
                    500: '#d9633f',
                    600: '#c54b2b',
                    700: '#a43c24',
                    800: '#873424',
                    900: '#703022',
                    950: '#3c160e',
                },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            boxShadow: {
                soft: '0 18px 45px -24px rgba(38, 59, 35, 0.35)',
                lift: '0 24px 60px -28px rgba(38, 59, 35, 0.45)',
            },
            borderRadius: {
                '4xl': '2rem',
            },
            keyframes: {
                ticker: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                marquee: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
            },
            animation: {
                ticker: 'ticker 30s linear infinite',
                marquee: 'marquee 25s linear infinite',
            },
            backgroundImage: {
                'grain':
                    "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='120' height='120' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E\")",
            },
        },
    },
    plugins: [],
};
