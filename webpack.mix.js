let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js','public/js')
    .scripts([

            'resources/assets/js/resources-room.js',
            'resources/assets/js/resources-file.js',
            'resources/assets/js/inviteParticipants.js',
            'resources/assets/js/invited-meetings/meetings.js',

        ],
        'public/js/bbb-custom.js')
    .scripts([

            'resources/assets/js/meeting/datepicker.js',
        ],
        'public/js/bbb-meetings.js')
    .scripts([

            'resources/assets/js/meeting/timepicker.js',
        ],
        'public/js/bbb-custom-meetings.js')
    .scripts([

            'resources/assets/js/front/meetings/resource-accesscode.js',
            'resources/assets/js/front/meetings/resource-joinAttendee.js'

        ],
        'public/js/front/meetings/bbb-front.js')
    .styles('resources/assets/css/bbb-custom-resource.css','public/css/bbb-custom.css');

// mix.js('resources/assets/js/app.js', 'public/js')
//     .sass('resources/assets/sass/app.scss', 'public/css');