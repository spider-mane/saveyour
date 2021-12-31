const mix = require('laravel-mix');

/**
 * Basic settings
 */
mix
  .setResourceRoot('src')
  .setPublicPath('dist')
  .sourceMaps(true, 'eval-source-map', 'source-map')
  .version();

/**
 * Browsersync
 */
mix.browserSync({proxy: 'saveyour.test'});

/**
 * Javascript
 */
mix
  .js('src/js/index.js', 'dist/js/saveyour.js')
  // .autoload({jquery: ['$', 'window.jQuery']})
  .extract();

/**
 * Sass
 */
// mix
// .sass('src/scss/main.scss', 'dist/css/saveyour.css', {
//   sassOptions: {
//     outputStyle: 'expanded',
//   },
// })
// .options({
//   processCssUrls: false,
// });
