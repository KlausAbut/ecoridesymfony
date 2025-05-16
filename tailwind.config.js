/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      './templates/**/*.twig',
      './assets/**/*.js'
    ],
    theme: {
      extend: {},
    },
    plugins: [],
  }
  module.exports = {
    theme: {
      extend: {
        colors: {
          primary: '#2F855A', // Vert éco-responsable
          secondary: '#2B6CB0'
        }
      }
    }
  }  