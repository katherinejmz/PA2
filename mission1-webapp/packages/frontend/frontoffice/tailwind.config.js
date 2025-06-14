/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,jsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
      colors: {
        primary: '#10b981',     // vert émeraude
        secondary: '#f59e0b',   // orange doré
        accent: '#3b82f6',      // bleu vif
        light: '#f9fafb',
        dark: '#111827',
      },
    },
  },
  plugins: [],
};