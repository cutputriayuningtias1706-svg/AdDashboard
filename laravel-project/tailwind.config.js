/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
          950: '#172554',
        },
        meta: {
          DEFAULT: '#4267B2',
          50: '#f0f4f8',
          100: '#e1e8f0',
          200: '#c4d1e0',
          300: '#a7bac9',
          400: '#8aa3b3',
          500: '#4267B2',
          600: '#3251a1',
          700: '#213d7e',
          800: '#11285c',
          900: '#001439',
        },
        tiktok: {
          DEFAULT: '#000000',
          light: '#25f4ee',
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
