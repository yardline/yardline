<?php

namespace Yardline\Admin;

use Yardline\Admin\Admin_Page;
use function Yardline\admin_page_url;
use function Yardline\dashicon;
use function Yardline\yardline_logo;
use function Yardline\html;
use function Yardline\is_white_labeled;
use Yardline\License_Manager;
use Yardline\Plugin;
use function Yardline\white_labeled_name;



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show the score board screen.
 *
 * Class Page
 * @package Yardline\Admin
 */
class Goals_Page extends Admin_Page {
    const SCRIPT_NAME = 'yl_goals_script';
	// UNUSED FUNCTIONS

    // UNUSED FUNCTIONS
	


	// public function scripts() {
	// 	wp_enqueue_style( 'yardline-admin' );
	// 	//wp_enqueue_style( 'groundhogg-admin-extensions' );
	// }

	/**
	 * Settings_Page constructor.
	 */
	public function __construct() {
		//$this->add_additional_actions();
		parent::__construct();
	}

	protected function add_additional_actions() {
		//add_action( 'admin_init', array( $this, 'init_defaults' ) );
		//add_action( 'admin_init', array( $this, 'register_sections' ) );
		//add_action( 'admin_init', array( $this, 'register_settings' ) );
		
	}

	public function get_slug() {
		return 'yl_goals';
	}

	public function get_name() {
		return _x( 'Goals', 'page_title', 'yardline' );
	}

	public function get_title() {
		return sprintf( __( "%s Goals", 'yardline' ), white_labeled_name() );
	}

	public function get_cap() {
		return 'manage_options';
	}

	public function get_item_type() {
		return null;
	}

	public function get_priority() {
		return 20;
	}

	protected function get_title_actions() {
		return [];
	}


	
	
	 

	

	
	

		

	
	public function view() {
		?>

        <div class="yl-admin">
			
            <div id="yl-goals-page" class="yl-goals-page">
			<div class="welcome-header">
                <?php echo yardline_logo( 'white', 300, false ) ; ?>
            </div>
				<?php $this->notices(); ?>
                <hr class="wp-header-end">
                <div class="col">
					<div id="yl-goals"></div>
				</div>
            </div>
        </div>
		<?php
	}

	public function scripts(){
            wp_enqueue_script( self::SCRIPT_NAME, YARDLINE_ASSETS_URL . 'js/goals.js' , array(), YARDLINE_VERSION, true );
            
            wp_localize_script( self::SCRIPT_NAME, 'yardlineAdmin', [
                'restURL' => get_rest_url(),
                'wpnonce' => wp_create_nonce( 'yardline_goals' ),
                'siteTitle' => get_bloginfo( 'name' )
                
            ] );
	}

}