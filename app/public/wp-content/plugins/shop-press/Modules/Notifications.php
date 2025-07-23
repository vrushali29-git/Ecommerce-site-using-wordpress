<?php

/**
 * Notifications
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class Notifications {
	/**
	 * Init.
	 *
	 * @since  1.2.0
	 */
	public static function init() {

		if ( ! sp_get_module_settings( 'notifications', 'status' ) ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'notifications_post_type' ) );

		add_filter( 'query_vars', array( __CLASS__, 'add_notifications_query_vars' ) );
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'add_notifications_links_to_my_account' ) );
		add_action( 'init', array( __CLASS__, 'add_notifications_endpoint' ) );
		add_action( 'woocommerce_account_notifications_endpoint', array( __CLASS__, 'display_notifications_endpoint' ) );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 99, 99 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_wp_media' ) );

		add_action( 'woocommerce_new_order', array( __CLASS__, 'notification_new_order' ), 10, 1 );

		add_action( 'woocommerce_order_status_cancelled', array( __CLASS__, 'notification_order_status' ), 10, 1 );
		add_action( 'woocommerce_order_status_refunded', array( __CLASS__, 'notification_order_status' ), 10, 1 );
		add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'notification_order_status' ), 10, 1 );
		add_action( 'woocommerce_order_status_processing', array( __CLASS__, 'notification_order_status' ), 10, 1 );
		add_action( 'woocommerce_order_status_pending', array( __CLASS__, 'notification_order_status' ), 10, 1 );
		add_action( 'woocommerce_order_status_failed', array( __CLASS__, 'notification_order_status' ), 10, 1 );
		add_action( 'woocommerce_order_status_on-hold', array( __CLASS__, 'notification_order_status' ), 10, 1 );

		add_action( 'shoppress_notification_order_status', array( __CLASS__, 'add_comment_notification_after_order_completed' ), 10, 4 );

		// add_action( 'woocommerce_account_dashboard', array( __CLASS__, 'display_notifications_widget' ) );
	}

	/**
	 * Load WP Media
	 *
	 * @since 1.0.0
	 */
	public static function load_wp_media() {
		wp_enqueue_media();
	}

	/**
	 * enqueue scripts.
	 *
	 * @since  1.0.0
	 */
	public static function enqueue_scripts() {

		if ( is_account_page() ) {

			wp_enqueue_style( 'sp-my-account-notifications' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-my-account-notifications-rtl' );
			}

			wp_enqueue_script( 'sp-my-account-notifications' );
		}
	}

	/**
	 * Notifications post type.
	 *
	 * @since  1.1.3
	 */
	public static function notifications_post_type() {

		register_post_type(
			'shoppress_notif',
			array(
				'labels'            => array(
					'name'          => __( 'Notifications', 'shop-press' ),
					'singular_name' => __( 'Notifications', 'shop-press' ),
				),
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => false,
				'show_in_nav_menus' => false,
				'supports'          => array( 'editor', 'title' ),
			)
		);
	}

	/**
	 * Is reviews enabled
	 *
	 * @since 1.1.3
	 *
	 * @return boolean
	 */
	public static function is_reviews_enabled() {

		return 'yes' === get_option( 'woocommerce_enable_reviews' );
	}

	/**
	 * Is review rating enabled
	 *
	 * @since 1.1.3
	 *
	 * @return boolean
	 */
	public static function is_review_rating_enabled() {

		return 'yes' === get_option( 'woocommerce_enable_review_rating' );
	}

	/**
	 * Return Notifications
	 *
	 * @param  array $args
	 *
	 * @since 1.1.3
	 *
	 * @return array
	 */
	public static function get_notifications( $args = array() ) {

		$datetime_format = get_option( 'date_format' );

		$defaults = array(
			'post_type'      => 'shoppress_notif',
			'posts_per_page' => 12,
			'paged'          => 1,
			'for'            => get_current_user_id(),
		);

		$args = wp_parse_args( $args, $defaults );

		if ( isset( $args['for'] ) && $args['for'] && 'all' !== $args['for'] ) {

			$args['meta_query']['notification_for'] = array(
				'key'     => '_shopperss_notification_for',
				'value'   => $args['for'],
				'compare' => '=',
			);
		}

		$notifications    = array();
		$wp_notifications = new \WP_Query( $args );
		if ( $wp_notifications->have_posts() ) {
			while ( $wp_notifications->have_posts() ) {
				$wp_notifications->the_post();

				$notification_id   = get_the_ID();
				$notification_type = get_post_meta( $notification_id, '_shopperss_notification_type', true );

				$notification = array(
					'id'        => $notification_id,
					'title'     => get_the_title(),
					'content'   => get_the_content(),
					'type'      => $notification_type,
					'for'       => get_post_meta( $notification_id, '_shopperss_notification_for', true ),
					'date'      => date_i18n( $datetime_format, strtotime( get_the_date() ) ),
					'link_url'  => '',
					'link_text' => '',
				);

				switch ( $notification_type ) {
					case 'order_created':
					case 'order_status_changed':
						$order_id                 = get_post_meta( $notification_id, '_shoppress_order_id', true );
						$notification['order_id'] = $order_id;

						$notification['link_url']  = wc_get_endpoint_url( 'view-order', $order_id, wc_get_page_permalink( 'myaccount' ) );
						$notification['link_text'] = __( 'View order', 'shop-press' );

						break;
					case 'order_comment':
						$order_id                 = get_post_meta( $notification_id, '_shoppress_order_id', true );
						$notification['order_id'] = $order_id;

						$notification['link_url']  = "#shppress-add-review-{$order_id}";
						$notification['link_text'] = __( 'Add review', 'shop-press' );

						break;
				}

				$notifications[ $notification_id ] = $notification;
			}
		}

		wp_reset_postdata();
		wp_reset_query();

		$notifications = array(
			'notifications' => $notifications,
			'page_count'    => $wp_notifications->max_num_pages,
		);

		return $notifications;
	}

	/**
	 * Update Notification
	 *
	 * @param  array $args
	 *
	 * @since 1.1.3
	 *
	 * @return array
	 */
	public static function update_notification( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'id'      => 0,
				'title'   => '',
				'content' => '',
				'type'    => 'normal',
				'for'     => get_current_user_id(),
				'date'    => '',
			)
		);

		$post_args = array(
			'ID'           => $args['id'],
			'post_type'    => 'shoppress_notif',
			'post_status'  => 'publish',
			'post_title'   => $args['title'],
			'post_content' => $args['content'],
			'meta_input'   => $args['meta_input'] ?? array(),
		);

		if ( ! empty( $args['date'] ) ) {

			$post_args['post_date'] = $args['date'];
		}

		if ( $args['type'] ) {

			$post_args['meta_input']['_shopperss_notification_type'] = $args['type'];
		}

		if ( $args['for'] ) {

			$post_args['meta_input']['_shopperss_notification_for'] = $args['for'];
		}

		if ( $post_args['ID'] ) {

			$notification_id = wp_update_post( $post_args );
		} else {

			$notification_id = wp_insert_post( $post_args );
		}

		return $notification_id;
	}

	/**
	 * Add notifications query vars
	 *
	 * @param array $vars
	 *
	 * @since 1.1.3
	 *
	 * @return array
	 */
	public static function add_notifications_query_vars( $vars ) {

		$vars['notifications'] = 'notifications';

		return $vars;
	}

	/**
	 * Add notifications menu to woocommerce my account
	 *
	 * @param array $menu_links
	 *
	 * @since 1.1.3
	 *
	 * @return array
	 */
	public static function add_notifications_links_to_my_account( $menu_links ) {

		$menu_links = array_slice( $menu_links, 0, 2 )
					+ array( 'notifications' => __( 'Notifications', 'shop-press' ) )
					+ array_slice( $menu_links, 2 );

		return $menu_links;
	}

	/**
	 * Add notifications menu to woocommerce my account
	 *
	 * @param array $menu_links
	 *
	 * @since 1.1.3
	 *
	 * @return array
	 */
	public static function add_notifications_endpoint() {

		add_rewrite_endpoint( 'notifications', EP_PAGES );
	}


	/**
	 * Notifications endpoint content.
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public static function display_notifications_endpoint() {
		echo self::get_notifications_content();
	}

	/**
	 * Returns the notifications content.
	 *
	 * @since  1.2.0
	 */
	private static function get_notifications_content() {
		ob_start();

		$builder_id = sp_get_template_settings( 'my_account_notifications', 'page_builder' );

		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
		if ( $builder_id ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $builder_id );
		} else {
			require_once sp_get_template_path( 'notifications/notifications' );
		}
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Add notification after add new order
	 *
	 * @param  int $order_id
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public static function notification_new_order( $order_id ) {

		$order = wc_get_order( $order_id );

		$title   = sprintf(
			__( 'New order #%s ', 'shop-press' ), // TODO: get from settings
			$order_id
		);
		$message = sprintf(
			__( 'A new order has been created.', 'shop-press' ) // TODO: get from settings
		);

		$notification = array(
			'title'      => $title,
			'content'    => $message,
			'for'        => $order->get_customer_id(),
			'type'       => 'order_created',
			'meta_input' => array(
				'_shoppress_order_id' => $order_id,
			),
		);

		$notification_id = static::update_notification( $notification );

		do_action( 'shoppress_notification_new_order', $notification_id, $notification );
	}

	/**
	 * Add notification after change order status
	 *
	 * @param  int $order_id
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public static function notification_order_status( $order_id ) {

		$order_status = str_replace( 'woocommerce_order_status_', '', current_action() );

		$order          = wc_get_order( $order_id );
		$order_statuses = wc_get_order_statuses();

		$order_status_text = $order_statuses[ "wc-$order_status" ] ?? $order_status;

		$title   = sprintf(
			__( 'Order #%s', 'shop-press' ), // TODO: get from settings
			$order_id
		);
		$message = sprintf(
			__( 'Order status changed to %s.', 'shop-press' ), // TODO: get from settings
			$order_status_text
		);

		$notification = array(
			'title'      => $title,
			'content'    => $message,
			'for'        => $order->get_customer_id(),
			'type'       => 'order_status_changed',
			'meta_input' => array(
				'_shoppress_order_id'     => $order_id,
				'_shoppress_order_status' => $order_status,
			),
		);

		$notification_id = static::update_notification( $notification );

		do_action( 'shoppress_notification_order_status', $notification_id, $notification, $order_id, $order_status );
	}

	/**
	 * Add comment notification after change order status changed to order completed
	 *
	 * @param  int $order_id
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public static function add_comment_notification_after_order_completed( $notification_id, $notification, $order_id, $order_status ) {

		if ( 'completed' !== $order_status ) {

			return;
		}

		$order = wc_get_order( $order_id );

		$title   = sprintf(
			__( 'Order #%s', 'shop-press' ), // TODO: get from settings
			$order_id
		);
		$message = sprintf(
			__( 'Please add your comment about the purchased products.', 'shop-press' ), // TODO: get from settings
		);

		$notification = array(
			'title'      => $title,
			'content'    => $message,
			'for'        => $order->get_customer_id(),
			'type'       => 'order_comment',
			'date'       => date_i18n( 'Y-m-d H:i', strtotime( '+7 days' ) ), // TODO: add for 7 days last
			'meta_input' => array(
				'_shoppress_order_id'     => $order_id,
				'_shoppress_order_status' => $order_status,
			),
		);

		$notification_id = static::update_notification( $notification );

		do_action( 'shoppress_add_comment_notification_after_order_completed', $notification_id, $notification, $order_status );
	}

	/**
	 * Display last notifications
	 *
	 * @param  int $order_id
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public static function display_notifications_widget() {

		include sp_get_template_path( 'notifications/notifications-dashboard-widget' );
	}
}
