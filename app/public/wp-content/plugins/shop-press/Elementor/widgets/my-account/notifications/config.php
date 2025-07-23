<?php
namespace ShopPress\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;
use ShopPress\Modules;

class UserNotifications extends ShopPressWidgets {

	public function get_name() {
		return 'sp-dashboard-user-notifications';
	}

	public function get_title() {
		return __( 'User Notifications', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-user-notifications';
	}

	public function get_categories() {
		return array( 'sp_woo_dashboard' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper'      => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item-wrapper' => array(
					'label'    => esc_html__( 'Message item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'icon',
			__( 'Icon', 'shop-press' ),
			array(

				'item-icon-wrapper' => array(
					'label'    => esc_html__( 'Icon Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-icon',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item-icon'         => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-icon svg',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'content',
			__( 'Content', 'shop-press' ),
			array(
				'item-content-wrapper' => array(
					'label'    => esc_html__( 'Content Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content-wrap',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item-title'           => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-title',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item-content'         => array(
					'label'    => esc_html__( 'Content', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item-view-order-link' => array(
					'label'    => esc_html__( 'View Order Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content a',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'date',
			__( 'Date', 'shop-press' ),
			array(
				'item-date-wrapper' => array(
					'label'    => esc_html__( 'Date Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-date-wrap',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item-date'         => array(
					'label'    => esc_html__( 'Date', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-date-wrap .sp-notification-item-date',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'show-more',
			__( 'Show More Button', 'shop-press' ),
			array(
				'item-show-more-btn'      => array(
					'label'    => esc_html__( 'Show More Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content-show-more',
					'wrapper'  => '{{WRAPPER}}',
				),
				'item-show-more-btn-icon' => array(
					'label'    => esc_html__( 'Show More Button Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content-show-more svg',
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
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		require_once sp_get_template_path( 'notifications/notifications' );
	}
}
