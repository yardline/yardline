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
class Score_Board_Page extends Admin_Page {
	// UNUSED FUNCTIONS

	const SCRIPT_NAME = 'yl_admin_script';
	public function help() {
	}

	public function screen_options() {
	}

	protected function add_ajax_actions() {
	}

	public function process_action() {
	    return;
	}

	/**
	 * Get the menu order between 1 - 99
	 *
	 * @return int
	 */
	public function get_priority() {
		return 1;
	}

	/**
	 * Get the page slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return 'yardline';
	}

	/**
	 * Get the menu name
	 *
	 * @return string
	 */
	public function get_name() {
		return apply_filters( 'yardline/admin/scoreboard/name', 'Yardline' );
	}

	/**
	 * The required minimum capability required to load the page
	 *
	 * @return string
	 */
	public function get_cap() {
		return 'view_contacts';
	}

	/**
	 * Get the item type for this page
	 *
	 * @return mixed
	 */
	public function get_item_type() {
		return null;
	}

	/**
	 * Adds additional actions.
	 *
	 * @return void
	 */
	protected function add_additional_actions() {
	}

	/**
	 * Add the page
	 */
	public function register() {

		if ( is_white_labeled() ) {
			$name = white_labeled_name();
		} else {
			$name = 'Yardline';
		}

		$page = add_menu_page(
			'Yardline',
			$name,
			'view_contacts',
			'yardline',
			[ $this, 'page' ],
			'dashicons-chart-bar',
			2

		);

		$sub_page = add_submenu_page(
			'yardline',
			_x( 'Score Board', 'page_title', 'yardline' ),
			_x( 'Score Board', 'page_title', 'yardline' ),
			'view_contacts',
			'yardline',
			array( $this, 'page' )
		);

		$this->screen_id = $page;

		/* White label compat */
		if ( is_white_labeled() ) {
			remove_submenu_page( 'yardline', 'yardline' );
		}

		add_action( "load-" . $page, array( $this, 'help' ) );
	}


	/**
	 * Display the title and dependent action include the appropriate page content
	 */
	public function page() {

        do_action( "yardline/admin/{$this->get_slug()}/before" );
        

		?>
        <div class="wrap">
			<?php
           
			if ( method_exists( $this, $this->get_current_action() ) ) {
				call_user_func( [ $this, $this->get_current_action() ] );
			} else if ( has_action( "yardline/admin/{$this->get_slug()}/display/{$this->get_current_action()}" ) ) {
				do_action( "yardline/admin/{$this->get_slug()}/display/{$this->get_current_action()}", $this );
			} else {
				call_user_func( [ $this, 'view' ] );
			}

			?>
        </div>
		<?php

		do_action( "yardline/admin/{$this->get_slug()}/after" );
	}


	/**
	 * The main output
	 */
	public function view() {
		?>

        <div class="yl-admin">
			
            <div id="score-board-page" class="score-board-page">
			<div class="welcome-header">
                <?php echo yardline_logo( 'white', 300, false ) ; ?>
            </div>
				<?php $this->notices(); ?>
                <hr class="wp-header-end">
                <div class="col">
					<div id="score-board"></div>
				</div>
            </div>
        </div>
		<?php
	}

	public function scripts(){
		wp_enqueue_script( self::SCRIPT_NAME, YARDLINE_ASSETS_URL . 'js/admin.js' , array(), YARDLINE_VERSION, true );
		
		wp_localize_script( self::SCRIPT_NAME, 'yardlineAdmin', [
            'restURL' => get_rest_url(),
			//'wpnonce' => wp_create_nonce( 'yardline_admin' ),
			'siteTitle' => get_bloginfo( 'name' )
            
        ] );
	}

}