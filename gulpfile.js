var gulp = require('gulp');
var debug = require('gulp-debug');
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
var bSync = require('browser-sync').create(); // create a browser sync instance.


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

paths.stylesScriptsRefFiles = ['./public/index.php'];
paths.nonPublicFiles = ['./resource/**/*.*','./templates/**/*.*','./vendor/**/*.*'];

paths.sharedVendorFiles = ['./lib-shared/**/*.*'];

paths.buildDir = '/dev/build/php-gulp';
paths.devTestDir = '/wamp64/www/test/php-gulp';

paths.stylesSourceDir = ['./public/css/*.*','./public/lib/css/*.*'];
paths.scriptsSourceDir = ['./public/js/*.*','./public/lib/js/*.*'];

function copyPublicSource(targetDir) {
    var src =  ['./public/**/*.html','./public/**/*.php','!./public/lib/KoolPHPSuite/**/*.*'];

    // Exclude php/html files that are referring to CSS and JS.. they will be copied in a separate task..
    var i;
    for (i = 0; i < paths.stylesScriptsRefFiles.length; i++) {
        src.push('!' + paths.stylesScriptsRefFiles[i]);
    }
    ;
    return gulp.src(src,{base:'./'})
        .pipe(gulp.dest(targetDir));
}

function copyStylesAndScripts(targetDir) {
    return gulp.src(paths.stylesScriptsRefFiles,{base:'./'})
        .pipe(useref())
        .pipe(debug())
        .pipe(gulpIf("*.js", uglify()))
        .pipe(gulpIf("*.css", minifyCSS()))
        .pipe(tap(function(file, t) {
            if (['.js', '.css'].indexOf( file.extname) >= 0) {
                return t.through(gulp.dest, [targetDir +'/public/']);
            } else {
                return t.through(gulp.dest, [targetDir]);
            }
        }));
}

gulp.task('build:clean', function() {
    return del.sync(paths.buildDir,{force:true});
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
    return copyPublicSource(paths.buildDir);
});

gulp.task('build:copy-styles-scripts',function() {
    return copyStylesAndScripts(paths.buildDir);
});

gulp.task('deploy-dev-test:clean', function() {
    return del.sync(paths.devTestDir,{force:true});
})

gulp.task('deploy-dev-test:copy', function() {
    return gulp.src(paths.buildDir +'/**/*.*')
        .pipe(gulp.dest(paths.devTestDir));
});

gulp.task('deploy-dev-test:copy-styles-scripts',function() {
    var retVal = copyStylesAndScripts(paths.devTestDir);
    bSync.reload();
    return retVal;
});

gulp.task('serve', function() {
    bSync.init({
        proxy : "http://test.localhost/php-gulp/public"
    });
});

gulp.task('deploy-dev-test:watch', ['serve'],function() {
    var stylesAndScriptSrc =  [];

    var i;
    for (i = 0; i < paths.scriptsSourceDir.length; i++) {
        stylesAndScriptSrc.push(paths.scriptsSourceDir[i]);
    }

    for (i = 0; i < paths.stylesSourceDir.length; i++) {
        stylesAndScriptSrc.push(paths.stylesSourceDir[i]);
    }

    for (i = 0; i < paths.stylesScriptsRefFiles.length; i++) {
        stylesAndScriptSrc.push(paths.stylesScriptsRefFiles[i]);
    }

    gulp.watch(stylesAndScriptSrc,['deploy-dev-test:copy-styles-scripts']);

    var otherSrc =  ['./public/**/*.html','./resource/**/*.html',
                     './public/**/*.php','./resource/**/*.php',
                     './templates/**/*.*',
                     '!./**/lib/*.*'];

    for (i = 0; i < stylesAndScriptSrc.length; i++) {
        otherSrc.push('!' + stylesAndScriptSrc[i]);
    }

    console.log(otherSrc);
    return gulp.watch(otherSrc, function(obj){
        console.log('CHANGED');
        if( obj.type === 'changed' || obj.type === 'added') {
            console.log('CHANGED or ADDED:' + obj.path);
            gulp.src( obj.path, { "base": "./"})
                .pipe(debug())
                .pipe(gulp.dest(paths.devTestDir));
            bSync.reload();
        }
    });
});

gulp.task('deploy-dev-test', function(callback) {
    runSequence('deploy-dev-test:clean','deploy-dev-test:copy',
        callback);
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
    runSequence('build:clean',['build:copy-non-public','build:copy-public-assets','build:copy-styles-scripts','build:copy-public-src'],
        callback);
});



