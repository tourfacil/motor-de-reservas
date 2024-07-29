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
mix.autoload({});

mix.sass('resources/assets/sass/app.scss', 'public/css').options({
    autoprefixer: {
        options: {
            browsers: ['last 6 versions']
        }
    }
});

mix.js('resources/assets/js/app.js', 'public/js/vendor.js');

mix.babel([
    "resources/assets/js/controllers/**/*.js",
], 'public/js/app.js');

mix.version();
