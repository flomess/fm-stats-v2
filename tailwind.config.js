/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'purple-fm': '#210d35',
        'purple-fm-hover': '#2c1c4b',
        'purple-fm-light': '#2f1e4f',
        'purple-fm-light-hover': '#3c2f6f',
        'pink-fm': '#c5048a',
        'pink-fm-light': '#f135d2',
        'dark-purple-fm': 'rgba(25,10,40)'
      },
      gridTemplateColumns: {
        '24': 'repeat(24, minmax(0, 1fr))',
        '15': 'repeat(15, minmax(0, 1fr))',
        '18': 'repeat(18, minmax(0, 1fr))',
      },
      minWidth: {
        '52': '13rem'
      }
    },
  },
  plugins: [],
}