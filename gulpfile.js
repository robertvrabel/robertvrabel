var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix.sass('app.scss', null, { includePaths: ['./node_modules/foundation-sites/scss/'] });

    // Combine necessary javascript
    mix.scripts([
        './resources/assets/js/app.js'
    ], 'public/js/app.js', './');

    // Copy CDN javascript for local use only
    mix.copy('./node_modules/foundation-sites/dist/foundation.min.js', 'public/js/foundation.min.js')
        .copy('./node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js');
});
