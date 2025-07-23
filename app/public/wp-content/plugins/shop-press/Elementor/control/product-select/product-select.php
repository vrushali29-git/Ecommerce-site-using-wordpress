<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Base_Data_Control;

class ShopPressSelectProduct extends Base_Data_Control {

	public function get_type() {
		return 'shoppress_product_select';
	}

	public function enqueue() {
		wp_enqueue_script( 'select2' );
		wp_enqueue_style( 'select2' );

		wp_enqueue_script( 'sp-woo-product-select', SHOPPRESS_URL . 'public/admin/elementor/controls/product-select/product-select.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script(
			'sp-woo-product-select',
			'SPWooProductSelect',
			array(
				'restUrl'   => esc_url_raw( rest_url( 'wc/v3/products' ) ),
				'restNonce' => wp_create_nonce( 'wp_rest' ),
			)
		);
	}

	protected function get_default_settings() {
		return array(
			'label_block' => true,
			'multiple'    => false,
		);
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo esc_attr( $control_uid ); ?>" class="sp-woo-product-select" data-setting="{{ data.name }}" multiple>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
