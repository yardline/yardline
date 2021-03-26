<?php
namespace Yardline;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Yardlline autoloader.
 *
 * Yardline autoloader handler class is responsible for loading the different
 * classes needed to run the plugin.
 *
 * Borrowed from Elementor via Groundhogg, thanks guys...
 *
 * @since 1.0
 */
class Autoloader {

    /**
     * Classes map.
     *
     * Maps Yardline classes to file names.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var array Classes used by yardline.
     */
    private static $classes_map = [
        'Analytics'                 => 'classes/Analytics.php',
        'Dashboard_Widget'          => 'classes/Dashboard_Widget.php',
        'Hit_Collector'             => 'classes/Hit_Collector.php',
        'Hit_Tracker'               => 'classes/Hit_Tracker.php',
        'Main_Installer'            => 'classes/Main_Installer.php',
        'Page_Paths'                => 'classes/Page_Paths.php',
        'Page_Stats'                => 'classes/Page_Stats.php',
        'Page_Views'                => 'classes/Page_Views.php',
        'Plugin'                    => 'classes/Plugin.php',
        'Referrers'                 => 'classes/Referrers.php',
        'Settings'                  => 'classes/Settings.php',
        'Site_Stats'                => 'classes/Site_Stats.php',
        'Abstracts\Installer'       => 'classes/abstracts/Installer.php',
        'Admin\Admin_Menu'          => 'classes/admin/Admin_Menu.php',
        'Admin\Admin_Page'          => 'classes/admin/Admin_Page.php',
        'Admin\Goals_Page'          => 'classes/admin/Goals_Page.php',
        'Admin\Score_Board_Page'    => 'classes/admin/Score_Board_Page.php',
        'Admin\Settings_Page'       => 'classes/admin/Settings_Page.php',
        'Api\Api_Loader'            => 'classes/api/Api_Loader.php',
        'Api\API_V1'                => 'classes/api/API_V1.php',
        'DB\DB'                     => 'classes/db/DB.php',
        'DB\Manager'                => 'classes/db/Manager.php',
        'DB\Page_Paths'             => 'classes/db/Page_Paths.php',
        'DB\Page_Stats'             => 'classes/db/Page_Stats.php',
        'DB\Referrer_Stats'         => 'classes/db/Referrer_Stats.php',
        'DB\Referrer_URLs'          => 'classes/db/Referrer_URLs.php',
        'DB\Site_Stats'             => 'classes/db/Site_Stats.php',
        'Utils\HTML'                => 'classes/utils/HTML.php',
        'Utils\Utils'               => 'classes/utils/Utils.php',
    ];

    /**
	 * Run autoloader.
	 *
	 * Register a function as `__autoload()` implementation.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function run() {
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}

	/**
	 * Load class.
	 *
	 * For a given class name, require the class file.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @param string $relative_class_name Class name.
	 */
	private static function load_class( $relative_class_name ) {

		if ( isset( self::$classes_map[ $relative_class_name ] ) ) {
			$filename = YARDLINE_PATH . '/' . self::$classes_map[ $relative_class_name ];
		} else {
			$filename = strtolower(
				preg_replace(
					[ '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$relative_class_name
				)
			);

			$is_filename = YARDLINE_PATH . $filename . '.php';

			if ( ! file_exists( $is_filename ) ){
			    $filename = wp_normalize_path( YARDLINE_PATH . 'includes/' . $filename . '.php' );
            } else {
			    $filename = $is_filename;
            }
		}

		if ( is_readable( $filename ) ) {
			require $filename;
		}
	}

	/**
	 * Autoload.
	 *
	 * For a given class, check if it exist and load it.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @param string $class Class name.
	 */
	private static function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ . '\\' ) ) {
			return;
		}

		$relative_class_name = preg_replace( '/^' . __NAMESPACE__ . '\\\/', '', $class );

		$final_class_name = __NAMESPACE__ . '\\' . $relative_class_name;

		if ( ! class_exists( $final_class_name ) ) {
			self::load_class( $relative_class_name );
		}
	}
}
