<?php

namespace Yardline\Admin;


use Yardline\Admin\Settings_Page;
use Yardline\Admin\Welcome_Page;
use function Yardline\has_premium_features;
use function Yardline\is_option_enabled;
use function Yardline\is_pro_features_active;
use function Yardline\is_white_labeled;
use function Yardline\isset_not_empty;
use function Yardline\white_labeled_name;

/**
 * Admin Manager to manage databases in Groundhogg
 *
 * Class Manager
 * @package Groundhogg\Admin
 */
class Admin_Menu {

	/**
	 * @var Admin_Page[]
	 */
	protected $pages = [];

   // public $welcome;
	/**
	 * Manager constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'init_admin' ], 25 );
	}

	/**
	 * Setup the base DBs for the plugin
	 */
	public function init_admin() {
		$this->welcome  = new Welcome_Page();
	//	$this->contacts = new Contacts_Page();
	//	$this->tags     = new Tags_Page();

		// if ( ! is_pro_features_active() || ! is_option_enabled( 'gh_use_advanced_email_editor' ) ) {
		// 	$this->emails = new Emails_Page();
		// }

	//	$this->broadcasts = new Broadcasts_Page();
	//	$this->funnels    = new Funnels_Page();

	//	$this->events    = new Events_Page();
	//	$this->tools     = new Tools_Page();
	    $this->settings  = new Settings_Page();
	//	$this->bulk_jobs = new Bulk_Job_Page();

	//	$this->reporting = new Reports_Page();
//        $this->dashboard = new Dashboard_Widgets();

		// if ( ! is_white_labeled() ) {
		// 	$this->guided_setup = new Guided_Setup();
		// 	$this->help         = new Help_Page();

		// 	if ( ! has_premium_features() ) {
		// 		$this->pro = new Pro_Page();
		// 	}
		// }

		// user profile edits...
	//	new Admin_User();

		do_action( 'yardline/admin/init', $this );
	}

	/**
	 * Set the data to the given value
	 *
	 * @param $key string
	 *
	 * @return Admin_Page|Funnels_Page|Contacts_Page
	 */
	public function get_page( $key ) {
		return $this->$key;
	}

	/**
	 * Magic get method
	 *
	 * @param $key string
	 *
	 * @return bool|Admin_Page
	 */
	public function __get( $key ) {
		if ( isset_not_empty( $this->pages, $key ) ) {
			return $this->pages[ $key ];
		}

		return false;
	}

	/**
	 * Set the data to the given value
	 *
	 * @param $key string
	 * @param $value Admin_Page
	 */
	public function __set( $key, $value ) {
		$this->pages[ $key ] = $value;
	}

}
