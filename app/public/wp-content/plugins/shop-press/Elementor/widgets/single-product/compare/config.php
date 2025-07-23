<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

class Compare extends ShopPressWidgets {

	public function get_name() {
		return 'sp-single-compare';
	}

	public function get_title() {
		return __( 'Compare Button', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-compare';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function get_script_depends() {
		return array( 'sp-compare' );
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-compare', 'sp-wishlist', 'sp-compare-rtl', 'sp-wishlist-rtl' );
		} else {
			return array( 'sp-compare', 'sp-wishlist' );
		}
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-single-compare',
					'wrapper'  => '{{WRAPPER}}',
				),
				'button'  => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare-button',
					'wrapper'  => '{{WRAPPER}} .sp-single-compare',
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
					'selector' => '.sp-compare-label',
					'wrapper'  => '{{WRAPPER}} .sp-compare-button',
				),
				'active_label' => array(
					'label'    => esc_html__( 'Active Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare-label',
					'wrapper'  => '{{WRAPPER}} .sp-single-compare[data-status="yes"] .sp-compare-button',
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
					'selector' => '.sp-compare-icon',
					'wrapper'  => '{{WRAPPER}} .sp-compare-button',
				),
				'icon'         => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-compare-button .sp-compare-icon',
				),
				'active_icon'  => array(
					'label'    => esc_html__( 'Active Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'i.sp-icon',
					'wrapper'  => '{{WRAPPER}} .sp-single-compare[data-status="yes"] .sp-compare-button .sp-compare-icon',
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

			if ( $this->is_editor() ) {
				?>
				<script>
					(function($){
						shoppress_compare_init($);
					})(jQuery);
				</script>
				<?php
			}
			sp_load_builder_template( 'single-product/product-compare', $args );
		}
	}

	protected function content_template() {
		$label = sp_get_module_settings( 'compare', 'add_label' );
		?>

		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		#>
		<div class="sp-single-compare sp-compare-button-wrapper">
			<div class="sp-compare-button">
				<# if ( 'label' === settings.type ) { #>
					<span class="sp-compare-label"><?php echo esc_html( $label ); ?></span>
				<# } #>

				<# if ( 'icon' === settings.type ) { #>
					<span class="sp-compare-icon">
						<# if ( iconHTML.value ) { #>
							<# if ( 'svg' === settings.icon.library ) {#>
								<i class="sp-icon">
									{{{ iconHTML.value }}}
								</i>
							<# } else { #>
								{{{ iconHTML.value }}}
							<# } #>
						<# } else { #>
							<?php echo wp_kses( sp_get_svg_icon( 'compare' ), sp_allowd_svg_tags() ); ?>
						<# } #>
					</span>
				<# } #>

				<# if ( 'icon-label' === settings.type ) { #>
					<span class="sp-compare-icon">
						<# if ( iconHTML.value ) { #>
							<# if ( 'svg' === settings.icon.library ) {#>
								<i class="sp-icon">
									{{{ iconHTML.value }}}
								</i>
							<# } else { #>
								{{{ iconHTML.value }}}
							<# } #>
						<# } else { #>
							<?php echo wp_kses( sp_get_svg_icon( 'compare' ), sp_allowd_svg_tags() ); ?>
						<# } #>
					</span>
					<span class="sp-compare-label"><?php echo esc_html( $label ); ?></span>
				<# } #>
			</div>
		</div>
		<?php
	}
}
