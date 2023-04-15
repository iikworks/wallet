/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
  ],
  safelist: [
    'mb-3',
    'gap-4',
    'grid-cols-1',
    'md:grid-cols-2',
    'lg:grid-cols-3',
    'lg:grid-cols-4',
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('flowbite/plugin')
  ],
}

