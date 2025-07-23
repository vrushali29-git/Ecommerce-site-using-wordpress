<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Attributes extends ShopPressWidgets {

	public function get_name() {
		return 'sp-Attributes';
	}

	public function get_title() {
		return __( 'Product Attributes', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-attributes';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-attributes-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
				'table'   => array(
					'label'    => esc_html__( 'Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-product-attributes',
					'wrapper'  => '{{WRAPPER}} .sp-attributes-wrapper',
				),
			)
		);
		$this->register_group_styler(
			'table',
			__( 'Table', 'shop-press' ),
			array(
				'tbody'      => array(
					'label'    => esc_html__( 'Table Body', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tbody',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-attributes',
				),
				'tr'         => array(
					'label'    => esc_html__( 'Table Row', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.woocommerce-product-attributes-item',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-attributes tbody',
				),
				'th'         => array(
					'label'    => esc_html__( 'Table Header', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'th.woocommerce-product-attributes-item__label',
					'wrapper'  => '{{WRAPPER}} tr.woocommerce-product-attributes-item',
				),
				'td'         => array(
					'label'    => esc_html__( 'Value', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'td.woocommerce-product-attributes-item__value',
					'wrapper'  => '{{WRAPPER}} tr.woocommerce-product-attributes-item',
				),
				'td_content' => array(
					'label'    => esc_html__( 'Value Content', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p',
					'wrapper'  => '{{WRAPPER}} td.woocommerce-product-attributes-item__value',
				),
				'td_link'    => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} td.woocommerce-product-attributes-item__value p',
				),
			)
		);
	}

	protected function register_controls() {
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-attributes' );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-attributes-wrapper">
			<table class="woocommerce-product-attributes shop_attributes">
				<tbody>
					<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--attribute_pa_color">
						<th class="woocommerce-product-attributes-item__label">Color</th>
						<td class="woocommerce-product-attributes-item__value"><p>Blue, Gray, Green, Red, Yellow</p>
					</td>
					</tr>
						<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--attribute_pa_size">
						<th class="woocommerce-product-attributes-item__label">Size</th>
						<td class="woocommerce-product-attributes-item__value"><p>Large, Medium, Small</p>
					</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}
}
