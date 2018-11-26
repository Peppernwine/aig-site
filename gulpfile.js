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

paths.NPMlibJSfiles = ['./node_modules/**/jquery.min.js','./node_modules/**/jquery.datetimepicker.full.min.js',
    './node_modules/**/jquery.blast.min.js','./node_modules/**/bootbox.min.js',
    './node_modules/**/bootstrap.min.js','./node_modules/**/velocity.min.js',
    './node_modules/**/flickity.pkgd.min.js',
    './bower_components/**/knockout.js','./bower_components/**/knockout.mapping-latest.js'];

paths.NPMlibCSSfiles = ['./node_modules/**/bootstrap.min.css','./node_modules/**/flickity.min.css',
    './node_modules/**/jquery.datetimepicker.min.css'];

paths.NPMLibSCSSFiles = ['./node_modules/**/fontawesome-free/scss/*.*'];
paths.NPMLibFontFiles = ['./node_modules/**/fontawesome-free/webfonts/*.*'];

paths.sharedVendorFiles = ['./lib-shared/**/*.*'];

paths.libSCSSFiles    = ['./public/scss/fontawesome.scss'];

paths.nonPublicFiles     = ['./app/**/*.*','./.htaccess','./app/**/.htaccess'];
paths.publicHTMLFiles    = ['./public/**/*.php','./public/**/*.html','./public/**/.htaccess'];
paths.publicImageFiles   = ['./public/**/images/*.*','./public/**/images/.htaccess'];
paths.publicFontFiles    = ['./public/**/fonts/*.*','./public/**/fonts/.htaccess'];

paths.stylesScriptsRefFiles = ['./public/styles.php','./public/scripts.php'];
paths.stylesSourceDir       = ['./public/scss/*.*','./public/css/*.*','./public/lib/css/*.*'];
paths.scriptsSourceDir      = ['./public/js/*.*','./public/lib/js/*.*'];
paths.SASSSource            = ['./public/scss/*.scss'];

paths.explicitPublicLibDir = ['./public/lib/KoolPHPSuite/**/*.*'];

paths.buildDir   = '/dev/build/aig-site';
paths.devTestDir = '/wamp64/www/test/aig-site';



function getStylesAndScriptSrc () {
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
    return stylesAndScriptSrc;
}


function getNOTStylesAndScriptSrc (src) {
    var i;
    var stylesAndScriptSrc = getStylesAndScriptSrc();
    for (i = 0; i < stylesAndScriptSrc.length; i++) {
        src.push('!' + stylesAndScriptSrc[i]);
    }
    return src;
}

function copyHtml(targetDir) {
    var src = paths.publicHTMLFiles;// ['./public/**/.htaccess','./public/**/*.html','./public/**/*.php'];

    // Exclude php/html files that are referring to CSS and JS.. they will be copied in a separate task..
    var i;
    for (i = 0; i < paths.stylesScriptsRefFiles.length; i++) {
        src.push('!' + paths.stylesScriptsRefFiles[i]);
    };

    for (i = 0; i < paths.explicitPublicLibDir.length; i++) {
        src.push('!' + paths.explicitPublicLibDir[i]);
    };

    return gulp.src(src,{base:'./'})
        .pipe(gulp.dest(targetDir));
}



