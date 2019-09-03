const mix = require('laravel-mix');

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

 mix.sass('resources/src/scss/bootstrap.scss', 'public/assets/css')
     .sass('resources/src/scss/bootstrap-extend.scss', 'public/assets/css')
     .sass('resources/src/scss/site.scss', 'public/assets/css')
     .sass('resources/src/skins/brown.scss', 'public/assets/skins')
     .sass('resources/src/skins/cyan.scss', 'public/assets/skins')
     .sass('resources/src/skins/green.scss', 'public/assets/skins')
     .sass('resources/src/skins/grey.scss', 'public/assets/skins')
     .sass('resources/src/skins/indigo.scss', 'public/assets/skins')
     .sass('resources/src/skins/orange.scss', 'public/assets/skins')
     .sass('resources/src/skins/pink.scss', 'public/assets/skins')
     .sass('resources/src/skins/purple.scss', 'public/assets/skins')
     .sass('resources/src/skins/red.scss', 'public/assets/skins')
     .sass('resources/src/skins/teal.scss', 'public/assets/skins')
     .sass('resources/src/skins/yellow.scss', 'public/assets/skins')
     .sass('resources/src/skins/light-green.scss', 'public/assets/skins')
     .sass('resources/src/skins/blue-grey.scss', 'public/assets/skins')
     .minify('public/assets/css/bootstrap.css')
     .minify('public/assets/css/bootstrap-extend.css')
     .minify('public/assets/css/site.css')
     .minify('public/assets/skins/brown.css')
     .minify('public/assets/skins/cyan.css')
     .minify('public/assets/skins/green.css')
     .minify('public/assets/skins/grey.css')
     .minify('public/assets/skins/indigo.css')
     .minify('public/assets/skins/orange.css')
     .minify('public/assets/skins/pink.css')
     .minify('public/assets/skins/purple.css')
     .minify('public/assets/skins/red.css')
     .minify('public/assets/skins/teal.css')
     .minify('public/assets/skins/yellow.css')
     .minify('public/assets/skins/light-green.css')
     .minify('public/assets/skins/blue-grey.css');
