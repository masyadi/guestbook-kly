const mix = require('laravel-mix');
const path = 'resources/asset/';


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

// Backend
mix.styles([

        //template
        path + '_template/css/font-awesome.css',
        path + '_template/css/metismenu.css',
        path + '_template/css/animate.css',
        path + '_template/css/bootstrap.css',
        path + '_template/css/pe-icon-7-stroke.css',
        path + '_template/css/toastr.min.css',
        path + '_template/css/helper.css',
        path + '_template/css/dropzone.css',
        path + '_template/css/style.css',
        path + '_template/css/datatable.css',
        path + '_template/css/summernote.css',

        'node_modules/select2/dist/css/select2.min.css',
        'node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css',
        'node_modules/jquery-tokeninput/dist/css/token-input.min.css',
        'node_modules/jquery-tokeninput/dist/css/token-input-facebook.min.css',
        'node_modules/jquery-datetime-picker/build/jquery.datetimepicker.min.css',

        //custom    
        path + 'custom/css/style.css'
    ],
    'public/static/css/app.css')

.js([

        //custom    
        path + 'custom/js/script.js',

        path + '_template/js/script.js'

    ],
    'public/static/js/app.js');


// Frontend
mix.styles([

        // path + '_template/css/web/jquery.fancybox.css',
        path + '_template/css/toastr.min.css',

        //custom
        // path + 'custom/css/web/style.css',
    ],
    'public/static/css/web.css')

.js([
        // path + '_template/js/web/jquery.fancybox.js',

        //custom    
        // path + 'custom/js/web/calendar.js',
        path + 'custom/js/web/script.js',
    ],
    'public/static/js/web.js');

// mix.copyDirectory(path + '_template/js/web/jquery.fancybox.js', 'public/static/js');

mix.autoload({ jquery: ['$', 'window.jQuery', 'jQuery'] })
    .extract(['jquery', 'bootstrap'])
    .copyDirectory(path + '_template/eot', 'public/static/eot')
    .copyDirectory(path + '_template/gif', 'public/static/gif')
    .copyDirectory(path + '_template/jpg', 'public/static/jpg')
    .copyDirectory(path + '_template/png', 'public/static/png')
    .copyDirectory(path + '_template/svg', 'public/static/svg')
    .copyDirectory(path + '_template/ttf', 'public/static/ttf')
    .copyDirectory(path + '_template/woff', 'public/static/woff')
    .copyDirectory(path + '_template/woff2', 'public/static/woff2')
    .copyDirectory(path + '_template/img', 'public/static/img')
    .copyDirectory(path + 'custom/img', 'public/static/img')
    .copyDirectory(path + 'custom/audio', 'public/static/audio');

if (mix.inProduction()) {
    mix.version();
}

