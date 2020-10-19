<?php

namespace Yardline\Admin;

use Yardline\Admin\Admin_Page;
use function Yardline\admin_page_url;
use function Yardline\dashicon;
use function Yardline\yardline_logo; //do this Phil
use function Yardline\html;
use function Yardline\is_white_labeled;
use Yardline\License_Manager;
use Yardline\Plugin;
use function Yardline\white_labeled_name;

//need to create and enqueue scripts

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show a welcome screen which will help users find articles and extensions that will suit their needs.
 *
 * Class Page
 * @package Yardline\Admin
 */
class Welcome_Page extends Admin_Page {
	// UNUSED FUNCTIONS
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
		return apply_filters( 'yardline/admin/welcome/name', 'Yardline' );
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
	 * Add the page todo
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
			_x( 'Welcome', 'page_title', 'yardline' ),
			_x( 'Welcome', 'page_title', 'yardline' ),
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

	/* Enque JS or CSS */
	public function scripts() {
		//wp_enqueue_style( 'groundhogg-admin' );
		//wp_enqueue_style( 'groundhogg-admin-welcome' );
	}

	/**
	 * Display the title and dependent action include the appropriate page content
	 */
	public function page() {

        do_action( "yardline/admin/{$this->get_slug()}/before" );
        

		?>
        <div class="wrap">
			<?php
            echo '<p>Server Protocol: ' . $_SERVER['SERVER_PROTOCOL'] . '</p>';
            echo '<p>HTTP Referer: ' . $_SERVER['HTTP_REFERER'] . '</p>';
            echo '<p>Remote Addr: ' . $_SERVER['REMOTE_ADDR'] . '</p>';
            echo '<p>Remote User: ' . $_SERVER['REMOTE_USER'] . '</p>';
           
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

        <div id="welcome-page" class="welcome-page">
            <div id="poststuff">
                <div class="welcome-header">
                    <h1><?php echo sprintf( __( 'Welcome to %s', 'yardline' ), yardline_logo( 'black', 300, false ) ); ?></h1>
                </div>
				<?php $this->notices(); ?>
                <hr class="wp-header-end">
                <div class="col">
                    <div class="postbox" id="ylmenu">
                        <div class="inside" style="padding: 0;margin: 0">
                            <ul>
								<?php

								$links = [
									[
										'icon'    => 'admin-site',
										'display' => __( 'Yardline' ),
										'url'     => 'https://yardlineanalytics.com'
									],
									[
										'icon'    => 'media-document',
										'display' => __( 'Documentation' ),
										'url'     => 'https://yardlineanalytics.com'
									],
									// [
									// 	'icon'    => 'store',
									// 	'display' => __( 'Store' ),
									// 	'url'     => 'https://www.groundhogg.io/downloads/'
									// ],
									// [
									// 	'icon'    => 'welcome-learn-more',
									// 	'display' => __( 'Courses' ),
									// 	'url'     => 'https://academy.groundhogg.io/'
									// ],
									// [
									// 	'icon'    => 'sos',
									// 	'display' => __( 'Support Group' ),
									// 	'url'     => 'https://www.groundhogg.io/fb/'
									// ],
									// [
									// 	'icon'    => 'admin-users',
									// 	'display' => __( 'My Account' ),
									// 	'url'     => 'https://www.groundhogg.io/account/'
									// ],
									// [
									// 	'icon'    => 'location-alt',
									// 	'display' => __( 'Find a Partner' ),
									// 	'url'     => 'https://www.groundhogg.io/partner/certified-partner-directory/'
									// ],
								];

								foreach ( $links as $link ) {

									echo html()->e( 'li', [], [
										html()->e( 'a', [
											'href'   => add_query_arg( [
												'utm_source'   => get_bloginfo(),
												'utm_medium'   => 'welcome-page',
												'utm_campaign' => 'admin-links',
												'utm_content'  => strtolower( $link['display'] ),
											], $link['url'] ),
											'target' => '_blank'
										], [
											dashicon( $link['icon'] ),
											'&nbsp;',
											$link['display']
										] )
									] );

								}

								?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="postbox">
						<?php

						echo html()->e( 'a', [
							'href'   => add_query_arg( [
								'utm_source'   => get_bloginfo(),
								'utm_medium'   => 'welcome-page',
								'utm_campaign' => 'quickstart',
								'utm_content'  => 'image',
							], 'https://academy.groundhogg.io/course/groundhogg-quickstart/' )
							,
							'target' => '_blank'
						], html()->e( 'img', [
							'src' => YARDLINE_ASSETS_URL . 'images/welcome/quickstart-course-welcome-screen.png',
						] ) );

						echo html()->e( 'a', [
							'target' => '_blank',
							'class'  => 'button big-button',
							'href'   => add_query_arg( [
								'utm_source'   => get_bloginfo(),
								'utm_medium'   => 'welcome-page',
								'utm_campaign' => 'quickstart',
								'utm_content'  => 'button',
							], 'https://academy.groundhogg.io/course/groundhogg-quickstart/' ),
						], __( 'Take The Quickstart Course!', 'yardline' ) );

						?>
                    </div>
                </div>

                <div class="left-col col">

                    <!-- Import your list -->
                    <div class="postbox">
						<?php

						echo html()->modal_link( [
							'title'              => __( 'Import your list!' ),
							'text'               => html()->e( 'p', [ 'hello'
								
							] ),
							'footer_button_text' => __( 'Close' ),
							'source'             => 'import-list-video',
							'class'              => 'img-link no-padding',
							'height'             => 555,
							'width'              => 800,
							'footer'             => 'true',
							'preventSave'        => 'true',
						] );

						echo html()->e( 'a', [
							'class' => 'button big-button',
							'href'  => admin_page_url( 'gh_tools', [ 'tab' => 'import', 'action' => 'add' ] )
						], __( 'Import your Contact List!', 'groundhogg' ) );

						echo html()->e( 'a', [ 'class'  => 'guide-link',
						                       'href'   => 'https://help.groundhogg.io/article/14-how-do-i-import-my-list',
						                       'target' => '_blank'
						], __( 'Read the full guide', 'groundhogg' ) );

						?>
                        <div class="hidden" id="import-list-video">
                            <iframe width="800" height="450" src="https://www.youtube.com/embed/BmTmVAoWSb0"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>

                    <!-- Create a funnel -->
                    <div class="postbox">
						<?php

						echo html()->modal_link( [
							'title'              => __( 'Create your first funnel!', 'groundhogg' ),
							'text'               => html()->e( 'img', [
								'src' => YARDLINE_ASSETS_URL . 'images/welcome/create-your-first-funnel-with-yardline.png'
							] ),
							'footer_button_text' => __( 'Close' ),
							'source'             => 'create-your-first-funnel-video',
							'class'              => 'img-link no-padding',
							'height'             => 555,
							'width'              => 800,
							'footer'             => 'true',
							'preventSave'        => 'true',
						] );

						

						?>
                        
                    </div>
                </div>
                <div class="right-col col">

                    <!-- Send a Broadcast -->
                    <div class="postbox">

						

						

						

                       
                    </div>

                    <!-- Configure CRON -->
                    <div class="postbox">
						<?php
						echo html()->modal_link( [
							'title'              => __( 'Configure WP-Cron', 'groundhogg' ),
							'text'               => html()->e( 'img', [
								'src' => YARDLINE_ASSETS_URL . 'images/welcome/correctly-configure-wp-cron-for-groundhogg.png'
							] ),
							'footer_button_text' => __( 'Close' ),
							'source'             => 'configure-wp-cron',
							'class'              => 'img-link no-padding',
							'height'             => 555,
							'width'              => 800,
							'footer'             => 'true',
							'preventSave'        => 'true',
						] );

						echo html()->e( 'a', [
							'class' => 'button big-button',
							'href'  => admin_page_url( 'gh_tools', [ 'tab' => 'cron_setup' ] )
						], __( 'Configure WP-Cron!' ) );

						echo html()->e( 'a', [ 'class'  => 'guide-link',
						                       'href'   => 'https://help.groundhogg.io/article/45-how-to-disable-builtin-wp-cron',
						                       'target' => '_blank'
						], __( 'Read the full guide', 'groundhogg' ) ); ?>
                        <div class="hidden" id="configure-wp-cron">
                            <iframe width="800" height="450" src="https://www.youtube.com/embed/1-csY3W-WP0"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

}