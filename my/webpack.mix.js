let mix = require('laravel-mix');

let CleanWebpackPlugin = require('clean-webpack-plugin');

// paths to clean
var pathsToClean = [
    'public/assets/app/js',
    'public/assets/app/css',
    'public/assets/partner/js',
    'public/assets/partner/css',
    'public/assets/jquery-ui',
    'public/assets/auth/css',
];

// the clean options to use
var cleanOptions = {};

mix.webpackConfig({
    plugins: [
        new CleanWebpackPlugin(pathsToClean, cleanOptions)
    ]
});
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

// fonts


// JS
mix.scripts([
    'node_modules/jquery/dist/jquery.js',
//    'node_modules/bootstrap-umi/dist/js/bootstrap.min.js',
    'node_modules/bootstrap-umi/dist/js/bootstrap.bundle.js',
], 'public/assets/app/js/app.js').version();


mix.scripts([
    'resources/partner/js/companies/post_api.js',
    'node_modules/clipboard/dist/clipboard.js',
    'resources/partner/js/companies/edit_base.js',
], 'public/assets/partner/js/companies/edit_base.js').version();

mix.scripts([
    'resources/partner/js/common/jquery.selectlistactions.js',
    'resources/partner/js/common/edit_order.js',
], 'public/assets/partner/js/common/edit_order.js').version();


mix.scripts([
    'node_modules/jquery-ui-dist/jquery-ui.js',
    'resources/partner/js/common/jquery-ui-touch-punch.min.js',
    'resources/partner/js/common/jquery.jquery-sortable-lists.min.js',
    'resources/partner/js/common/edit_order_mobile.js',
], 'public/assets/partner/js/common/edit_order_mobile.js').version();

// ES2015表記も使用可能にする
mix.js('resources/partner/js/companies/info.js', 'public/assets/partner/js/companies')
    .js('resources/auth/login/rule.js', 'public/assets/auth/js/login')
    .js('resources/partner/js/companies/edit_detail2.js', 'public/assets/partner/js/companies')
    .js('resources/partner/js/companies/edit_image_order.js', 'public/assets/partner/js/companies').version();


// CSS
mix.styles([
    'node_modules/bootstrap-umi/dist/css/bootstrap.min.css',
], 'public/css/bootstrap.css').version();

mix.styles([
    'node_modules/jquery-ui-dist/jquery-ui.css',
], 'public/css/jquery-ui.css').version();



mix.sass('resources/assets/sass/style.scss', 'public/css').version();
mix.sass('resources/assets/sass/layout/auth.scss', 'public/assets/auth/css/').version();
mix.sass('resources/assets/sass/layout/partner.scss', 'public/assets/partner/css/').version();
