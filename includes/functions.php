<?php
namespace Yardline;

use Yardline\HTML;
/**
 * Get a variable from an array or default if it doesn't exist.
 *
 * @param        $array
 * @param string $key
 * @param bool   $default
 *
 * @return mixed
 */
function get_array_var( $array, $key = '', $default = false ) {
	if ( isset_not_empty( $array, $key ) ) {
		if ( is_object( $array ) ) {
			return $array->$key;
		} else if ( is_array( $array ) ) {
			return $array[ $key ];
		}
	}

	return $default;
}

/**
 * Return if a value in an array isset and is not empty
 *
 * @param $array
 * @param $key
 *
 * @return bool
 */
function isset_not_empty( $array, $key = '' ) {
	if ( is_object( $array ) ) {
		return isset( $array->$key ) && ! empty( $array->$key );
	} else if ( is_array( $array ) ) {
		return isset( $array[ $key ] ) && ! empty( $array[ $key ] );
	}

	return false;
}

/**
 * Get a variable from the $_REQUEST global
 *
 * @param string $key
 * @param bool   $default
 * @param bool   $post_only
 *
 * @return mixed
 */
function get_request_var( $key = '', $default = false, $post_only = false ) {
	$global = $post_only ? $_POST : $_REQUEST;

	return wp_unslash( get_array_var( $global, $key, $default ) );
}

if ( ! function_exists( __NAMESPACE__ . '\is_white_labeled' ) ) {

	/**
	 * Whether the Groundhogg is white labeled or not.
	 *
	 * @return bool
	 */
	function is_white_labeled() {
		return false; // todo make false
	}
}

if ( ! function_exists( __NAMESPACE__ . '\white_labeled_name' ) ) {

	/**
	 * Return replacement name form white label
	 *
	 * @return string
	 */
	function white_labeled_name() {
		return 'Yardline';  // TODO
	}
}

/**
 * Shorthand;
 *
 * @return HTML
 */
function html() {
	return Plugin::$instance->utils->html;
}

/**
 * Show the logo.
 *
 * @param string $color
 * @param int    $width
 *
 * @return string|bool
 */
function yardline_logo( $color = 'black', $width = 300, $echo = true ) {
	return 'yardline_logo in functions file';	switch ( $color ) {
		default:
		case 'black':
			$link = 'logo-black-1000x182.png';
			break;
		case 'white':
			$link = 'logo-white-1000x182.png';
			break;
	}

	$img = html()->e( 'img', [
		'src'   => YARDLINE_ASSETS_URL . 'images/' . $link,
		'width' => $width
	] );

	if ( $echo ) {
		echo $img;

		return true;
	}

	return $img;
}

/**
 * Easier url builder.
 *
 * @param $page
 * @param $args
 *
 * @return string
 */
function admin_page_url( $page, $args = [] ) {
	$args = wp_parse_args( $args, [ 'page' => $page ] );

	return add_query_arg( $args, admin_url( 'admin.php' ) );
}

/**
 * Return a dashicon
 *
 * @param        $icon
 * @param string $wrap
 * @param array  $atts
 *
 * @return string
 */
function dashicon($icon, $wrap = 'span', $atts = [], $echo = false)
{
    $atts = wp_parse_args($atts, [
        'class' => 'dashicons dashicons-'
    ]);

    $atts['class'] .= $icon;

    $html = html()->e($wrap, $atts, '', false);

    if ($echo) {
        echo $html;
    }
}

/**
 * Convert array to HTML tag attributes
 *
 * @param $atts
 *
 * @return string
 */
function array_to_atts( $atts ) {
	$tag = '';

	foreach ( $atts as $key => $value ) {

		if ( empty( $value ) ) {
			continue;
		}

		$key = strtolower( $key );

		switch ( $key ) {
			case 'style':
				$value = array_to_css( $value );
				break;
			case 'href':
			case 'action':
			case 'src':
				$value = esc_url( $value );
				break;
			default:
				if ( is_array( $value ) ) {
					$value = implode( ' ', $value );
				}

				$value = esc_attr( $value );
				break;

		}

		$tag .= sanitize_key( $key ) . '="' . $value . '" ';
	}

	return $tag;
}

/**
 * Enqueues the modal scripts
 *
 * @return Modal
 *
 */
function enqueue_yardline_modal() {
	//return Modal::instance();
}
