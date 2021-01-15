<?php
namespace Yardline\Utils;

use function Yardline\array_to_atts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * HTML
 *
 * Helper class for reusable html markup.
 *
 * @since       1.0
 * @package     Includes
 */
class HTML {

	const INPUT = 'input';
	const NUMBER = 'number';
	const BUTTON = 'button';
	const TOGGLE = 'toggle';
	const CHECKBOX = 'checkbox';
	const RANGE = 'range';
	const TEXTAREA = 'textarea';
	const SELECT2 = 'select2';
	const TAG_PICKER = 'tag_picker';
	const FONT_PICKER = 'font_picker';
	const DATE_PICKER = 'date_picker';
	const LINK_PICKER = 'link_picker';
	const COLOR_PICKER = 'color_picker';
	const IMAGE_PICKER = 'image_picker';
	const DROPDOWN = 'dropdown';


	/**
	 * HTML constructor.
	 *
	 */
	public function __construct() {
	}

	
	public function tabs( $tabs = [], $active_tab = false, $class = "nav-tab-wrapper" ) {
		if ( empty( $tabs ) ) {
			return;
		}

		if ( ! $active_tab ) {
			$active_tab = get_request_var( 'tab' );

			// Get first Tab
			if ( ! $active_tab ) {
				$tab_keys   = array_keys( $tabs );
				$active_tab = array_shift( $tab_keys );
			}
		}

		?>
        <h2 class="<?php esc_attr_e( $class ); ?>">
			<?php foreach ( $tabs as $id => $tab ):

				echo html()->e( 'a', [
					'href'  => esc_url( add_query_arg( [ 'tab' => $id ], $_SERVER['REQUEST_URI'] ) ),
					'class' => 'nav-tab' . ( $active_tab == $id ? ' nav-tab-active' : '' ),
					'id'    => $id,
				], $tab );

			endforeach; ?>
        </h2>
		<?php
	}

	
	/**
	 * Return P description.
	 *
	 * @param $text
	 *
	 * @return string
	 */
	public function description( $text ) {
		return sprintf( '<p class="description">%s</p>', $text );
	}

	
	/**
	 * Wrap arbitraty HTML in another element
	 *
	 * @param string $content
	 * @param string $e
	 * @param array  $atts
	 *
	 * @return string
	 */
	public function wrap( $content = '', $e = 'div', $atts = [] ) {
		if ( is_array( $content ) ) {
			$content = implode( '', $content );
		}

		return sprintf( '<%1$s %2$s>%3$s</%1$s>', esc_html( $e ), array_to_atts( $atts ), $content );
	}

	/**
	 * Generate an html element.
	 *
	 * @param string $e
	 * @param array  $atts
	 * @param bool   $self_closing
	 *
	 * @return string
	 */
	public function e( $e = 'div', $atts = [], $content = '', $self_closing = true ) {
		if ( ! empty( $content ) || ! $self_closing ) {
			return $this->wrap( $content, $e, $atts );
		}

		return sprintf( '<%1$s %2$s/>', esc_html( $e ), array_to_atts( $atts ) );
	}


