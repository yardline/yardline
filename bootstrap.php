<?php
use Yardline\Plugin;

//require YARDLINE_PATH . 'vendor/autoload.php';

/**
 * Register Autoloader
 */
spl_autoload_register(function ($class) {
	$prefix = 'Yardline\\';
	$base_dir = __DIR__ . '/classes/';
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}
	$relative_class = substr($class, $len);
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
	if (file_exists($file)) {
		require $file;
	}
});

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
