<?php
namespace Yardline\Api\V1;

use function Yardline\dev_log;
use Yardline\Site_Stat;
use Yardline\Hit_Tracker;
use Yardline\DB\Site_Stats;
/**
 * Class WP_Statistics_Rest
 */
class API_V1 {

	// Set Default namespace
	const route = 'yardline/v1';

	// Set Default Name
	const _Argument = 'yardline_hit_rest';

	public function __construct() {
		$this->register_routes();
	}
	/**
	 * List Of Required Params
	 *
	 * @return array
	 */
	public static function require_params_hit() {
		return array(
			'ua',
			'url',
		);
	}

	/*
	 * Add Endpoint Route
	 */
	public function register_routes() {

		// Create Require Params
		$params = array();
		foreach ( self::require_params_hit() as $p ) {
			$params[ $p ] = array( 'required' => true );
		}

		// Get Hit
		register_rest_route( self::route, '/hit', array(
			'methods'             => \WP_REST_Server::READABLE,
			'permission_callback' => function () {
				return true;
			},
			'callback'            => array( $this, 'hit' ),
			'args'                => array_merge(
				array( '_wpnonce' => array(
					'required'          => true,
					'validate_callback' => function ( $value ) {
						return wp_verify_nonce( $value, 'wp_rest' );
					}
				) ), $params )
		) );

		// Test REST API WordPress is activate
		register_rest_route( self::route, '/connection', [
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => [ $this, 'connection' ]
		 ] );
		 
		 register_rest_route(
			self::route,
			'/stats',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_stats' ],
				'args'                => [
					'start_date' => [
						'validate_callback' => [ $this, 'validate_date_param' ],
					],
					'end_date'   => [
						'validate_callback' => [ $this, 'validate_date_param' ],
					],
				],
				'permission_callback' => function () {
					//return current_user_can( 'view_yardline' );
					return true;
				},
			]
		);
	}

	/**
	 * Check Is Test Service Request
	 * @return array|null
	 */
	public function connection() {
		if ( isset( $_REQUEST['rest-api-yardline'] ) ) {
			return array( "rest-api-yardline" => "OK" );
		}

		return null;
	}

	/*
	 * Wp Statistic Hit Save
	 */
	public function hit( \WP_REST_Request $request ) {
		
		dev_log('API hit fired');
		// Get Params
		$url        = $request->get_param( 'url' );
		$user_agent = $request->get_param( 'ua' );
		$new_visitor = $request->get_param( 'nv' );
		$unique_pageview = $request->get_param( 'up' );
		$referer = $request->get_param( 'r' );
		//dev_log( 'Post ID: ' . url_to_postid( $url ));
		//dev_log( 'UA: ' . $user_agent );
		//dev_log( 'New V: ' . $new_visitor );
		//dev_log( 'UP: ' .$unique_pageview );
		if ( empty( $url ) || $unique_pageview == 0 ) {
			return;
		}
		
		$stats_data = [
			'url' => esc_url_raw( $url ),
			'visitors' => $new_visitor,
			'pageviews' =>$unique_pageview,
			'referer' => esc_url_raw( $referer )
		];
		$hit_tracker = new Hit_Tracker();
		$hit_tracker->track_hit( $stats_data );
		
		//need to add these to the DB here??
		$post_id = url_to_postid( $url );
		//Determine if has post_id
		if( $post_id ) {
			$stats_data['post_type'] = get_post_type( $post_id );
		}
		
		// Set Return
		return new \WP_REST_Response( array( 'status' => true, 'message' => __( 'Visitor Hit was recorded successfully.', 'yardline' ) ) );
	}

	/*
	 * Check is Rest Request
	 */
	static public function is_rest() {
		

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			if ( isset( $_REQUEST[ self::_Argument ] ) ) {
				return true;
			}
		}
		return false;
	}

	/*
	 * Get Params Request
	 */
	static public function params( $params ) {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST and isset( $_REQUEST[ self::_Argument ] ) ) {
			$data = array();
			foreach ( $_REQUEST as $key => $value ) {
				if ( ! in_array( $key, array( '_wpnonce' ) ) ) {
					$data[ $key ] = trim( $value );
				}
			}

			if ( isset( $data[ $params ] ) ) {
				return $data[ $params ];
			}
		}

		return false;
	}

	public function get_stats( \WP_REST_Request $request ) {
		$results = [];
		//date range
		return $results;
	}
}
