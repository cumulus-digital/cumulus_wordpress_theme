var browserify = require('browserify');
browserify('./assets/prod/js/index.js')
  .transform("babelify", {presets: ["latest"]})
  .bundle()
  .pipe(process.stdout);