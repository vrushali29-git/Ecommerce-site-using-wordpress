<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class SizeChart extends ShopPressWidgets {

	public function get_name() {
		return 'sp-chart-size';
	}

	public function get_title() {
		return __( 'Product Size Chart', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-size-chart';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function get_script_depends() {
		return array( 'sp-size-chart' );
	}

	public function get_style_depends() {
		return array( 'sp-size-chart' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Button', 'shop-press' ),
			array(
				'button' => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-size-chart',
					'wrapper'  => '{{WRAPPER}}',
				),
				'icon'   => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-size-chart',
				),
			),
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'label',
			array(
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Button Text', 'shop-press' ),
				'default' => __( 'Size Chart', 'shop-press' ),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'                  => __( 'Icon', 'shop-press' ),
				'type'                   => Controls_Manager::ICONS,
				'exclude_inline_options' => array( 'svg' ),
			)
		);

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'label' => $settings['label'],
			'icon'  => $settings['icon'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-size-chart', $args );
		}
	}

	protected function content_template() {
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true, 'class': "sp-icon" }, 'i' , 'object' );
		var render_icon;

		#>
		<button class="sp-size-chart has-icon">
			<# if ( 'svg' === settings.icon.library ) {#>
				<i class="sp-icon">
					{{{ iconHTML.value }}}
				</i>
			<# } else { #>
				{{{ iconHTML.value }}}
			<# } #>

			{{{ settings.label }}}
		</button>
		<?php
	}
}
