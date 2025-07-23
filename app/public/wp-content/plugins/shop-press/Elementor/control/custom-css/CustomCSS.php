<?php
/**
 * Custom CSS Control.
 *
 * @package ShopPress
 * @since   1.2.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Base_Data_Control;
use Elementor\Plugin;

class ShopPressElementorCustomCSS extends Base_Data_Control {
	/**
	 * Control type name.
	 *
	 * @since   1.2.0
	 */
	public function get_type() {
		return 'shoppress_custom_code';
	}

	/**
	 * Control enqueue live assets.
	 *
	 * @since   1.2.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'shoppress-custom-code', SHOPPRESS_URL . 'public/lib/codemiror/codemirror.css', array(), '1.1.2' );
		wp_enqueue_script( 'shoppress-custom-code', SHOPPRESS_URL . 'public/lib/codemiror/codemirror.js', array( 'jquery' ), '1.1.2', false );
		wp_enqueue_script( 'shoppress-custom-code-css', SHOPPRESS_URL . 'public/lib/codemiror/codemirror-css.js', array( 'jquery' ), '1.1.2', false );
		wp_enqueue_script( 'shoppress-custom-code-close-bracket', SHOPPRESS_URL . 'public/lib/codemiror/codemirror-autoclosebracket.js', array( 'jquery' ), '1.1.2', false );
		wp_enqueue_script( 'shoppress-custom-code-customize', SHOPPRESS_URL . 'public/lib/codemiror/codemirror-customize.js', array( 'jquery' ), '1.1.2', false );
	}

	/**
	 * Control HTML structure.
	 *
	 * @since   1.2.0
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="sp-custom-css-field">
				<form>
					<textarea id="<?php echo esc_attr( $control_uid ); ?>" class="elementor-input-style" rows="{{ data.rows }}" data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}"></textarea>
				</form>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{ data.description }}</div>
		<# } #>
		<?php
	}
}

/**
 * Add custom css control to widgets
 *
 * @param \ShopPress\Elementor\ShopPressWidgets $widget_class
 *
 * @since 1.2.0
 *
 * @return void
 */
function shop_press_add_custom_css_control_to_widgets( $widget_class ) {

	$widget_class->start_controls_section(
		'shoppress_custom_css_section',
		array(
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			'label' => esc_html__( 'Custom CSS', 'shop-press' ),
		)
	);

	$widget_class->add_control(
		'shoppress_custom_css',
		array(
			'type' => 'shoppress_custom_code',
		)
	);

	$widget_class->end_controls_section();
}

add_action( 'shoppress/elementor/widget/register_controls_init', 'shop_press_add_custom_css_control_to_widgets' );

function shoppress_elementor_custom_css_output( $settings ) {
	shoppress_module_custom_css_editor( isset( $settings['shoppress_custom_css'] ) ? $settings['shoppress_custom_css'] : '' );
}
add_action( 'shoppress/widget/before_render', 'shoppress_elementor_custom_css_output', 10, 1 );


function shoppress_module_custom_css_editor( $custom_css ) {
	if ( ! empty( $custom_css ) && Plugin::$instance->editor->is_edit_mode() ) {
		echo '<style>' . $custom_css . '</style>';
	}
}


function shoppress_module_custom_css_frontend( $post_css, $element ) {
	if ( $post_css instanceof Dynamic_CSS ) {
		return;
	}
	$element_settings = $element->get_settings();
	$custom_css       = ! empty( $element_settings['shoppress_custom_css'] ) ? trim( $element_settings['shoppress_custom_css'] ) : '';
	if ( ! empty( $custom_css ) ) {
		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	} else {
		return;
	}
}
add_action( 'elementor/element/parse_css', 'shoppress_module_custom_css_frontend', 10, 2 );
