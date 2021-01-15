<?php
/**
 * Plugin Name: Yardline
 * Plugin URI: https://yardlineanalytics.com
 * Description: Learn how to use namespaces and autoload with composer in WordPress plugins.
 * Version: 0.1.0
 * Author: Yardline
 * Author URI: https://yardlineanalytics.com
 * License: GPL2
 * Text Domain: yardline
 * Domain Path: languages
 * 
 * Yardline is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Yardline is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'YARDLINE_VERSION', '0.1' );

define( 'YARDLINE__FILE__', __FILE__ );
define( 'YARDLINE_PLUGIN_BASE', plugin_basename( YARDLINE__FILE__ ) );
define( 'YARDLINE_PATH', plugin_dir_path( YARDLINE__FILE__ ) );

define( 'YARDLINE_URL', plugins_url( '/', YARDLINE__FILE__ ) );

define( 'YARDLINE_ASSETS_PATH', YARDLINE_PATH . 'assets/' );
define( 'YARDLINE_ASSETS_URL', YARDLINE_URL . 'assets/' );
if ( $upload_dir = wp_get_upload_dir() ) {
    define('YARDLINE_HIT_FILE', $upload_dir['basedir'] .'/yardlinehits.php');
}

add_action( 'plugins_loaded', 'yardline_load_plugin_textdomain' );

define( 'YARDLINE_TEXT_DOMAIN', 'yardline' );

if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
    add_action( 'admin_notices', 'yardline_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.9', '>=' ) ) {
    add_action( 'admin_notices', 'yardline_fail_wp_version' );
} else {
    require YARDLINE_PATH . 'bootstrap.php';
}


/**
 * Load Yardline textdomain.
 *
 * Load gettext translate for Yardline text domain.
 *
 * @since 1.0
 *
 * @return void
 */
function yardline_load_plugin_textdomain() {
    load_plugin_textdomain( 'yardline', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Yardline admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0
 *
 * @return void
 */
function yardline_fail_php_version() {
    
    $message = sprintf( esc_html__( 'Yardline requires PHP version %s+, plugin is currently NOT RUNNING.', 'yardline' ), '5.6' );
    $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
    echo wp_kses_post( $html_message );
}

/**
 * Yardline admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.0
 *
 * @return void
 */
function yardline_fail_wp_version() {
    $message = sprintf( esc_html__( 'Yardline requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'yardline' ), '4.9' );
    $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
    echo wp_kses_post( $html_message );
}