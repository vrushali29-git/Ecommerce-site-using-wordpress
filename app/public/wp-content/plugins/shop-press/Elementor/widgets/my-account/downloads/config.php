<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Downloads extends ShopPressWidgets {

	public function get_name() {
		return 'sp-dashboard-downloads';
	}

	public function get_title() {
		return __( 'My Account Downloads', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-downloads';
	}

	public function get_categories() {
		return array( 'sp_woo_dashboard' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-downloads',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'notice',
			__( 'Notice Message', 'shop-press' ),
			array(
				'notice_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-info',
					'wrapper'  => '{{WRAPPER}} .sp-downloads',
				),
				'notice_btn'     => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.button',
					'wrapper'  => '{{WRAPPER}} .sp-downloads .woocommerce-info',
				),
			)
		);

		$this->register_group_styler(
			'table',
			__( 'Table', 'shop-press' ),
			array(
				'table' => array(
					'label'    => esc_html__( 'table', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-table--order-downloads',
					'wrapper'  => '{{WRAPPER}}',
				),
				'thead' => array(
					'label'    => esc_html__( 'thead', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'thead',
					'wrapper'  => '{{WRAPPER}} .woocommerce-table--order-downloads',
				),
				'tr'    => array(
					'label'    => esc_html__( 'tr', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr',
					'wrapper'  => '{{WRAPPER}} .woocommerce-table--order-downloads',
				),
				'th'    => array(
					'label'    => esc_html__( 'th', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'th',
					'wrapper'  => '{{WRAPPER}} .woocommerce-table--order-downloads',
				),
				'td'    => array(
					'label'    => esc_html__( 'td', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'td',
					'wrapper'  => '{{WRAPPER}} .woocommerce-table--order-downloads',
				),
				'tbody' => array(
					'label'    => esc_html__( 'tbody', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tbody',
					'wrapper'  => '{{WRAPPER}} .woocommerce-table--order-downloads',
				),
			)
		);

		$this->register_group_styler(
			'button',
			__( ' Download Button', 'shop-press' ),
			array(
				'button' => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a.button',
					'wrapper'  => '{{WRAPPER}} .woocommerce-table--order-downloads .download-file',
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

		sp_load_builder_template( 'my-account/downloads' );
	}

	protected function content_template() {
		?>
		<div class="sp-downloads">

			<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
				<a class="woocommerce-Button button" href="#">Browse products</a>
				No downloads available yet.
			</div>

			<section class="woocommerce-order-downloads">
				<table class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive order_details">
					<thead>
						<tr>
							<th class="download-product"><span class="nobr">Product</span></th>
							<th class="download-remaining"><span class="nobr">Downloads remaining</span></th>
							<th class="download-expires"><span class="nobr">Expires</span></th>
							<th class="download-file"><span class="nobr">Download</span></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="download-product" data-title="Product">
								<a href="#">Product 1</a>
							</td>
							<td class="download-remaining" data-title="Downloads remaining">
								4
							</td>
							<td class="download-expires" data-title="Expires">
								<time datetime="2023-12-13" title="1702425600">December 13, 2023</time>
							</td>
							<td class="download-file" data-title="Download">
								<a href="#" class="woocommerce-MyAccount-downloads-file button alt">Download Here</a>
							</td>
						</tr>
						<tr>
							<td class="download-product" data-title="Product">
								<a href="#">Product 2</a>
							</td>
							<td class="download-remaining" data-title="Downloads remaining">
								4
							</td>
							<td class="download-expires" data-title="Expires">
								<time datetime="2023-12-13" title="1702425600">December 20, 2023</time>
							</td>
							<td class="download-file" data-title="Download">
								<a href="#" class="woocommerce-MyAccount-downloads-file button alt">Download Here</a>
							</td>
						</tr>
						<tr>
							<td class="download-product" data-title="Product">
								<a href="#">Product 3</a>
							</td>
							<td class="download-remaining" data-title="Downloads remaining">
								4
							</td>
							<td class="download-expires" data-title="Expires">
								<time datetime="2023-12-13" title="1702425600">December 18, 2023</time>
							</td>
							<td class="download-file" data-title="Download">
								<a href="#" class="woocommerce-MyAccount-downloads-file button alt">Download Here</a>
							</td>
						</tr>
					</tbody>
				</table>
			</section>
		</div>
		<?php
	}
}
