<?php
namespace Yardline;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show a welcome screen which will help users find articles and extensions that will suit their needs.
 *
 * Class Page
 * @package Yardline\
 */
class Analytics {

    const SCRIPT_NAME = 'yl-analytics';

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend' ] );
    }

    public function enqueue_frontend() {
        
        wp_enqueue_script( self::SCRIPT_NAME, YARDLINE_ASSETS_URL . 'js/frontend.js', array(), YARDLINE_VERSION, true );
    }
}
	
	

	
	



	

	

	