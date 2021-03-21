/**
 * WC Tiered Shipping build scripts.
 */

// Declare gulp libraries.
const gulp       = require( 'gulp' ),
      browserify = require( 'browserify' ),
      buffer     = require( 'vinyl-buffer' ),
      concat     = require( 'gulp-concat' ),
      source     = require( 'vinyl-source-stream' ),
      uglify     = require( 'gulp-uglify' );


// Build editor JS files.
function jsTask() {
  return browserify( { entries: [ 'wc-tiered-shipping/source/wc-tiered-shipping-scripts.js' ] } )
    .transform( 'babelify', { presets: [ '@babel/preset-env' ] } )
    .bundle()
    .pipe( source( 'wc-tiered-shipping-scripts.min.js' ) )
    .pipe( buffer() )
    .pipe( uglify() )
    .pipe( gulp.dest( 'wc-tiered-shipping/build' ) );
}

// Tasks.
gulp.task( 'default', jsTask );
