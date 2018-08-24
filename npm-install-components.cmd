//Ensure nodeJS/NPM is installed on your PC
//Ensure gulp is installed as a global module on your PC ->  npm install -g gulp
//Ensure bower is installed as a global module on your PC ->  npm install -g bower

npm init
bower init

//install gulp locally
npm install gulp --save-dev

npm install gulp-debug gulp-tap del gulp-install gulp-flatten gulp-if gulp-rename del run-sequence  gulp-composer check-files-exist --save-dev
npm install gulp-sass  gulp-useref  gulp-uglify gulp-minify-css browser-sync --save-dev
npm install jquery@3.3.1 bootstrap@3.3.7  velocity-animate@2.0.2  blast-text@2.0.0  bootbox@4.4.0 jquery-datetimepicker@2.5.20 flickity@2.1.1 @fortawesome/fontawesome-free  --save-dev
bower install knockout@3.4.2  knockout-mapping --save-dev