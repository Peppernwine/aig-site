var gulp = require('gulp');
//var debug = require('gulp-debug');
var runSequence = require('run-sequence');
var install = require("gulp-install");
var flatten = require('gulp-flatten');
var sass = require('gulp-sass');
var useref = require('gulp-useref');
var gulpIf = require('gulp-if');
var tap = require('gulp-tap');
var uglify = require('gulp-uglify');
var minifyCSS = require('gulp-minify-css');
var rename = require('gulp-rename');
var del = require('del');
var composer = require('gulp-composer');
var checkFilesExist = require('check-files-exist');
var path = require('path');

var paths = {};

paths.libJSfiles = ['./node_modules/**/jquery.min.js','./node_modules/**/jquery.datetimepicker.full.min.js',
    './node_modules/**/jquery.blast.min.js','./node_modules/**/bootbox.min.js',
    './node_modules/**/bootstrap.min.js','./node_modules/**/velocity.min.js',
    './node_modules/**/flickity.pkgd.min.js',
    './bower_components/**/knockout.js','./bower_components/**/knockout.mapping-latest.js'];

paths.libCSSfiles = ['./node_modules/**/bootstrap.min.css','./node_modules/**/flickity.min.css',
    './node_modules/**/jquery.datetimepicker.min.css'];

paths.libFontAwesomeSCSSfiles = ['./node_modules/**/fontawesome-free/scss/*.*'];
paths.libFontAwesomeFontfiles = ['./node_modules/**/fontawesome-free/webfonts/*.*'];

paths.nonPublicFiles = ['./resource/**/*.*','./templates/**/*.*','./vendor/**/*.*'];

paths.sharedVendorFiles = ['./lib-shared/**/*.*'];

paths.buildDir = './build';

gulp.task('build:clean', function() {
    return del.sync('./build');
})


// copy resource,templates,vendor to build
gulp.task('build:copy-non-public', function() {
    return gulp.src(paths.nonPublicFiles,{base:'./'})
        .pipe(gulp.dest(paths.buildDir));
});

// copy public/fonts,public/images/,public/lib/fonts, public/lib/KoolPHPSuite
gulp.task('build:copy-public-assets', function() {
    return gulp.src(['./public/**/*.*','!./public/**/*.php','!./public/**/*.html',
        '!./public/**/css/*.*','!./public/**/js/*.*','!./public/**/scss/*.*'],{base:'./'})
        .pipe(gulp.dest(paths.buildDir));
});

gulp.task('build:copy-public-src',function() {
    return gulp.src(['./public/**/*.html','./public/**/*.php','!./public/lib/KoolPHPSuite/**/*.*'],{base:'./'})
        .pipe(useref())
        .pipe(gulpIf("*.js", uglify()))
        .pipe(gulpIf("*.css", minifyCSS()))
        .pipe(tap(function(file, t) {
            if (['.js', '.css'].indexOf(file.extname) >= 0) {
                t.through(gulp.dest, [paths.buildDir +'/public/'])
            } else {
                t.through(gulp.dest, [paths.buildDir])
            }
        }))
});

gulp.task('install-dev:copy-shared-lib', function() {
    //copy to non public folder
    gulp.src(paths.sharedVendorFiles)
        .pipe(gulp.dest('./vendor'));

    //copy to public folder
    return gulp.src(paths.sharedVendorFiles)
        .pipe(gulp.dest('./public/lib'));
});

gulp.task('install-dev:frontend', function() {
    return gulp.src(['./bower.json', './package.json'])
        .pipe(install());
});

gulp.task('install-dev:backend', function(done) {
    checkFilesExist('composer.json').then(function(){composer()});
    done();
});

gulp.task('install-dev:copy-lib-js', function() {
    return gulp.src(paths.libJSfiles)
        .pipe(flatten())
        .pipe(gulp.dest('./public/lib/js'));
});

gulp.task('install-dev:copy-lib-css', function() {
    return gulp.src(paths.libCSSfiles)
        .pipe(flatten())
        .pipe(gulp.dest('./public/lib/css'));
});

gulp.task('install-dev:copy-fa-scss', function() {
    return gulp.src(paths.libFontAwesomeSCSSfiles)
        .pipe(flatten())
        .pipe(gulp.dest('./public/lib/scss'));
});

gulp.task('install-dev:copy-fa-font', function() {
    return gulp.src(paths.libFontAwesomeFontfiles)
        .pipe(flatten())
        .pipe(gulp.dest('./public/lib/fonts'));
});

gulp.task('install-dev:fa-sass',function () {
    return gulp.src('./public/scss/fontawesome.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(rename('fontawesome.min.css'))
        .pipe(gulp.dest('./public/lib/css'));
});

gulp.task('install-dev:clean', function() {
    return del.sync('./public/lib/');
})

gulp.task('install-dev', function(callback) {
    runSequence('install-dev:clean',
        ['install-dev:frontend','install-dev:backend'],
        ['install-dev:copy-shared-lib','install-dev:copy-lib-js',
            'install-dev:copy-lib-css','install-dev:copy-fa-scss','install-dev:copy-fa-font'],
        'install-dev:fa-sass',
        callback);
});

gulp.task('build', function(callback) {
    runSequence('build:clean',['build:copy-non-public','build:copy-public-assets','build:copy-public-src'],
        callback);
});



