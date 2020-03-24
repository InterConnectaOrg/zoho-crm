const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.vue'],
		alias: {
			'~': __dirname + '/resources/js'
		}
	}
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

mix.setPublicPath('public')
	.js('resources/js/app.js', 'public')
	.sass('resources/sass/app.scss', 'public')
	.options({
		processCssUrls: false,
		postCss: [tailwindcss('tailwind.config.js')],
	});

mix.browserSync('interconnecta-connect.test');

if (mix.inProduction()) {
	mix.version();
}