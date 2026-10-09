/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        // DEFINISI WARNA KUSTOM ADA DI SINI
        bg: {
          dark: '#0D0806',
          secondary: '#1A0F0A',
        },
        coffee: {
          dark: '#3D2817',
          DEFAULT: '#6B4423',
          light: '#8B6F47',
        },
        cream: {
          DEFAULT: '#D4A574',
          light: '#E8C9A0',
        },
        text: {
          light: '#F5F1E8',
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
      },
      animation: {
        'spin-slow': 'spin 3s linear infinite',
        'bounce-slow': 'bounce 3s infinite',
      }
    },
  },
  plugins: [],
}