/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        'ango-navy': '#1B2A4A',
        'ango-red': '#E8453C',
        'ango-orange': '#F47B20',
        'ango-gold': '#F5A623',
        'ango-dark': '#0D1B2A',
        'ango-light': '#F8FAFC',
      },
      fontFamily: {
        'display': ['Inter', 'system-ui', 'sans-serif'],
        'body': ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
