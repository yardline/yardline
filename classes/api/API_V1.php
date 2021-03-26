<?php
namespace Yardline\Api;

use Yardline\Site_Stat;
use Yardline\Hit_Tracker;
use Yardline\Site_Stats;
use Yardline\Page_Views;
use Yardline\Referrers;
/**
 * Class API_V1
 */
class API_V1 {

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

		register_rest_route(
			self::route,
			'/pageviews',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_pageviews' ],
				'args'                => [
					'start_date' => [
						'validate_callback' => [ $this, 'validate_date_param' ],
					],
					'end_date'   => [
						'validate_callback' => [ $this, 'validate_date_param' ],
					],
				],
				'permission_callback' => function () {
					return true;
				},
			]
		);

		register_rest_route(
			self::route,
			'/referrers',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_referrers' ],
				'args'                => [
					'start_date' => [
						'validate_callback' => [ $this, 'validate_date_param' ],
					],
					'end_date'   => [
						'validate_callback' => [ $this, 'validate_date_param' ],
					],
				],
				'permission_callback' => function () {
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
	 * Hit Save
	 */
	public function hit( \WP_REST_Request $request ) {
		
		// Get Params
		$url        = $request->get_param( 'url' );
		$user_agent = $request->get_param( 'ua' );
		$new_visitor = $request->get_param( 'nv' );
		$unique_pageview = $request->get_param( 'up' );
		$referrer = $request->get_param( 'r' );
		
		if ( empty( $url ) ) {
			return;
		}
		
		$stats_data = [
			'url' => esc_url_raw( $url ),
			'visitors' => $new_visitor,
			'pageviews' =>$unique_pageview,
			'referrer' => esc_url_raw( $referrer )
		];
		$hit_tracker = new Hit_Tracker();
		$hit_tracker->track_hit( $stats_data );
		$post_id = url_to_postid( $url );
		if( $post_id ) {
			$stats_data['post_type'] = get_post_type( $post_id );
		}
		
		// Set Return
		return new \WP_REST_Response( array( 'status' => true, 'message' => __( 'Visitor Hit was recorded successfully.', 'yardline' ) ) );
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
       
       	$params     = $request->get_query_params();
		$start_date = isset( $params['start_date'] ) ? $params['start_date'] : gmdate( 'Y-m-d', strtotime( '1st of this month' ) + get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS );
        $end_date   = isset( $params['end_date'] ) ? $params['end_date'] : gmdate( 'Y-m-d', time() + get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS );
		
		$site_stats = new Site_Stats();

		$stats_for_range =  $site_stats->get_for_date_range( $start_date, $end_date );
		return $stats_for_range;
	}
	
	public function get_pageviews( \WP_REST_Request $request ) {
		$page_views = new Page_Views();
		$params     = $request->get_query_params();
		$start_date = isset( $params['start_date'] ) ? $params['start_date'] : gmdate( 'Y-m-d', strtotime( '1st of this month' ) + get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS );
        $end_date   = isset( $params['end_date'] ) ? $params['end_date'] : gmdate( 'Y-m-d', time() + get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS );
		
		return $page_views->get_for_date_range( $start_date, $end_date );;
	}

	public function get_referrers( \WP_REST_Request $request ) {
		$referrers = new Referrers();
		$params     = $request->get_query_params();
		$start_date = isset( $params['start_date'] ) ? $params['start_date'] : gmdate( 'Y-m-d', strtotime( '1st of this month' ) + get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS );
        $end_date   = isset( $params['end_date'] ) ? $params['end_date'] : gmdate( 'Y-m-d', time() + get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS );
		
		return $referrers->get_for_date_range( $start_date, $end_date );;
	}
    
    public function validate_date_param( $param, $one, $two ) {
		return strtotime( $param ) !== false;
	}
}