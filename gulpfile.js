var elixir = require('laravel-elixir');

require('laravel-elixir-webpack');

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
    mix.webpack('main.js', {
        module: {
            loaders: [
                {
                    test: /\.js$/,
                    exclude: [],
                    loader: 'babel-loader'
                }
            ]
        }
    });

    mix.version(['css/app.css', 'js/main.js']);

    // Copy CDN javascript for local use only
    mix.copy('./node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js');
});
