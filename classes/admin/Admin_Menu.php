<?php

namespace Yardline\Admin;


use Yardline\Admin\Settings_Page;
use Yardline\Admin\Score_Board_Page;
use function Yardline\isset_not_empty;

/**
 *
 * Admin Manage
 * @package Yardline\Admin
 */
class Admin_Menu {

	/**
	 * @var Admin_Page[]
	 */
	protected $pages = [];


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
		$this->score_board  = new Score_Board_Page();
	    $this->settings  = new Settings_Page();
	
		do_action( 'yardline/admin/init', $this );
	}

	/**
	 * Set the data to the given value
	 *
	 * @param $key string
	 *
	 * @return Admin_Page
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
