<?php

namespace Yardline\DB;

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
class Site_Stats extends DB {

	/**
	 * Get the DB suffix
	 *
	 * @return string
	 */
	public function get_db_suffix() {
		return 'yl_site_stats';
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
	 *
	 * @return string
	 */
	public function get_object_type() {
		return 'site_stat';
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
			'visitors'      => (int) '%d',
			'pageviews'     => (int) '%d',
		);
	}

	/**
	 * Get default column values
	 *
	 * @access  public
	 */
	public function get_column_defaults() {
		return array(
			'date'          => date( 'Y-m-d'),
            'visitors'      => 0,
            'pageviews'     => 0,
		);
	}

	/**
	 * Add on duplicate
	 * 
	 * increments the value if already present
	 *
	 * @access  public
	 */
	public function add_on_duplicate( $data = array() ) {
		global $wpdb;
		$data = wp_parse_args( $data, $this->get_column_defaults() );
		$sql = $wpdb->prepare( 
			"INSERT INTO {$wpdb->prefix}yl_site_stats(date, visitors, pageviews) 
			VALUES(%s, %d, %d) 
			ON DUPLICATE KEY UPDATE visitors = visitors + VALUES(visitors), pageviews = pageviews + VALUES(pageviews)", 
			array( $data['date'], $data['visitors'], $data['pageviews'] ) );
		$wpdb->query( $sql );
	}

	public function get_for_date_range( $start_date, $end_date ) {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT date, visitors, pageviews FROM {$wpdb->prefix}yl_site_stats s WHERE s.date >= %s AND s.date <= %s", array( $start_date, $end_date ) );
		return $wpdb->get_results( $sql );
	}

	/**
	 * Add a stat
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
            visitors MEDIUMINT UNSIGNED NOT NULL,
			pageviews MEDIUMINT UNSIGNED NOT NULL,
			PRIMARY KEY (date)
		) {$this->get_charset_collate()};";

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

}