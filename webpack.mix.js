const mix = require('laravel-mix');
const webpack = require('webpack');

 mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: true, 
    })
    .webpackConfig({
        output: {
            publicPath: '/invenict2/public/',
            chunkFilename: 'js/[name].js',
        },
        plugins: [
            new webpack.DefinePlugin({
                __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false), // Set to `true` for detailed mismatch logs
            }),
        ],
    });
    mix.copy('node_modules/primeicons/fonts', 'public/fonts');


