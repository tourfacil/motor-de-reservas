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

// Arquivos SCSS
mix.sass('resources/assets/scss/main.scss', 'public/css/tourfacil.css');

// Arquivos JS
mix.js('resources/assets/js/vendor.js', 'public/js/vendor.js');
mix.js('resources/assets/js/app.js', 'public/js/app.js');
mix.babel([
    "resources/assets/js/controllers/**/*.js",
], 'public/js/controllers.js');

// CSS dos vouchers
// Flex css
mix.sass('resources/assets/scss/voucher.scss', 'public/css').options({
    autoprefixer: {
        options: {
            browsers: [
                'last 6 versions',
            ]
        }
    }
});

mix.version();
