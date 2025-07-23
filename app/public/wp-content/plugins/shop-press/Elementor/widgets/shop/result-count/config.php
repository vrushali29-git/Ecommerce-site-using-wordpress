<?php
namespace ShopPress\Elementor\Widgets;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;
use ShopPress\Elementor\ControlsWidgets;

defined( 'ABSPATH' ) || exit;

class ResultCount extends ShopPressWidgets {

	public function get_name() {
		return 'sp-result-count';
	}

	public function get_title() {
		return __( 'Result Count', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-result-count';
	}

	public function get_categories() {
		return array( 'sp_woo_shop' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Styles', 'shop-press' ),
			array(
				'wrapper'     => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-result-count',
					'wrapper'  => '{{WRAPPER}}',
				),
				'result_text' => array(
					'label'    => esc_html__( 'Text', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-result-count',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);
	}

	protected function register_controls() {
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'shop/shop-result-count' );
		}
	}

	protected function content_template() {
		?>
		<div class="sp-result-count">
			<p class="woocommerce-result-count"><?php echo __( 'Showing all 12 results', 'shop-press' ) ?></p>
		</div>
		<?php
	}
}
