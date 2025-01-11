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

 mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: true, // Ensure CSS URLs are processed correctly
    })
    .webpackConfig({
        output: {
            publicPath: '/invenict2/public/', // Ensure chunks load from the correct base path
            chunkFilename: 'js/[name].js', // Customize chunk filenames
        },
    });

    // Copy PrimeIcons fonts to the public/fonts directory
    mix.copy('node_modules/primeicons/fonts', 'public/fonts');


