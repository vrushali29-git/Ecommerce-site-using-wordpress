<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class Wishlist extends ShopPressWidgets {

	public function get_name() {
		return 'sp-wishlist';
	}

	public function get_title() {
		return __( 'Wishlist Button', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-wishlist';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function get_script_depends() {
		return array( 'sp-wishlist' );
	}

	public function get_style_depends() {
		return array( 'sp-wishlist' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist',
					'wrapper'  => '{{WRAPPER}}',
				),
				'button'  => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-button',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist',
				),
			)
		);

		$this->register_group_styler(
			'label',
			__( 'Label', 'shop-press' ),
			array(
				'label'        => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-label',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-button',
				),
				'active_label' => array(
					'label'    => esc_html__( 'Active Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-label',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist[data-status="yes"] .sp-wishlist-button',
				),
			)
		);

		$this->register_group_styler(
			'icon',
			__( 'Icon', 'shop-press' ),
			array(
				'icon_wrapper' => array(
					'label'    => esc_html__( 'Icon Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-wishlist-icon',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-button',
				),
				'icon'         => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist-button .sp-wishlist-icon',
				),
				'active_icon'  => array(
					'label'    => esc_html__( 'Active Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-wishlist[data-status="yes"] .sp-wishlist-button .sp-wishlist-icon',
				),
			)
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Settings', 'shop-press' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'type',
			array(
				'label'   => __( 'Type', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon'       => __( 'Icon', 'shop-press' ),
					'label'      => __( 'Label', 'shop-press' ),
					'icon-label' => __( 'Icon + Label', 'shop-press' ),
				),
				'default' => 'icon-label',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'                  => __( 'Icon', 'shop-press' ),
				'type'                   => Controls_Manager::ICONS,
				'exclude_inline_options' => array( 'svg' ),
				'condition'              => array(
					'type!' => 'label',
				),
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
			'type' => $settings['type'],
			'icon' => $settings['icon'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-wishlist', $args );
		}
	}

	protected function content_template() {
		$label = sp_get_module_settings( 'wishlist_general_settings', 'add_label' );
		?>

		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		#>
		<div class="sp-wishlist sp-wishlist-button-container">
			<div class="sp-wishlist-button">
				<# if ( 'label' === settings.type ) { #>
					<span class="sp-wishlist-label"><?php echo esc_html( $label ); ?></span>
				<# } #>

				<# if ( 'icon' === settings.type ) { #>
					<span class="sp-wishlist-icon">
						<# if ( iconHTML.value ) { #>
							<# if ( 'svg' === settings.icon.library ) {#>
								<i class="sp-icon">
									{{{ iconHTML.value }}}
								</i>
							<# } else { #>
								{{{ iconHTML.value }}}
							<# } #>
						<# } else { #>
							<?php echo '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'wishlist' ), sp_allowd_svg_tags() ) . '</i>'; ?>
						<# } #>
						</span>
				<# } #>

				<# if ( 'icon-label' === settings.type ) { #>
					<span class="sp-wishlist-icon">
						<# if ( iconHTML.value ) { #>
							<# if ( 'svg' === settings.icon.library ) {#>
								<i class="sp-icon">
									{{{ iconHTML.value }}}
								</i>
							<# } else { #>
								{{{ iconHTML.value }}}
							<# } #>
						<# } else { #>
							<?php echo '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'wishlist' ), sp_allowd_svg_tags() ) . '</i>'; ?>
						<# } #>
						</span>
					<span class="sp-wishlist-label"><?php echo esc_html( $label ); ?></span>
				<# } #>
			</div>
		</div>
		<?php
	}
}
