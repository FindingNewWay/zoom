const mix = require('laravel-mix');

mix.js('resources/js/index.js', 'public/js')
   .js('resources/js/meeting.js', 'public/js')
   .js('resources/js/tool.js', 'public/js')
   .copy('resources/js/vconsole.min.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');