<?php

namespace Yardline\Admin;

use Yardline\Admin\Admin_Page;

use function Yardline\get_array_var;
use function Yardline\get_request_var;
use function Yardline\html;
use function Yardline\is_white_labeled;
use Yardline\Plugin;
use function Yardline\isset_not_empty;
use function Yardline\white_labeled_name;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Settings
 *
 * This  is your fairly typical settigns page.
 * It's a BIT of a mess, but I digress.
 *
 * @since       File available since Release 0.1
 * @subpackage  Admin/Settings
 * @author      Adrian Tobey <info@groundhogg.io>
 * @copyright   Copyright (c) 2018, Groundhogg Inc.
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License v3
 * @package     Admin
 */
class Settings_Page extends Admin_Page {

	// UNUSED FUNCTIONS
	protected function add_ajax_actions() {
	}

	public function help() {
	}

	public function scripts() {
		wp_enqueue_style( 'yardline-admin' );
		//wp_enqueue_style( 'groundhogg-admin-extensions' );
	}

	/**
	 * Settings_Page constructor.
	 */
	public function __construct() {
		$this->add_additional_actions();
		parent::__construct();
	}

	protected function add_additional_actions() {
		add_action( 'admin_init', array( $this, 'init_defaults' ) );
		add_action( 'admin_init', array( $this, 'register_sections' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		
	}

	public function get_slug() {
		return 'yl_settings';
	}

	public function get_name() {
		return _x( 'Settings', 'page_title', 'yardline' );
	}

	public function get_title() {
		return sprintf( __( "%s Settings", 'yardline' ), white_labeled_name() );
	}

	public function get_cap() {
		return 'manage_options';
	}

	public function get_item_type() {
		return null;
	}

	public function get_priority() {
		return 99;
	}

	protected function get_title_actions() {
		return [];
	}

	/**
	 * A list of the settings tabs
	 *
	 * @var array
	 */
	private $tabs;

	/**
	 * A list of tab sections
	 *
	 * @var array
	 */
	private $sections;

	/**
	 * A list of all the settings
	 *
	 * @var array
	 */
	private $settings;


	/**
	 * Init the default settings & sections.
	 */
	public function init_defaults() {
		$this->tabs     = $this->get_default_tabs();
		$this->sections = $this->get_default_sections();
		$this->settings = $this->get_default_settings();

		do_action( 'yardline/admin/settings/init_defaults', $this );
	}

	public function screen_options() {
	}

	/**
	 * display the API keys table
	 */
	public function api_keys_table() {
		$api_keys_table = new API_Keys_Table();
		$api_keys_table->prepare_items();
		?>
        <h3><?php _e( 'API Keys', 'yardline' ); ?></h3>
        <div style="max-width: 900px;"><?php
		$api_keys_table->display();
		?></div><?php
	}

	/**
	 * When we submit the form but to the page not to options.php
	 */
	public function process_view() {

		if ( get_request_var( 'activate_license' ) ) {

			$licenses = get_request_var( 'license', [] );

			foreach ( $licenses as $item_id => $license ) {

				//License_Manager::activate_license( $license, absint( $item_id ) );

			}

		}

	}

	public function process_deactivate_license() {

		$item_id = absint( get_request_var( 'extension' ) );

		if ( $item_id ) {
			//License_Manager::deactivate_license( $item_id );
		}

	}

	/**
	 * Add the default settings sections
	 */
	public function register_sections() {

		do_action( 'yl_settings_pre_register_sections', $this );

		foreach ( $this->sections as $id => $section ) {

			$callback = array();

			if ( key_exists( 'callback', $section ) ) {
				$callback = $section['callback'];
			}

			add_settings_section( 'yl_' . $section['id'], $section['title'], $callback, 'yl_' . $section['tab'] );
		}

	}

	/**
	 * Returns a list of tabs
	 *
	 * @return array
	 */
	private function get_default_tabs() {
		$tabs = [
			'general'   => array(
				'id'    => 'general',
				'title' => _x( 'General', 'settings_tabs', 'yardline' )
			),		
			'misc'      => array(
				'id'    => 'misc',
				'title' => _x( 'Misc', 'settings_tabs', 'yardline' )
			),
		];

		if ( ! is_white_labeled() || ! is_multisite() || is_main_site() ) {
			$tabs['extensions'] = [
				'id'    => 'extensions',
				'title' => _x( 'Licenses', 'settings_tabs', 'yardline' ),
				'cap'   => 'edit_posts'
			];
		}

		return apply_filters( 'yardline/admin/settings/tabs', $tabs );
	}

	/**
	 * Returns a list of all the default sections
	 *
	 * @return array
	 */
	private function get_default_sections() {

		$sections = array(
			'business_info'     => array(
				'id'    => 'business_info',
				'title' => _x( 'Business Settings', 'settings_sections', 'yardline' ),
				'tab'   => 'general'
			),
			'general_other'     => array(
				'id'    => 'general_other',
				'title' => _x( 'Other', 'settings_sections', 'yardline' ),
				'tab'   => 'general'
			),
			'misc_info'         => array(
				'id'    => 'misc_info',
				'title' => _x( 'Misc Settings', 'settings_sections', 'yardline' ),
				'tab'   => 'misc'
			),
			'wp_cron'           => [
				'id'    => 'wp_cron',
				'title' => _x( 'WP Cron', 'settings_sections', 'yardline' ),
				'tab'   => 'misc',
			],
			'affiliate'         => array(
				'id'    => 'affiliate',
				'title' => _x( 'Affiliate Section', 'settings_sections', 'yardline' ),
				'tab'   => 'misc'
			),
			'captcha'           => array(
				'id'    => 'captcha',
				'title' => _x( 'Google reCAPTCHA', 'settings_sections', 'yardline' ),
				'tab'   => 'misc'
			),
			'event_notices'     => [
				'id'    => 'event_notices',
				'title' => _x( 'Event Notices', 'settings_sections', 'yardline' ),
				'tab'   => 'misc'
			],
			'compliance'        => array(
				'id'    => 'compliance',
				'title' => _x( 'Compliance', 'settings_sections', 'yardline' ),
				'tab'   => 'marketing'
			),

			
			
		);

		if ( defined( 'DISABLE_WP_CRON' ) && ! defined( 'YL_SHOW_DISABLE_WP_CRON_OPTION' ) ) {
			unset( $sections['wp_cron'] );
		}


		return apply_filters( 'yardline/admin/settings/sections', $sections );
	}

	private function get_default_settings() {

		$settings = array(
			
			
			
			
			
			
			
			
			
			
			'yl_uninstall_on_delete'                 => array(
				'id'      => 'yl_uninstall_on_delete',
				'section' => 'misc_info',
				'label'   => sprintf( _x( 'Delete %s data', 'settings', 'yardline' ), white_labeled_name() ),
				'desc'    => _x( 'Delete all information when uninstalling. This cannot be undone.', 'settings', 'yardline' ),
				'type'    => 'checkbox',
				'atts'    => array(
					'label' => __( 'Enable' ),
					
					'name'  => 'yl_uninstall_on_delete',
					'id'    => 'yl_uninstall_on_delete',
					'value' => 'on',
				),
			),
			'yl_opted_in_stats_collection'           => array(
				'id'      => 'yl_opted_in_stats_collection',
				'section' => 'misc_info',
				'label'   => _x( 'Optin to anonymous usage tracking.', 'settings', 'groundhogg' ),
				'desc'    => sprintf( _x( 'Help us make %s better by providing anonymous usage information about your site.', 'settings', 'groundhogg' ), white_labeled_name() ),
				'type'    => 'checkbox',
				'atts'    => array(
					'label' => __( 'Enable' ),
					
					'name'  => 'yl_opted_in_stats_collection',
					'id'    => 'yl_opted_in_stats_collection',
					'value' => 'on',
				),
			),
			'yl_privacy_policy'                      => array(
				'id'      => 'yl_privacy_policy',
				'section' => 'compliance',
				'label'   => __( 'Privacy Policy' ),
				'desc'    => _x( 'Link to your privacy policy.', 'settings', 'yardline' ),
				'type'    => 'link_picker',
				'atts'    => array(
					'name' => 'yl_privacy_policy',
					'id'   => 'yl_privacy_policy',
				),
			),
			'yl_enable_gdpr'                         => array(
				'id'      => 'yl_enable_gdpr',
				'section' => 'compliance',
				'label'   => _x( 'Enable GDPR features.', 'settings', 'yardline' ),
				'desc'    => _x( 'This will add a consent box to your forms as well as a "Delete Everything" Button to your email preferences page.', 'settings', 'yardline' ),
				'type'    => 'checkbox',
				'atts'    => array(
					'label' => __( 'Enable' ),
					'name'  => 'yl_enable_gdpr',
					'id'    => 'yl_enable_gdpr',
					'value' => 'on',
				),
            )
        );
			
		if ( ! defined( 'DISABLE_WP_CRON' ) || defined( 'YL_SHOW_DISABLE_WP_CRON_OPTION' ) ) {
			$settings['yl_disable_wp_cron'] = array(
				'id'      => 'yl_disable_wp_cron',
				'section' => 'wp_cron',
				'label'   => _x( 'Disable WP Cron.', 'settings', 'yardline' ),
				'desc'    => _x( 'Disable the built-in WP Cron system. This is recommended if you are using an external cron job.', 'settings', 'yardline' ),
				'type'    => 'checkbox',
				'atts'    => array(
					'label' => __( 'Disable' ),
					'name'  => 'yl_disable_wp_cron',
					'id'    => 'yl_disable_wp_cron',
					'value' => 'on',
				),
			);
		}

		return apply_filters( 'yardline/admin/settings/settings', $settings );
	}

	/**
	 * Register all the settings
	 */
	public function register_settings() {

		do_action( 'yardline/admin/register_settings/before', $this );

		foreach ( $this->settings as $id => $setting ) {

			if ( ! isset_not_empty( $this->sections, $setting['section'] ) ) {
				continue;
			}

			add_settings_field( $setting['id'], $setting['label'], array(
				$this,
				'settings_callback'
			), 'yl_' . $this->sections[ $setting['section'] ]['tab'], 'yl_' . $setting['section'], $setting );
			$args = isset_not_empty( $setting, 'args' ) ? $setting['args'] : [];
			register_setting( 'yl_' . $this->sections[ $setting['section'] ]['tab'], $setting['id'], $args );
		}

		do_action( 'yardline/admin/register_settings/after', $this );
	}

	/**
	 * Add a tab to the settings page
	 *
	 * @param string $id    if of the tab
	 * @param string $title title of the tab
	 *
	 * @return bool
	 */
	public function add_tab( $id = '', $title = '' ) {
		if ( ! $id || ! $title ) {
			return false;
		}


		$this->tabs[ $id ] = array(
			'id'    => $id,
			'title' => $title,
		);

		return true;
	}

	/**
	 * Add a section to a tab
	 *
	 * @param string $id    id of the section
	 * @param string $title title of the section
	 * @param string $tab   the tab
	 *
	 * @return bool
	 */
	public function add_section( $id = '', $title = '', $tab = '' ) {
		if ( ! $id || ! $title || ! $tab ) {
			return false;
		}


		$this->sections[ $id ] = array(
			'id'    => $id,
			'title' => $title,
			'tab'   => $tab,
		);

		return true;
	}

	/**
	 * Add a setting to the page
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	public function add_setting( $args = array() ) {
		$setting = wp_parse_args( $args, array(
				'id'      => '',
				'section' => 'misc',
				'label'   => '',
				'desc'    => '',
				'type'    => 'input',
				'atts'    => array(
					//keep brackets for backwards compat
					'name' => '',
					'id'   => '',
				)
			)
		);

		if ( empty( $setting['id'] ) ) {
			return false;
		}

		$this->settings[ $setting['id'] ] = $setting;

		return true;
	}

	/**
	 * Return the id of the active tab
	 *
	 * @return string
	 */
	private function active_tab() {
		return sanitize_key( get_request_var( 'tab', 'general' ) );
	}

	/**
	 * Return whether a tab has settings or not.
	 *
	 * @param $tab string the ID of the tab
	 *
	 * @return bool
	 */
	private function tab_has_settings( $tab = '' ) {

		if ( ! $tab ) {
			$tab = $this->active_tab();
		}

		global $wp_settings_sections;

		return isset( $wp_settings_sections[ 'yl_' . $tab ] );
	}

	/**
	 * If a cap is specific for the tab, check to see if the user has the required permissions...
	 *
	 * @param $tab
	 *
	 * @return bool
	 */
	private function user_can_access_tab( $tab = '' ) {

		if ( ! $tab ) {
			$tab = $this->active_tab();
		}

		$tab = get_array_var( $this->tabs, $tab );

		// Check for cap restriction on the tab...
		$cap = get_array_var( $tab, 'cap' );

		// ignore if there is no cap, but if there is one check if the user has require privileges...
		if ( $cap && ! current_user_can( $cap ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Output the settings content
	 */
//    public function settings_content()
	public function view() {
		?>
        <style>
            .select2 {
                max-width: 300px;
            }
        </style>
        <div class="wrap">
			<?php
			settings_errors();
			$action = $this->tab_has_settings( $this->active_tab() ) ? 'options.php' : ''; ?>
            <form method="POST" enctype="multipart/form-data" action="<?php echo $action; ?>">

                <!-- BEGIN TABS -->
                <h2 class="nav-tab-wrapper">
					<?php foreach ( $this->tabs as $id => $tab ):

						// Check for cap restriction on the tab...
						$cap = get_array_var( $tab, 'cap' );

						// ignore if there is no cap, but if there is one check if the user has require privileges...
						if ( $cap && ! current_user_can( $cap ) ) {
							continue;
						}

						?>

                        <a href="?page=yl_settings&tab=<?php echo $tab['id']; ?>"
                           class="nav-tab <?php echo $this->active_tab() == $tab['id'] ? 'nav-tab-active' : ''; ?>"><?php _e( $tab['title'], 'groundhogg' ); ?></a>
					<?php endforeach; ?>
                </h2>
                <!-- END TABS -->

                <!-- BEGIN SETTINGS -->
				<?php
				if ( $this->tab_has_settings() && $this->user_can_access_tab() ) {
					
					settings_fields( 'yl_' . $this->active_tab() );
					do_settings_sections( 'yl_' . $this->active_tab() );
					do_action( "yardline/admin/settings/{$this->active_tab()}/after_settings" );
					submit_button();

				}

				do_action( "yardline/admin/settings/{$this->active_tab()}/after_submit" );
				?>
                <!-- END SETTINGS -->
            </form>
			<?php do_action( "yardline/admin/settings/{$this->active_tab()}/after_form" ); ?>
        </div> <?php
	}

	public function settings_callback( $field ) {
		$value = Plugin::$instance->settings->get_option( $field['id'] );

		switch ( $field['type'] ) {
			case 'editor':
				$field['atts']['id']       = $field['id'];
				$field['atts']['content']  = $value;
				$field['atts']['settings'] = [ 'editor_height' => 200 ];
				break;
			case 'select2':
			case 'dropdown_emails':
			case 'tag_picker':
				$field['atts']['selected'] = is_array( $value ) ? $value : [ $value ];
				break;
			case 'dropdown':
			case 'dropdown_owners':
				$field['atts']['selected'] = $value;
				break;
			case 'checkbox':
				$field['atts']['checked'] = (bool) $value;
				break;
			case 'input':
			default:
				$field['atts']['value'] = $value;
				break;
		}

		$field['atts']['id'] = esc_attr( sanitize_key( $field['id'] ) );

		echo html()->wrap( call_user_func( array(
			Plugin::$instance->utils->html,
			$field['type']
		), $field['atts'] ), 'div', [ 'style' => [ 'max-width' => '700px' ] ] );

		if ( isset( $field['desc'] ) && $desc = $field['desc'] ) {
			printf( '<p class="description">%s</p>', $desc );
		}
	}


}