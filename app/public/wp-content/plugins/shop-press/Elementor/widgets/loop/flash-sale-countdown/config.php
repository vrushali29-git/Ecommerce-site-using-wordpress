<?php
namespace ShopPress\Elementor\Widgets\LoopBuilder;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;

class FlashSalesCountdown extends ShopPressWidgets {

	public function get_name() {
		return 'sp-item-flash-sale-countdown';
	}

	public function get_title() {
		return __( 'Flash Sale Countdown', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-loop-flash-sales';
	}

	public function get_categories() {
		return array( 'sp_woo_loop' );
	}

	protected function register_controls() {

		$this->register_group_styler(
			'wrapper',
			__( 'General', 'shop-press' ),
			array(
				'wrapper'       => array(
					'label'    => __( 'Wrapper', 'shop-press' ),
					'selector' => '.fs-countdown',
					'wrapper'  => '{{WRAPPER}}',
				),
				'items_wrapper' => array(
					'label'    => __( 'Item Wrapper', 'shop-press' ),
					'selector' => '.fs-countdown ul li',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item_number'   => array(
					'label'    => __( 'Numbers', 'shop-press' ),
					'selector' => '.fs-countdown ul li div',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item_label'    => array(
					'label'    => __( 'Labels', 'shop-press' ),
					'selector' => '.fs-countdown ul li span',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'loop/loop-flash-sale-countdown' );
		}
	}

	protected function content_template() {

		$date = date( 'Y-m-d', strtotime( '+7 days' ) );
		?>
		<div class="fs-countdown-wrapper">
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
