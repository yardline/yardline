<?php
use Yardline\Plugin;

require YARDLINE_PATH . 'vendor/autoload.php';

add_action( 'init', function() {
    Plugin::instance();
}, 1 );

/**
 * Yardline loaded.
 *
 * Fires when Yardline is fully loaded and instantiated.
 *
 * @since 1.0.0
 */
do_action( 'yardline/loaded' );
