<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

class FlashSalesCountdown extends ShopPressWidgets {

	public function get_name() {
		return 'sp-single-flash-sale-countdown';
	}

	public function get_title() {
		return __( 'Flash Sale Countdown', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-flash-sales';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'shop-press' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_title',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label'        => __( 'Show Title', 'shop-press' ),
				'label_on'     => __( 'Show', 'shop-press' ),
				'label_off'    => __( 'Hide', 'shop-press' ),
			)
		);

		$this->end_controls_section();

		$this->register_group_styler(
			'wrapper',
			__( 'General', 'shop-press' ),
			array(
				'wrapper'       => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'selector' => '.fs-countdown-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
				'timer_title'   => array(
					'label'     => esc_html__( 'Timer Title', 'shop-press' ),
					'selector'  => '.fs-countdown-title',
					'wrapper'   => '{{WRAPPER}}',
					'condition' => array(
						'show_title' => 'yes',
					),
				),
				'items_wrapper' => array(
					'label'    => esc_html__( 'Item Wrapper', 'shop-press' ),
					'selector' => '.fs-countdown ul li',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item_number'   => array(
					'label'    => esc_html__( 'Numbers', 'shop-press' ),
					'selector' => '.fs-countdown ul li div',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item_label'    => array(
					'label'    => esc_html__( 'Labels', 'shop-press' ),
					'selector' => '.fs-countdown ul li span',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		do_action( 'shoppress/widget/before_render', $settings );

		$args = array(
			'show_title' => $settings['show_title'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/flash-sale-countdown', $args );
		}
	}

	protected function content_template() {

		$date = date( 'Y-m-d', strtotime( '+7 days' ) );
		?>
		<div class="fs-countdown-wrapper">
			<# if ( 'yes' === settings.show_title ) { #>
				<h6 class="fs-countdown-title">Timer Title</h6>
			<# } #>
			<div class="fs-countdown" data-timeend="<?php echo esc_attr( $date ); ?>">
				<ul>
					<li class="fc-countdown-days">
						<div>40</div><span>Days</span>
					</li>
					<li class="fc-countdown-hours">
						<div>11</div><span>Hours</span>
					</li>
					<li class="fc-countdown-minutes">
						<div>6</div><span>Minutes</span>
					</li>
					<li class="fc-countdown-seconds">
						<div>7</div><span>Seconds</span>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}
}



