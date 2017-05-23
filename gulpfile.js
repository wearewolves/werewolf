var gulp = require('gulp');
var ftp = require('vinyl-ftp');
var gutil = require('gulp-util');
var minimist = require('minimist');
var args = minimist(process.argv.slice(2));
gulp.task('deploy', function() {
  var remotePath = '/www/';
  var conn = ftp.create({
    host: 'werewolf6.cafe24.com',
    user: args.user,
    password: args.password,
    log: gutil.log,
    maxConnections: 4
  });

  gulp.src(['release/**/*.*'])
    .pipe(conn.newer(remotePath))
    .pipe(conn.dest(remotePath));

  gulp.src(['server/next.cgi'])
    .pipe(conn.mode(remotePath +'server','0755'))
    .pipe(conn.newer(remotePath + 'server'))
    .pipe(conn.dest(remotePath + 'server'));
});
