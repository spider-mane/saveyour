const env = require('dotenv').config({path: '../.env', debug: true});
const envExp = require('dotenv-expand');
const yargs = require('yargs');
const mix = require('laravel-mix');

envExp.expand(env);

const argv = yargs(process.argv.slice(2))
  .options({
    openBrowser: {type: 'boolean', default: false},
  })
  .parseSync();

mix

  /**
   *==========================================================================
   * Output directory
   *==========================================================================
   *
   *
   *
   */
  .setPublicPath('dist')

  /**
   *==========================================================================
   * Sourcemaps
   *==========================================================================
   *
   *
   *
   */
  .sourceMaps(mix.inProduction(), 'eval-source-map', 'source-map')

  /**
   *==========================================================================
   * Versioning
   *==========================================================================
   *
   *
   *
   */
  .version()

  /**
   *==========================================================================
   * Browsersync
   *==========================================================================
   *
   *
   *
   */
  .browserSync({
    proxy: process.env.DOMAIN_NAME,
    open: argv.open ?? false,
    notify: false,
    logLevel: 'debug',
    files: ['dist/**/*.js', 'dist/**/*.css', '../src/**/*.php'],
  })

  /**
   *==========================================================================
   * Javascript
   *==========================================================================
   *
   *
   *
   */
  .js('src/js/index.js', 'dist/saveyour.js')

  /**
   *==========================================================================
   * Sass
   *==========================================================================
   *
   *
   *
   */
  .sass('src/scss/main.scss', 'dist/saveyour.css', {
    sassOptions: {
      outputStyle: 'expanded',
    },
  })

  /**
   *==========================================================================
   * Copies
   *==========================================================================
   *
   *
   *
   */
  .copyDirectory('./node_modules/jquery/dist', 'dist/lib/jquery')
  .copyDirectory('./node_modules/select2/dist', 'dist/lib/select2')
  .copyDirectory('./node_modules/trix/dist', 'dist/lib/trix')
  .copyDirectory('./node_modules/choices.js/public/assets', 'dist/lib/choices')

  /**
   *==========================================================================
   * Options
   *==========================================================================
   *
   *
   *
   */
  .options({
    processCssUrls: false,
    // postCss: [require('tailwindcss')],
  })

  /**
   *==========================================================================
   * Webpack
   *==========================================================================
   *
   *
   *
   */
  .webpackConfig({
    stats: {
      children: true,
    },
  });
