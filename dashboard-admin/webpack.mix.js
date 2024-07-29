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

mix.sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/js/app.js', 'public/js/vendor.js');

mix.babel([
    "resources/assets/js/controllers/**/*.js",
], 'public/js/app.js');

// CSS dos vouchers
mix.sass('resources/assets/sass/vouchers/voucher.scss', 'public/css');

mix.version();
