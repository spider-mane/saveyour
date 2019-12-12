/* Gulpfile

IMPORANT!! READ THIS!!

This will only work if you namespace your unit tests like this.
- Tests for 'Foo' class needs to be named 'FooTest'
- 'FooTest' needs to be in the same namespace as 'Foo'
- User psr-4 autoloading to facilitate this

  app/Acme/Foo.php ===> Acme\Foo
  app/tests/unit/FooTest.php ===> Acme\FooTest

  composer.json file
    "psr-4": {
        "Acme\\" : "app/tests/unit",
        "Acme\\" : "app/Acme"
    },

- set your namespace on the first line in your php file like shown below
  <?php namespace Acme\Foo;
  <?php namespace Acme\FooTest;

  ** This script reads the first line and extracts the namespace so its important that you put your
     namespace exactly like shown above **

- I have only tested this on a windows environment!

*/

var gulp = require('gulp');
var exec = require('child_process').exec;
var path = require('path');
var fs = require('fs');

gulp.task('watch', function () {

    // PHPUNIT
    // Passing 'event' allows us to get the filepath of the file that is saved when using gulp.watch
    gulp.watch('**/*.php', function (event) {

        // Read file by line and return the specified line
        // Credit for this function goes to http://stackoverflow.com/a/6401299
        function get_line(filename, line_no, callback) {
            var stream = fs.createReadStream(filename, {
                flags: 'r',
                encoding: 'utf-8',
                fd: null,
                mode: 0666,
                bufferSize: 64 * 1024
            });

            var fileData = '';
            stream.on('data', function (data) {
                fileData += data;
                var lines = fileData.split("\n");

                line_no = line_no - 1;

                if (lines.length >= +line_no) {
                    stream.destroy();
                    callback(null, lines[+line_no]);
                }
            });

            stream.on('error', function () {
                callback('Error', null);
            });
        }

        // Get the class that is being modified/saved
        var phpClass = path.basename(event.path, '.php');

        get_line(event.path, 1, function (err, line) {

            if (typeof line !== 'undefined') {

                // Get the first line in the file being modified/saved and just return the namespace
                var namespace = line.match(/((?:namespace\s)([^\s;]+))/)[2];

                // Add the class name to the namespace
                classPath = namespace + '\\' + phpClass;

                // Check to see if the actual Class or TestClass is being modified/saved.
                // TestClass will have Test at the end of its name
                if (phpClass.indexOf('Test') == -1) {
                    var testClassPath = classPath + 'Test';
                } else {
                    // No need to append 'Test' if actual test file is modified
                    var testClassPath = classPath;
                }

                // Escape back slashes (\ to \\) and add quotes
                // (format needed to run as a --filter option in PHPUnit)
                testClassPath = testClassPath.replace(/\\/g, '\\\\');
                testClassPath = '\'' + testClassPath + '\'';

                // Run phpunit
                exec('vendor\\bin\\phpunit --filter ' + testClassPath, function (error, stdout) {
                    console.log(stdout);
                });

            } else {
                console.log('Error retrieving namespace!');
            }
        });
    });
});
