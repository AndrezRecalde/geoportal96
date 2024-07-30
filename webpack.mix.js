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
 mix.alias({
    '@': 'resources/js',//path.join(__dirname, 'resources/js')
    '@sass':'resources/sass'
});
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .js('resources/js/appweb.js', 'public/js')
    .vue();
