/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      './templates/**/*.html.twig',
      './assets/**/*.js'
    ],
    theme: {
      extend: {
        extend: {
          colors: {
            primary: '#2F855A', // Vert éco-responsable
            secondary: '#2B6CB0'
          }
        }
      },
    },
    plugins: [],
  }
 
  