<?php
namespace Yardline\DB;

use function Yardline\get_array_var;
use function Yardline\get_db;
use function Yardline\dev_log;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Site Stats DB
 *
 * @since       File available since Release 1.0
 *
 */
class Referrer_URLs extends DB {

	/**
	 * Get the DB suffix
	 *
	 * @return string
	 */
	public function get_db_suffix() {
		return 'yl_referrer_urls';
	}

	/**
	 * Get the DB primary key
	 *
	 * @return string
	 */
	public function get_primary_key() {
		return 'id';
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
		return 'referrer_url';
	}

	
	/**
	 * Get columns and formats
	 *
	 * @access  public
	 */
	public function get_columns() {
		return array(
            'id'            => '%d',
            'url'          => '%s'
		);
	}

	/**
	 * Get default column values
	 *
	 * @access  public
	 */
	public function get_column_defaults() {
		return array(
            'id'        => 0,
            'url'      => '',
		);
	}

	public function get_by_urls($urls) {
		global $wpdb;
		//dev_log('referrer get by urls');
		$placeholders  = rtrim( str_repeat( '%s,', count( $urls ) ), ',' );
		$sql = $wpdb->prepare( "SELECT id, url FROM {$wpdb->prefix}yl_referrer_urls r WHERE r.url IN({$placeholders})", $urls );
		//dev_log($sql);
		//dev_log($wpdb->get_results( $sql ));
		return $wpdb->get_results( $sql );
	}

	public function add_urls( $urls ) {
        global $wpdb;
		foreach( $urls as $url ) {
			$this->add([
				'url'		=> $url,
			]);
		}

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

		if ( empty( $args['url'] ) ) {
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
            id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
            url VARCHAR(255) NOT NULL UNIQUE,
            PRIMARY KEY (id)
		) {$this->get_charset_collate()};";

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

}