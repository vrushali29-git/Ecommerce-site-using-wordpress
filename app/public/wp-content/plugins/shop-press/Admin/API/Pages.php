<?php
/**
 * Pages.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\API;

defined( 'ABSPATH' ) || exit;

use ShopPress\Admin;

class Pages {
	/**
	 * Instance of this class.
	 *
	 * @since  1.2.0
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.2.0
	 *
	 * @return  object
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add a page.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function add_page( $request ) {
		$post_type = $request->get_param( 'type' );
		$component = $request->get_param( 'component' );
		$name      = $request->get_param( 'name' );
		$builder   = $request->get_param( 'builder' );

		$custom_type_meta = sanitize_text_field( $component );
		$builder_meta     = sanitize_text_field( $builder );
		$page_id          = \ShopPress\Templates\Main::add_post( $post_type, $name );
		$page_array       = array();

		if ( $page_id ) {

			update_post_meta( $page_id, 'custom_type', $custom_type_meta );
			update_post_meta( $page_id, 'sp_builder_type', $builder_meta );

			$page = get_post( $page_id );

			$page_array = array(
				'page_id'      => $page->ID,
				'page_title'   => $page->post_title,
				'builder_type' => sp_get_builder_type( $page->ID ),
			);

			return new \WP_REST_Response(
				$page_array,
				200
			);
		}

		return $page_array;
	}

	/**
	 * Delete a page.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function delete_page( $request ) {
		$page_id = $request->get_param( 'ID' );

		$deleted_page = wp_delete_post( $page_id, false );

		return new \WP_REST_Response(
			$deleted_page->ID,
			200
		);
	}

	/**
	 * Prepare pages data.
	 *
	 * @since 1.2.0
	 *
	 * @param object $pages
	 */
	private function prepare_pages_data( $pages ) {

		if ( ! is_array( $pages ) || empty( $pages ) ) {
			return array();
		}

		$prepare_pages_data = array();

		foreach ( $pages as $page ) {

			$page_array = array(
				'page_id'      => $page->ID,
				'page_title'   => $page->post_title,
				'builder_type' => sp_get_builder_type( $page->ID ),
			);

			$prepare_pages_data[] = $page_array;
		}

		return $prepare_pages_data;
	}

	/**
	 * Get pages.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_pages( $request ) {
		$post_type = $request->get_param( 'type' );
		$component = $request->get_param( 'component' );

		if ( ! $post_type || ! $component ) {
			return;
		}

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => 99,
		);

		if ( 'shoppress_loop' !== $post_type ) {

			$args['meta_query']['custom_type'] = array(
				'key'     => 'custom_type',
				'value'   => $component,
				'compare' => '=',
			);
		}

		$pages = get_posts( $args );

		if ( ! is_wp_error( $pages ) ) {

			$prepared_data = $this->prepare_pages_data( $pages );

			return new \WP_REST_Response(
				$prepared_data,
				200
			);
		} else {

			$error_message = $pages->get_error_message();

			return new \WP_REST_Response(
				$error_message,
				500
			);
		}
	}
}
