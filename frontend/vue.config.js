// "use strict"
const path = require('path')
const src = './src/'

module.exports = {
  // proxy API requests to Valet during development
  // devServer: {
  //     proxy: 'http://laracon.test'
  // },
  // assetsDir: 'assets',

  // output built static files to Laravel's public dir.
  // note the "build" script in package.json needs to be modified as well.
  outputDir: '../public',

  // modify the location of the generated HTML file.
  // make sure to do this only in production.
  indexPath: process.env.NODE_ENV === 'production'
    ? '../resources/views/index.blade.php'
    : 'index.html',

  chainWebpack: config => {
    // // See https://cli.vuejs.org/guide/css.html#automatic-imports
    const types = ['vue-modules', 'vue', 'normal-modules', 'normal']
    types.forEach(type =>
      addStyleResource(
        config.module.rule('stylus').oneOf(type)
      )
    )
  },
  configureWebpack: {
    resolve: {
      alias: {
        // src folder alias
        '@': path.resolve(src)
      }
    }
  }
}

// See https://cli.vuejs.org/guide/css.html#automatic-imports
function addStyleResource (rule) {
  // console.log(rule)
  rule.use('style-resource')
    .loader('style-resources-loader')
    .options({
      patterns: [
        path.resolve(__dirname, src + 'styles/global/mixins.styl'), // or styles/global/mixins/*.styl
        path.resolve(__dirname, src + 'styles/global/variables.styl'),
        path.resolve(__dirname, src + 'styles/global/functions.styl')
      ]
    })
}