	/**
	 * @param array $args
	 *
	 * @return string
	 */
	public function editor( $args = [] ) {
		$args = wp_parse_args( $args, [
			'id'                  => '',
			'content'             => '',
			'settings'            => [],
			'replacements_button' => false,
		] );


		ob_start();

		wp_editor( $args['content'], $args['id'], $args['settings'] );


		return ob_get_clean();
	}

	
	/**
	 * Wrapper function for the INPUT
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function number( $args = [] ) {

		$a = wp_parse_args( $args, array(
			'type'        => 'number',
			'name'        => '',
			'id'          => '',
			'class'       => 'regular-text',
			'value'       => '',
			'attributes'  => '',
			'placeholder' => '',
			'min'         => 0,
			'max'         => 999999999,
			'step'        => 1
		) );

		return apply_filters( 'yardline/html/number', $this->input( $a ), $a );
	}

	/**
	 * Output a button
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function button( $args = [] ) {
		$a = wp_parse_args( $args, array(
			'type'  => 'button',
			'text'  => '',
			'name'  => '',
			'id'    => '',
			'class' => 'button button-secondary',
			'value' => '',
		) );

		$text = $a['text'];
		unset( $a['text'] );

		return apply_filters( 'yardline/html/button', $this->wrap( $text, 'button', $a ), $a );
	}

	/**
	 * Output a checkbox
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function checkbox( $args = [] ) {
		$a = wp_parse_args( $args, array(
			'label'   => '',
			'type'    => 'checkbox',
			'name'    => '',
			'id'      => '',
			'class'   => '',
			'value'   => '1',
			'checked' => false,
			'title'   => '',
		) );

		$html = $this->wrap( $this->input( $a ) . '&nbsp;' . $a['label'], 'label', [ 'class' => 'yl-checkbox-label' ] );

		return apply_filters( 'yardline/html/checkbox', $html, $a );
	}

	public function help_icon( $link = '' ) {
		return $this->modal_link( [
			'title'              => 'Help',
			'text'               => '',
			'footer_button_text' => __( 'Close' ),
			'id'                 => '',
			'source'             => $link,
			'height'             => 800,
			'width'              => 1000,
			'footer'             => 'true',
			'preventSave'        => 'true',
			'class'              => 'dashicons dashicons-editor-help help-icon'
		] );
	}

	/**
	 * Wrapper function for the INPUT
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function range( $args = [] ) {

		$a = wp_parse_args( $args, array(
			'type'        => 'range',
			'name'        => '',
			'id'          => '',
			'class'       => 'slider',
			'value'       => '',
			'attributes'  => '',
			'placeholder' => '',
			'min'         => 0,
			'max'         => 99999,
			'step'        => 1
		) );

		return apply_filters( 'yardline/html/range', $this->input( $a ), $a );
	}

	/**
	 * Output a simple textarea field
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function textarea( $args = [] ) {
		$a = wp_parse_args( $args, array(
			'name'        => '',
			'id'          => '',
			'class'       => '',
			'value'       => '',
			'cols'        => '100',
			'rows'        => '7',
			'placeholder' => '',
		) );

		$value = $a['value'];
		unset( $a['value'] );

		$html = $this->wrap( esc_html( $value ), 'textarea', $a );

		return apply_filters( 'yardline/html/textarea', $html, $a );

	}

	/**
	 * Output simple HTML for a dropdown field.
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function dropdown( $args = [] ) {
		$a = wp_parse_args( $args, array(
			'name'              => '',
			'id'                => '',
			'class'             => '',
			'options'           => array(),
			'selected'          => '',
			'multiple'          => false,
			'option_none'       => 'Please Select One',
			'option_none_value' => '',
		) );

		$a['selected'] = ensure_array( $a['selected'] );

		$optionHTML = '';

		if ( ! empty( $a['option_none'] ) ) {
			$optionHTML .= sprintf( "<option value='%s'>%s</option>",
				esc_attr( $a['option_none_value'] ),
				sanitize_text_field( $a['option_none'] )
			);
		}

		if ( is_array( get_array_var( $a, 'options' ) ) ) {

			$options = $a['options'];

			foreach ( $options as $value => $name ) {

				/* Include optgroup support */
				if ( is_array( $name ) ) {

					/* Redefine */
					$inner_options = $name;
					$label         = $value;

					$optionHTML .= sprintf( "<optgroup label='%s'>", $label );

					foreach ( $inner_options as $inner_value => $inner_name ) {

						$selected = ( in_array( $inner_value, $a['selected'] ) ) ? 'selected' : '';

						$optionHTML .= sprintf(
							"<option value='%s' %s>%s</option>",
							esc_attr( $inner_value ),
							$selected,
							esc_html( $inner_name )
						);
					}

					$optionHTML .= "</optgroup>";

				} else {
					$selected = ( in_array( $value, $a['selected'] ) ) ? 'selected' : '';

					$optionHTML .= sprintf(
						"<option value='%s' %s>%s</option>",
						esc_attr( $value ),
						$selected,
						esc_html( $name )
					);
				}

			}

		}

		if ( ! $a['multiple'] ) {
			unset( $a['multiple'] );
		}

		unset( $a['option_none'] );
		unset( $a['attributes'] );
		unset( $a['option_none_value'] );
		unset( $a['selected'] );
		unset( $a['options'] );

		$html = $this->wrap( $optionHTML, 'select', $a );

		return apply_filters( 'yardline/html/select', $html, $a );

	}

	/**
	 * Select 2 html input
	 *
	 * @param $args
	 *
	 * @type  $selected array list of $value which are selected
	 * @type  $data     array list of $value => $text options for the select 2
	 *
	 * @return string
	 */
	public function select2( $args = [] ) {
		$a = wp_parse_args( $args, array(
			'name'        => '',
			'id'          => '',
			'class'       => 'gh-select2',
			'data'        => [],
			'options'     => [],
			'selected'    => [],
			'multiple'    => false,
			'placeholder' => 'Please Select One',
			'tags'        => false,
			'style'       => [ 'min-width' => '400px' ]
		) );

		if ( isset_not_empty( $a, 'data' ) ) {
			$a['options'] = $a['data'];
		}

		unset( $a['data'] );

		if ( isset_not_empty( $a, 'placeholder' ) ) {
			$a['data-placeholder'] = $a['placeholder'];
		}

		unset( $a['placeholder'] );

		if ( isset_not_empty( $a, 'tags' ) ) {
			$a['data-tags'] = $a['tags'];
		}

		unset( $a['tags'] );

		$html = $this->dropdown( $a );

		//wp_enqueue_style( 'select2' );
		//wp_enqueue_script( 'select2' );
		//wp_enqueue_style( 'yardline-admin' );
		//wp_enqueue_script( 'yardline-admin' );

		return apply_filters( 'yardline/html/select2', $html, $a );

	}

	/**
	 * Return the HTML for a tag picker
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function tag_picker( $args = [] ) {
		$a = wp_parse_args( $args, array(
			'name'        => 'tags[]',
			'id'          => 'tags',
			'class'       => 'gh-tag-picker',
			'data'        => array(),
			'selected'    => array(),
			'multiple'    => true,
			'placeholder' => __( 'Please Select a Tag', 'yardline' ),
			'tags'        => true,
		) );

		$a['selected'] = wp_parse_id_list( $a['selected'] );

		if ( is_array( $a['selected'] ) ) {

			foreach ( $a['selected'] as $tag_id ) {

				if ( Plugin::$instance->dbs->get_db( 'tags' )->exists( $tag_id ) ) {

					$tag = Plugin::$instance->dbs->get_db( 'tags' )->get( $tag_id );

					$a['data'][ $tag_id ] = sprintf( "%s (%s)", $tag->tag_name, $tag->contact_count );

				}

			}
		}


		return apply_filters( 'yardline/html/tag_picker', $this->select2( $a ), $a );
	}


	/**
	 * Output a simple Jquery UI date picker
	 *
	 * @param $args
	 *
	 * @return string HTML
	 */
	public function date_picker( $args = [] ) {
		$a = wp_parse_args( $args, array(
			'name'        => '',
			'id'          => uniqid( 'date-' ),
			'class'       => 'regular-text',
			'value'       => '',
			'attributes'  => '',
			'placeholder' => 'yyyy-mm-dd',
			'min-date'    => date( 'Y-m-d', strtotime( 'today' ) ),
			'max-date'    => date( 'Y-m-d', strtotime( '+100 years' ) ),
			'format'      => 'yy-mm-dd'
		) );

		$html = sprintf(
			"<input type='text' id='%s' class='%s' name='%s' value='%s' placeholder='%s' autocomplete='off' %s><script>jQuery(function($){\$('#%s').datepicker({changeMonth: true,changeYear: true,minDate: '%s', maxDate: '%s',dateFormat:'%s'})});</script>",
			esc_attr( $a['id'] ),
			esc_attr( $a['class'] ),
			esc_attr( $a['name'] ),
			esc_attr( $a['value'] ),
			esc_attr( $a['placeholder'] ),
			$a['attributes'],
			esc_attr( $a['id'] ),
			esc_attr( $a['min-date'] ),
			esc_attr( $a['max-date'] ),
			esc_attr( $a['format'] )
		);

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui' );

		return apply_filters( 'yardline/html/date_picker', $html, $a );
	}

	
	/**
	 * Return HTML for a color picker
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function color_picker( $args = [] ) {
		$a = wp_parse_args( $args, array(
			'name'    => '',
			'id'      => '',
			'value'   => '',
			'default' => ''
		) );

		$html = sprintf(
			"<input type=\"text\" id=\"%s\" name=\"%s\" class=\"wpgh-color\" value=\"%s\" data-default-color=\"%s\" />",
			esc_attr( $a['id'] ),
			esc_attr( $a['name'] ),
			esc_attr( $a['value'] ),
			esc_attr( $a['default'] )
		);


		return apply_filters( 'yardline/html/color_picker', $html, $args );
	}

	
	
}