<?php
namespace Yardline;
use Yardline\Analytics;
use Yardline\DB\Manager as DB_Manager;
use Yardline\Settings;
use Yardline\Dashboard_Widget;
use Yardline\Main_Installer;
use Yardline\Admin\Admin_Menu;
use Yardline\Utils\Utils;
use Yardline\Api\Api_Loader;

if ( ! defined( 'ABSPATH' ) ) {exit;}

/**
 * Yardline plugin.
 *
 * The main plugin handler class is responsible for initializing Yardline. The
 * class registers and all the components required to run the plugin.
 *
 * @since 1.0
 */

class Plugin {

    /**
     * Instance.
     *
     * Holds the plugin instance.
     *
     * @since 1.0.0
     * @access public
     *
     * @static
     *
     * @var Plugin
     */
    public static $instance = null;

    /**
     * Database.
     *
     * Holds the plugin databases.
     *
     * @since 2.0.0
     * @access public
     *
     * @var DB_Manager
     */
    public $dbs;

     /**
     * @var Admin_Menu;
     */
    public $admin;

     /**
     * @var Analytics;
     */
    public $analytics;

     /**
     * @var Api_Loader;
     */
    public $api;

     /**
     * @var Hit_Collector;
     */
    public $hit_collector;

     /**
     * @var Utils
     */
    public $utils;

    /**
     * @var Main_Installer
     */
    public $installer;

    /**
     * Settings.
     *
     * @since 1.0.0
     * @access public
     *
     * @var Settings
     */
    public $settings;

    /**
     * Instance.
     *
     * Ensures only one instance of the plugin class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return Plugin An instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function init() {
        
        $this->includes();
        $this->init_components();
    }

    /**
     * Plugin constructor.
     *
     * Initializing Yardline plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function __construct() {

        if ( did_action( 'plugins_loaded' ) ){
            $this->init();
        } else {
            add_action( 'plugins_loaded', [ $this, 'init' ], 0 );
        }
    }

     /**
     * Init components.
     *
     * @since 1.0.0
     * @access private
     */
    private function init_components() {
        $this->settings     = new Settings();
        $this->analytics = new Analytics();
        $this->api = new Api_Loader();
        $this->dbs = new DB_Manager();
        $this->utils = new Utils();
        $this->hit_collector = new Hit_Collector();
        $this->hit_collector->init();

        if ( is_admin() ) {
            $this->admin   = new Admin_Menu();
            $this->dashboard = new Dashboard_Widget();
        }
        
        $this->installer    = new Main_Installer();
    }

     /**
     * Include other files
     */
    private function includes() {
        require  YARDLINE_PATH . '/includes/functions.php';

    }

}