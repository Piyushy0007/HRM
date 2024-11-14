const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/dashboard.scss', 'public/css')
    .options({
		processCssUrls: false,
		postCss: [ tailwindcss('tailwind.config.js') ],
    });
