var package = require('./package.json'),
    gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    jshint = require('gulp-jshint'),
    stripDebug = require('strip-debug'),
    runSequence = require('run-sequence'),
    uglify = require('gulp-uglify');

var csso = require('gulp-csso');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
//var notify = require('gulp-notify');

var $vendor_js = [
  'build/js/lib/modernizr.3.3.1.custom.min.js',
  'build/js/lib/bootstrap/bootstrap.min.js',
  'dist/js/libs/slick/slick.min.js',
  'build/js/lib/jssocials/jssocials.min.js',
  'build/js/lib/handlebars/handlebars-v4.0.5.js',
  'build/js/lib/jquery/jquery.md5.js',
  'build/js/lib/jquery/jquery.easing.min.js',
  'build/js/lib/jquery/jquery.storageapi.min.js'
];

var $script_js = [
  'build/js/app.js',
  'build/js/script.js',
  'build/js/gallery-product.js',
  'build/js/modal.js',
  'build/js/modal-gallery.js',
  'build/js/search.js',
  'build/js/cart.js',
  'build/js/facebook.js',
  'build/js/quote.js',
  //'build/js/tooltip.js', // TODO: remove or use
  'build/js/layout.js',
  'build/js/tile-tooltip.js',
  'build/js/menu-mobile.js',
  'build/js/validate.js',
  'build/js/payment.js',
  'build/js/analytics.js',
];

var $css_dir = 'htdocs/wp-content/themes/brandner/vendor/tbo/css';
var $js_dir = 'htdocs/wp-content/themes/brandner/vendor/tbo/js';

gulp.task('styles', function () {
  return gulp
    .src('build/scss/app.scss')
    .pipe(sass())
    .pipe(autoprefixer({
      browsers: ['> 1%', 'last 10 versions'],
      cascade: false
    }))
    .pipe(csso())
    .pipe(rename('all.css'))
    .pipe(gulp.dest('dist/css'))
    .pipe(gulp.dest($css_dir));
});

gulp.task('scripts', function () {
  return gulp.src($script_js)
    .pipe(jshint())
    .pipe(concat('all.js'))
    .pipe(gulp.dest('dist/js'))
    .pipe(gulp.dest($js_dir));
});

gulp.task('scripts-production', function () {
  return gulp.src($script_js)
    .pipe(jshint())
    .pipe(uglify())
    .pipe(gulp.dest('dist/js'));
});

// TODO: for production and with wordpress
gulp.task('remove-logging', function () {
  return gulp.src('dist/app/**/*.js')
    .pipe(stripDebug())
    .pipe(gulp.dest('dist/app'));
});

gulp.task('watch', function() {
  gulp.watch('build/scss/**/*.scss', ['styles']);
  gulp.watch('build/js/**/*.js', ['scripts']);
});

gulp.task('default', ['styles', 'scripts']);

gulp.task('production', function() {
  runSequence(
    ['styles',  'scripts-production']
    //'remove-logging'
  );
});

// vendor file, build once, or whenever it changes
gulp.task('vendor', function() {
  return gulp.src($vendor_js)
      .pipe(concat('vendor.js'))
      .pipe(gulp.dest($js_dir))
      .pipe(rename('vendor.min.js'))
      .pipe(uglify())
      .pipe(gulp.dest($js_dir));
});