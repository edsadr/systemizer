var gulp = require('gulp');
var phpcs = require('gulp-phpcs');

gulp.task('default', function() {
  return gulp.src(['src/**/*.php', '!src/vendor/**/*.*'])
    // Validate files using PHP Code Sniffer, please adjust the bin path if needed
    .pipe(phpcs({
      bin: '/usr/bin/phpcs',
      standard: 'PSR2',
      warningSeverity: 0
    }))
    // Log all problems that was found
    .pipe(phpcs.reporter('log'));
});