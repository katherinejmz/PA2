/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./index.html",
      "./src/**/*.{js,jsx}",
    ],
    theme: {
      extend: {
        fontFamily: {
          sans: ["Inter", "sans-serif"],
        },
        colors: {
          primary: '#10b981',     // émeraude
          secondary: '#f59e0b',   // orange doré
          accent: '#3b82f6',      // bleu vif
          light: '#f9fafb',
          dark: '#111827',
        },
      },
    },
    plugins: [],
  }
