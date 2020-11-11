<?php

namespace Yardline\DB;

// Exit if accessed directly
use function Yardline\get_array_var;
use function Yardline\get_db;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Site Stats DB
 *
 * @since       File available since Release 1.0
 *
 */
class Page_Stats extends DB {

	/**
	 * Get the DB suffix
	 *
	 * @return string
	 */
	public function get_db_suffix() {
		return 'yl_page_stats';
	}

	/**
	 * Get the DB primary key
	 *
	 * @return string
	 */
	public function get_primary_key() {
		return 'date';
	}

	/**
	 * Get the DB version
	 *
	 * @return mixed
	 */
	public function get_db_version() {
		return '1.0';
	}

	/**
	 * Get the object type we're inserting/updateing/deleting.
	 *
	 * @return string
	 */
	public function get_object_type() {
		return 'page_stat';
	}

	/**
	 * @return string
	 */
	public function get_date_key() {
		return 'date';
	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 */
	public function get_columns() {
		return array(
            'date'          => '%s',
            'path_id'            => '%d',
			'visitors'      => '%d',
			'pageviews'     => '%d',
		);
	}

	/**
	 * Get default column values
	 *
	 * @access  public
	 */
	public function get_column_defaults() {
		return array(
			
            'date'          => date(),
            'path_id'            => 0,
            'visitors'      => 0,
            'pageviews'     => 0,
            
		);
	}

	/**
	 * Add a activity
	 *
	 * @access  public
	 */
	public function add( $data = array() ) {

		$args = wp_parse_args(
			$data,
			$this->get_column_defaults()
		);

		if ( empty( $args['date'] ) ) {
			return false;
		}

		return $this->insert( $args );
	}

	/**
	 * Create the table
	 *
	 * @access  public
	 */
	public function create_table() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE " . $this->table_name . " (
            date DATE NOT NULL,
            path_id BIGINT(20) UNSIGNED NOT NULL,
	        visitors MEDIUMINT UNSIGNED NOT NULL,
	        pageviews MEDIUMINT UNSIGNED NOT NULL,
	        PRIMARY KEY (date, path_id)
		) {$this->get_charset_collate()};";

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

}