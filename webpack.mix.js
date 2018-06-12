let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
 mix.webpackConfig({
   resolve: {
       modules: [
           path.resolve('./resources/assets/js/ui'),
           path.resolve('./node_modules')
       ]
   }
});

mix.js('resources/assets/js/app.js', 'public/js')
  .js('resources/assets/js/lander.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .styles('./node_modules/vue-material/dist/vue-material.css')
  .styles(['resources/assets/css/sage.css', 'resources/assets/css/style.css', 'resources/assets/css/icons.css'], 'public/css/sage.css')
  .copy('storage/app/public', 'public/storage')
  .copy('resources/assets/css/emails.css' ,'public/css/emails.css')
  .copy('resources/assets/js/plugins', 'public/js/plugins');
