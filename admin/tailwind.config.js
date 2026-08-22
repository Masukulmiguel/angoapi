/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{js,jsx}'],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#eef5ff',
          100: '#d9e8ff',
          200: '#bcd7ff',
          300: '#8ebfff',
          400: '#599cff',
          500: '#3378ff',
          600: '#1b57f5',
          700: '#1443e1',
          800: '#1736b6',
          900: '#19328f',
          950: '#0D1B2A',
        },
        dark: {
          sidebar: '#0D1B2A',
          card: '#1B2838',
          surface: '#152232',
        },
        surface: '#F8FAFC',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
