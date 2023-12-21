/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'bookmark-red': '#FF0000',
        'bookmark-black': '#000000',
      },
    },
  },
  plugins: [
      require("daisyui")
  ],
  daisyui:{
    themes: [
        "retro",
        "pastel"
    ]
  }

}