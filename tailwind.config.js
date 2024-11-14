module.exports = {
  purge: [],
  theme: {
    extend: {
      colors: {
        custom: {
          primary: 'rgb(84 138 238)',
          border: '#707070',
          custom2: '#EFEFEF', // pseudo even
          cardBg: '#E3E6F0',
          placeholder: '#444444', // fuuta
          button: '#2D477F',
        }
      }
    }
  },
  variants: {
    backgroundColor: ['even']
  },
  plugins: [
    require("@tailwindcss/custom-forms")
  ]
}
