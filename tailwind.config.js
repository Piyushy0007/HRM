module.exports = {
  purge: [],
  theme: {
    extend: {
      colors: {
        custom: {
          primary: 'rgb(84 138 238)',
          border: '#d9dada',
          custom2: '#EFEFEF', // pseudo even
          cardBg: '#E3E6F0',
          placeholder: '#444444', // fuuta
          button: '#2D477F',
          bg_table_head_primary:"#e2e8f0",
          tag_primary: "#4CAF50"
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
