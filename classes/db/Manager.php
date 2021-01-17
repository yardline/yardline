<?php

namespace Yardline\DB;

use function Yardline\isset_not_empty;

/**
 * DB Manager to manage databases in Yardline
 *
 * Class Manager
 * @package Yardline\DB
 */
class Manager {

	/**
	 * List of DBs
	 *
	 * @var DB[]|Meta_DB[]
	 */
	protected $dbs = [];

	protected $initialized = false;

	/**
	 * Manager constructor.
	 */
	public function __construct() {
		
		add_action( 'plugins_loaded', [ $this, 'init_dbs' ], 1 );

	}


	/**
	 * @return bool
	 */
	public function is_initialized() {
		return $this->initialized;
	}

	/**
	 * Setup the base DBs for the plugin
	 */
	public function init_dbs() {
		
		$this->site_stats = new Site_Stats();
		$this->page_stats = new Page_Stats();
		$this->page_paths = new Page_Paths();
		$this->referer_urls = new Referer_URLs();
		
		$this->initialized = true;
	}

	/**
	 * Install all DBS.
	 */
	public function install_dbs() {
		if ( empty( $this->dbs ) ) {
			$this->init_dbs();
		}

		foreach ( $this->dbs as $db ) {
			$db->create_table();
		}
	}

	/**
	 * Empty all of the dbs.
	 */
	public function truncate_dbs() {
		if ( empty( $this->dbs ) ) {
			$this->init_dbs();
		}

		foreach ( $this->dbs as $db ) {
			$db->truncate();
		}
	}

	/**
	 * Drop all the DBs
	 */
	public function drop_dbs() {
		if ( empty( $this->dbs ) ) {
			$this->init_dbs();
		}

		foreach ( $this->dbs as $db ) {
			$db->drop();
		}
	}

	/**
	 * Get all the table names.
	 *
	 * @return string[]
	 */
	public function get_table_names() {
		$table_names = [];

		foreach ( $this->dbs as $db ) {

			$table_names[] = $db->get_table_name();

		}

		return $table_names;
	}

	/**
	 * Set the data to the given value
	 *
	 * @param $key string
	 *
	 * @return DB|Meta_DB|Tags
	 */
	public function get_db( $key ) {
		return $this->$key;
	}

	/**
	 * @return DB[]|Meta_DB[]
	 */
	public function get_dbs() {
		return $this->dbs;
	}

	/**
	 * Magic get method
	 *
	 * @param $key string
	 *
	 * @return bool|DB|Meta_DB
	 */
	public function __get( $key ) {
		if ( isset_not_empty( $this->dbs, $key ) ) {
			return $this->dbs[ $key ];
		}

		return false;
	}


	/**
	 * Set the data to the given value
	 *
	 * @param $key   string
	 * @param $value DB|Meta_DB
	 */
	public function __set( $key, $value ) {
		$this->dbs[ $key ] = $value;
	}

}
