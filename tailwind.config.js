/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/**/*.js',
    './templates/**/*.html.twig',
    './vendor/symfony/twig-bridge/Resources/views/Form/*.html.twig',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
