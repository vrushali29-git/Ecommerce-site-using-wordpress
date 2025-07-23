<?php
/**
 * Post Type.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin;

defined( 'ABSPATH' ) || exit;

class PostType {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'woocommerce_pages' ) );
		add_action( 'init', array( __CLASS__, 'woocommerce_my_account_pages' ) );
		add_action( 'init', array( __CLASS__, 'woocommerce_products_loop' ) );
	}

	/**
	 * WooCommerce pages.
	 *
	 * @since  1.0.0
	 */
	public static function woocommerce_pages() {
		register_post_type(
			'shoppress_pages',
			array(
				'labels'              => array(
					'name'          => __( 'WooCommerce pages', 'shop-press' ),
					'singular_name' => __( 'WooCommerce pages', 'shop-press' ),
				),
				'public'              => true,
				'has_archive'         => true,
				'show_in_menu'        => false,
				'can_export'          => true,
				'rewrite'             => false,
				'show_in_nav_menus'   => false,
				'exclude_from_search' => true,
				'show_in_rest'        => true,
				'supports'            => array( 'editor', 'title', 'custom-fields' ),
			)
		);

		$args = array(
			'type'           => 'array',
			'single'         => true,
			'object_subtype' => 'shoppress_pages',
			'show_in_rest'   => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type' => 'array',
					),
				),
			),
		);

		register_meta( 'post', 'custom_type', $args );
	}

	/**
	 * WooCommerce my account pages.
	 *
	 * @since  1.0.0
	 */
	public static function woocommerce_my_account_pages() {
		register_post_type(
			'shoppress_myaccount',
			array(
				'labels'              => array(
					'name'          => __( 'WooCommerce my account pages', 'shop-press' ),
					'singular_name' => __( 'WooCommerce my account pages', 'shop-press' ),
				),
				'public'              => true,
				'has_archive'         => true,
				'show_in_menu'        => false,
				'can_export'          => true,
				'rewrite'             => false,
				'show_in_nav_menus'   => false,
				'exclude_from_search' => true,
				'show_in_rest'        => true,
				'supports'            => array( 'editor', 'title', 'custom-fields' ),
			)
		);

		$args = array(
			'type'           => 'array',
			'single'         => true,
			'object_subtype' => 'shoppress_myaccount',
			'show_in_rest'   => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type' => 'array',
					),
				),
			),
		);

		register_meta( 'post', 'custom_type', $args );
	}

	/**
	 * WooCommerce products loop.
	 *
	 * @since  1.0.0
	 */
	public static function woocommerce_products_loop() {
		register_post_type(
			'shoppress_loop',
			array(
				'labels'              => array(
					'name'          => __( 'WooCommerce products loop', 'shop-press' ),
					'singular_name' => __( 'WooCommerce products loop', 'shop-press' ),
				),
				'public'              => true,
				'has_archive'         => true,
				'show_in_menu'        => false,
				'can_export'          => true,
				'rewrite'             => false,
				'show_in_nav_menus'   => false,
				'exclude_from_search' => true,
				'show_in_rest'        => true,
				'supports'            => array( 'editor', 'title', 'custom-fields' ),
			)
		);

		$args = array(
			'type'           => 'array',
			'single'         => true,
			'object_subtype' => 'shoppress_loop',
			'show_in_rest'   => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type' => 'array',
					),
				),
			),
		);

		register_meta( 'post', 'custom_type', $args );
	}
}
