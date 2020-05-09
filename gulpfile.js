var syntax = 'sass'; // Syntax: sass or scss;
var theme_name = 'starter_pack';
var proxy_name = 'remby.loc';

var gulp = require('gulp'),
  gutil = require('gulp-util'),
  sass = require('gulp-sass'),
  browserSync = require('browser-sync'),
  concat = require('gulp-concat'),
  uglify = require('gulp-uglify'),
  cleancss = require('gulp-clean-css'),
  rename = require('gulp-rename'),
  autoprefixer = require('gulp-autoprefixer'),
  notify = require("gulp-notify"),
  build = require('gulp-build'),
  rsync = require('gulp-rsync'),
  imagemin = require('gulp-imagemin');
cssmin = require('gulp-cssmin');
minify = require('gulp-minify');

gulp.task('styles', function () {

  return gulp.src('app/wp-content/themes/' + theme_name + '/assets/' + syntax + '/**/*.' + syntax + '')
    .pipe(sass({outputStyle: 'expanded'}).on("error", notify.onError()))
    .pipe(autoprefixer(['last 30 versions']))
    // .pipe(cleancss( {level: { 1: { specialComments: 0 } } })) // Opt., comment out when debugging
    .pipe(gulp.dest('app/wp-content/themes/' + theme_name + '/assets/css/'))
    .pipe(browserSync.stream())
});

gulp.task('combain_min_css', function () {
  return gulp.src([
    'app/wp-content/themes/' + theme_name + '/assets/libs/owl.carousel/owl.carousel.min.css',
    'app/wp-content/themes/' + theme_name + '/assets/libs/owl.carousel/owl.theme.default.min.css',
    'app/wp-content/themes/' + theme_name + '/assets/libs/fancybox-master/dist/jquery.fancybox.css',
    'app/wp-content/themes/' + theme_name + '/assets/css/main.css'
  ])
    .pipe(concat('styles.css'))
    .pipe(cssmin())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('app/wp-content/themes/' + theme_name + '/assets/css/'))
    .pipe(browserSync.stream());
});

gulp.task('js', function () {
  return gulp.src([
    'app/wp-content/themes/' + theme_name + '/assets/libs/jquery-3.4.1.min.js',
    'app/wp-content/themes/' + theme_name + '/assets/libs/owl.carousel/owl.carousel.min.js',
    'app/wp-content/themes/' + theme_name + '/assets/libs/fancybox-master/dist/jquery.fancybox.min.js',
    'app/wp-content/plugins/contact-form-7/includes/js/scripts.js',
    'app/wp-content/themes/' + theme_name + '/assets/libs/comments-reply.js',
    'app/wp-content/themes/' + theme_name + '/assets/libs/navigation.js',
    'app/wp-content/themes/' + theme_name + '/assets/libs/skip-link-focus-fix.js',
    'app/wp-content/themes/' + theme_name + '/assets/js/common.js',
    'app/wp-content/themes/' + theme_name + '/assets/js/wp_ajax.js', // Always at the end

  ])
    .pipe(concat('scripts.js'))


    .pipe(minify({
      ext: {
        src: 'scripts.js',
        min: '.min.js'
      },
      exclude: ['tasks'],
      ignoreFiles: ['.combo.js', '-min.js']
    }))


    .pipe(gulp.dest('app/wp-content/themes/' + theme_name + '/assets/js'))
    .pipe(browserSync.reload({stream: true}))
});

gulp.task('watch', ['styles', 'js', 'combain_min_css', 'browser-sync'], function () {
  gulp.watch('app/wp-content/themes/' + theme_name + '/assets/' + syntax + '/**/*.' + syntax + '', ['styles']);
  gulp.watch('app/wp-content/themes/' + theme_name + '/assets/css/main.css', ['combain_min_css']);

  gulp.watch(['app/wp-content/themes/' + theme_name + '/assets/js/common.js'], ['js']);
  gulp.watch(['app/wp-content/themes/' + theme_name + '/assets/js/wp_ajax.js'], ['js']);
  gulp.watch('app/wp-content/themes/' + theme_name + '/**/*.php', browserSync.reload);
  gulp.watch('app/wp-content/themes/' + theme_name + '/*.php', browserSync.reload);
});

gulp.task('browser-sync', function () {
  browserSync({
    proxy: proxy_name,
    notify: false,
    open: false,
  })
});

gulp.task('rsync', function () {
  return gulp.src('app/wp-content/themes/' + theme_name + '/**')
    .pipe(rsync({
      root: 'app/wp-content/themes/' + theme_name + '/',
      hostname: 'username@yousite.com',
      destination: 'app/wp-content/themes/' + theme_name + '/',
      include: ['*.htaccess'], // Includes files to deploy
      exclude: ['**/Thumbs.db', '**/*.DS_Store'], // Excludes files from deploy
      recursive: true,
      archive: true,
      silent: false,
      compress: true
    }))
});

gulp.task('default', ['watch']);