module.exports = {
  presets: [
    '@vue/app'
    // https://cli.vuejs.org/guide/browser-compatibility.html#polyfills
    // {
    //   polyfills: [
    //     'es6.promise',
    //     'es6.symbol'
    //   ]
    // }
  ],
  plugins: [
    '@babel/plugin-syntax-dynamic-import'
  ]
}
