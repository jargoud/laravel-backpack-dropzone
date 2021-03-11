const mix = require('laravel-mix');

mix.webpackConfig({
  target: ['web', 'es5'],
});
mix.js('src/resources/js/dropzone.js', 'resources/js');
mix.copy('node_modules/dropzone/dist/dropzone.css', 'resources/css');
