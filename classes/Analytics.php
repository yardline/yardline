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
        $hit_nonce = wp_create_nonce( 'wp_rest' );
        dev_log($hit_nonce);
        wp_enqueue_script( self::SCRIPT_NAME, YARDLINE_ASSETS_URL . 'js/frontend.js', array(), YARDLINE_VERSION, true );
        wp_localize_script( self::SCRIPT_NAME, 'yardlineObject', [
            'restURL' => get_rest_url(),
            'wpnonce' => $hit_nonce,
            'honorDNT' => true,
            'useCookie' =>true
        ] );
    }
}
	
	

	
	



	

	

	