function copyStylesAndScripts(targetDir) {
    return gulp.src(paths.stylesScriptsRefFiles,{base:'./'})
        //.pipe(debug())
        .pipe(useref())
        .pipe(gulpIf("*.js", uglify().on('error', function(e){
            console.log(e);
        })))
        .pipe(gulpIf("*.css", minifyCSS()))
        .pipe(debug())
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


gulp.task('build:copy-explicit-public-lib', function() {
    return gulp.src(paths.explicitPublicLibDir,{base:'./'})
        .pipe(gulp.dest(paths.buildDir));
});


// copy folders/files under app folder..eg. resource,templates,vendor to build
gulp.task('build:copy-non-public', function() {
    return gulp.src(paths.nonPublicFiles,{base:'./'})
        .pipe(gulp.dest(paths.buildDir));
});

gulp.task('build:copy-html',function() {
    return copyHtml(paths.buildDir);
});

gulp.task('build:copy-styles-scripts',function() {
    return copyStylesAndScripts(paths.buildDir);
});

gulp.task('build:copy-fonts', function() {
    return gulp.src(paths.publicFontFiles,{base:'./'})
        .pipe(gulp.dest(paths.buildDir));
});

gulp.task('build:copy-images', function() {
    return gulp.src(paths.publicImageFiles,{base:'./'})
        .pipe(gulp.dest(paths.buildDir));
});


gulp.task('deploy-dev-test:clean', function() {
    return del.sync(paths.devTestDir,{force:true});
})

gulp.task('deploy-dev-test:copy', function() {
    return gulp.src(paths.buildDir +'/**/*.*')
        .pipe(gulp.dest(paths.devTestDir));
});

gulp.task('deploy-dev-test:copyLiveHtml', function() {
    var src = getNOTStylesAndScriptSrc(['./**/*.htaccess','./app/**/+(*.html|*.php)','./public/**/+(*.html|*.php)','!vendor/**/*.*','!lib/**/*.*']);
    return gulp.src(src,{base:"./"})
        .pipe(gulp.dest(paths.devTestDir));
});


gulp.task('deploy-dev-test:copy-styles-scripts',function() {
    var retVal = copyStylesAndScripts(paths.devTestDir);
    bSync.reload();
    return retVal;
});

gulp.task('deploy-dev-test', function(callback) {
    runSequence('build','deploy-dev-test:clean','deploy-dev-test:copy',
        callback);
});


gulp.task('install-dev:copy-shared-lib', function() {
    //copy to non public folder
    return gulp.src(paths.sharedVendorFiles)
        .pipe(gulp.dest('./app/vendor'))
        .pipe(gulp.dest('./public/lib'));
});

gulp.task('install-dev:frontend', function() {
    return gulp.src(['./bower.json', './package.json'])
        .pipe(install());
});

gulp.task('install-dev:backend', function(done) {
    checkFilesExist('composer.json').then(function(){composer({ async: false })});
    done();
});

gulp.task('install-dev:copy-lib-js', function() {
    return gulp.src(paths.NPMlibJSfiles)
        .pipe(flatten())
        .pipe(gulp.dest('./public/lib/js'));
});

gulp.task('install-dev:copy-lib-css', function() {
    return gulp.src(paths.NPMlibCSSfiles)
        .pipe(flatten())
        .pipe(gulp.dest('./public/lib/css'));
});

gulp.task('install-dev:copy-lib-scss', function() {
    return gulp.src(paths.NPMLibSCSSFiles)
        .pipe(flatten())
        .pipe(gulp.dest('./public/lib/scss'));
});

gulp.task('install-dev:copy-lib-fonts', function() {
    return gulp.src(paths.NPMLibFontFiles)
        .pipe(flatten())
        .pipe(gulp.dest('./public/lib/fonts'));
});

gulp.task('install-dev:compile-sass',function () {
    return gulp.src(paths.libSCSSFiles)
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./public/lib/css'));
});

gulp.task('install-dev:clean', function() {
    return del.sync(['./public/lib/','./app/vendor/']);
})

gulp.task('install-dev', function(callback) {
    runSequence('install-dev:clean',
        ['install-dev:frontend','install-dev:backend'],
        ['install-dev:copy-shared-lib','install-dev:copy-lib-js','install-dev:copy-lib-css',
            'install-dev:copy-lib-scss','install-dev:copy-lib-fonts'],
        'install-dev:compile-sass',
        callback);
});

gulp.task('build', function(callback) {
    runSequence('install-dev:compile-sass','build:clean',['build:copy-non-public','build:copy-explicit-public-lib','build:copy-html','build:copy-styles-scripts','build:copy-images','build:copy-fonts'],
        callback);
});

gulp.task('serve', function(callback) {
    runSequence(['install-dev:compile-sass','deploy-dev-test:copy-styles-scripts'],callback);
    bSync.init({
        proxy : "http://test.localhost/aig-site/public"
    });
});

gulp.task('watch',['serve'],function() {

    gulp.watch(paths.SASSSource,['install-dev:compile-sass']);

    gulp.watch(getStylesAndScriptSrc(),['deploy-dev-test:copy-styles-scripts']);

    //for watch to trigger adds... folders must be relative path.. cannot event begin with .(dot)
    var otherSrc = getNOTStylesAndScriptSrc(['.htaccess','public/**/.htaccess','app/**/.htaccess','public/**/*.*','app/**/*.*','!./**/lib/*.*','!.git/**/*.*']);

    return gulp.watch(otherSrc, function(obj){
        console.log('CHANGED');
        if( obj.type === 'changed' || obj.type === 'added') {
            console.log('CHANGED or ADDED:' + obj.path);
            gulp.src( obj.path, { "base": "./"})
                .pipe(debug())
                .pipe(gulp.dest(paths.devTestDir))
                .on('end',bSync.reload);
        }
    });
});

gulp.task('default',['watch']) ;