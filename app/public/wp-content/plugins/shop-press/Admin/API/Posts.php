<?php
/**
 * Posts.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\API;

defined( 'ABSPATH' ) || exit;

use ShopPress\Admin;

class Posts {
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
	 * Default term fields.
	 *
	 * @since 1.3.4
	 */
	private static $default_term_fields = array( 'name', 'slug', 'description', 'parent' );

	/**
	 * Default post fields.
	 *
	 * @since 1.3.4
	 */
	private static $default_post_fields = array( 'post_title', 'post_content' );

	/**
	 * Add a post.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function add_post( $request ) {
		$post_type = $request->get_param( 'post_type' );
		$name      = $request->get_param( 'name' );

		$post_id    = \ShopPress\Templates\Main::add_post( $post_type, $name );
		$item_array = array();

		if ( $post_id ) {

			$post = get_post( $post_id );

			$item_array = array(
				'id'       => $post->ID,
				'title'    => $post->post_title,
				'username' => $this->prepare_username( $post->post_author ),
				'date'     => $this->prepare_date( $post->post_date ),
				'link'     => $this->prepare_link( $post->ID ),
			);

			return new \WP_REST_Response(
				$item_array,
				200
			);
		}

		return $item_array;
	}

	/**
	 * Update post.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function update_post( $request ) {
		$post_id      = $request->get_param( 'id' );
		$updated_meta = $request->get_json_params();
		$post_type    = get_post_type( $post_id );

		if ( ! $post_id || ! is_array( $updated_meta ) ) {
			return;
		}

		/**
		 * Filters the default post fields.
		 *
		 * @since 1.4.3
		 *
		 * @param array  $default_post_fields
		 * @param string $post_type
		 */
		$default_post_fields = apply_filters( 'shoppress/api/post/default_post_fields', self::$default_post_fields, $post_type );

		$post_data = array();
		foreach ( $updated_meta as $key => $value ) {

			if ( in_array( $key, $default_post_fields ) ) {

				$value = ( is_array( $value ) && isset( $value['value'] ) ) ? $value['value'] : $value;

				$post_data[ $key ] = $value;
			} else {

				// update value if it was string
				if ( is_array( $value ) && isset( $value['url'] ) ) {
					$value = json_encode( $value );
				}

				update_post_meta( $post_id, $key, $value );
			}
		}

		if ( ! empty( $post_data ) ) {

			$post_data['ID'] = $post_id;
			wp_update_post( $post_data );
		}
	}

	/**
	 * Delete post.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function delete_post( $request ) {
		$post_id = $request->get_param( 'id' );

		$deleted_post = wp_delete_post( $post_id, false );

		return new \WP_REST_Response(
			$deleted_post->ID,
			200
		);
	}

	/**
	 * Prepare date format.
	 *
	 * @since 1.2.0
	 *
	 * @param string $date
	 */
	private function prepare_date( $date ) {

		if ( ! $date ) {
			return '';
		}

		$dateObj = new \DateTime( $date );

		$year  = $dateObj->format( 'Y' );
		$month = $dateObj->format( 'm' );
		$day   = $dateObj->format( 'd' );

		$formatted_date = "$day/$month/$year";

		return $formatted_date;
	}

	/**
	 * Prepare the link.
	 *
	 * @since 1.2.0
	 *
	 * @param int $post_id
	 */
	private function prepare_link( $post_id ) {
		$post_type = get_post_type( $post_id );

		return apply_filters( 'shoppress/api/get_posts/link', get_permalink( $post_id ), $post_type );
	}

	/**
	 * Prepare username.
	 *
	 * @since 1.2.0
	 *
	 * @param int $user_id
	 */
	private function prepare_username( $user_id ) {
		$user_data = get_userdata( $user_id );
		$username  = __( 'Guest', 'shop-press' );

		if ( $user_data ) {
			$username = $user_data->user_login;
		}

		return $username;
	}

	/**
	 * Prepare items data.
	 *
	 * @since 1.2.0
	 *
	 * @param object $items
	 * @param array  $meta_in_table
	 *
	 * @return array $prepare_items_data
	 */
	private function prepare_items_data( $items, $meta_in_table = array() ) {

		if ( ! is_array( $items ) || empty( $items ) ) {
			return array();
		}

		$prepare_items_data = array();

		foreach ( $items as $item ) {

			$item_id   = $item->ID ?? $item->term_id;
			$post_type = $item->post_type ?? null;
			$data_type = $post_type ? 'posts' : 'terms';

			$post_array = array(
				'id'       => $item_id ?? 'null',
				'title'    => $item->post_title ?? '',
				'username' => $this->prepare_username( $item->post_author ?? '' ),
				'date'     => $this->prepare_date( $item->post_date ),
				'link'     => $this->prepare_link( $item_id ),
			);

			$term_array = array(
				'id'    => $item_id ?? null,
				'title' => $item->name ?? '',
			);

			if ( ! empty( $meta_in_table ) ) {

				$meta_list = $this->prepare_meta_list( $item_id, $meta_in_table );

				foreach ( $meta_list as $key => $value ) {

					$post_array[ $key ] = $value;
				}
			}

			if ( 'posts' === $data_type ) {

				$prepare_items_data[] = apply_filters( 'shoppress/api/get_posts/prepare_data', $post_array, $item_id, $post_type, $item );
			} elseif ( 'terms' === $data_type ) {

				$prepare_items_data[] = apply_filters( 'shoppress/api/get_terms/prepare_data', $term_array, $item_id, $item );
			}
		}

		return $prepare_items_data;
	}

	/**
	 * Get post.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_post( $request ) {
		$post_id = $request->get_param( 'id' );

		if ( ! $post_id ) {
			return;
		}

		$post_type = get_post_type( $post_id );

		$post = get_post( $post_id );

		$meta_keys = get_post_custom_keys( $post_id );

		// Ignore these items
		$default_keys = array( '_edit_last', 'custom_type', '_edit_lock' );

		/**
		 * Filters the default post keys.
		 *
		 * @since 1.4.3
		 *
		 * @param array  $default_keys
		 * @param string $post_type
		 */
		$default_post_keys = apply_filters( 'shoppress/api/get_post/default_post_keys', $default_keys, $post_type );

		$keys_values = array();
		if ( is_array( $meta_keys ) ) {

			foreach ( $meta_keys as $key ) {

				if ( in_array( $key, $default_post_keys ) ) {
					continue;
				}

				$meta_value         = get_post_custom_values( $key, $post_id )[0] ?? '';
				$meta_value_json    = json_decode( $meta_value, true );
				$unserialized_value = maybe_unserialize( $meta_value );

				if ( $meta_value_json !== null ) {
					$unserialized_value = $meta_value_json;
				}

				$keys_values[ $key ] = $unserialized_value !== false ? $unserialized_value : $meta_value;
			}
		}

		/**
		 * Filters the default post fields.
		 *
		 * @since 1.4.3
		 *
		 * @param array  $default_post_fields
		 * @param string $post_type
		 */
		$default_post_fields = apply_filters( 'shoppress/api/post/default_post_fields', self::$default_post_fields, $post_type );

		foreach ( $post as $key => $value ) {

			if ( in_array( $key, $default_post_fields ) ) {

				$keys_values[ $key ] = $value ?? '';
			}
		}

		if ( ! is_wp_error( $post ) ) {

			$prepared_data = $this->prepare_item_data( $post );

			$post_data = array(
				'post'        => $prepared_data,
				'meta'        => $keys_values,
				'meta_fields' => array(),
			);

			$response = apply_filters( 'shoppress/api/get_post', $post_data, $post_id, $post_type );

			return new \WP_REST_Response(
				$response,
				200
			);
		} else {

			$error_message = $post->get_error_message();

			return new \WP_REST_Response(
				$error_message,
				500
			);
		}
	}

	/**
	 * Get posts.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_posts( $request ) {
		$post_type     = $request->get_param( 'post_type' );
		$page          = $request->get_param( 'page' );
		$meta_in_table = $request->get_param( 'meta_in_table' );

		if ( ! $post_type ) {
			return;
		}

		$posts_per_page = apply_filters( 'shoppress/api/get_posts/per_page', 10, $post_type );

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'paged'          => $page ?? 1,
		);

		$query = new \WP_Query( $args );

		if ( ! is_wp_error( $query ) ) {

			$max_num_pages      = $query->max_num_pages;
			$prepare_items_data = $this->prepare_items_data( $query->get_posts(), $meta_in_table );

			$response = rest_ensure_response(
				new \WP_REST_Response(
					$prepare_items_data,
					200
				)
			);

			$response->header( 'total_posts', (int) $max_num_pages );

			return $response;
		} else {

			$error_message = $posts->get_error_message();

			return new \WP_REST_Response(
				$error_message,
				500
			);
		}
	}

	/**
	 * Get terms.
	 *
	 * @since 1.3.1
	 *
	 * @param object $request
	 */
	public function get_terms( $request ) {
		$taxonomy      = $request->get_param( 'taxonomy' );
		$page          = $request->get_param( 'page' );
		$meta_in_table = $request->get_param( 'meta_in_table' );

		if ( ! $taxonomy ) {
			return;
		}

		$number = apply_filters( 'shoppress/api/get_terms/number', 10, $taxonomy );

		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'number'     => $number,
			'offset'     => ( $page - 1 ) * $number,
		);

		$terms = get_terms( $args );

		if ( ! is_wp_error( $terms ) ) {

			$total_terms        = ceil( wp_count_terms( $taxonomy ) / $number );
			$prepare_items_data = $this->prepare_items_data( $terms, $meta_in_table );

			$response = rest_ensure_response(
				new \WP_REST_Response(
					$prepare_items_data,
					200
				)
			);

			$response->header( 'total_terms', (int) $total_terms );

			return $response;
		} else {

			$error_message = $terms->get_error_message();

			return new \WP_REST_Response(
				$error_message,
				500
			);
		}
	}

	/**
	 * Add a term.
	 *
	 * @since 1.3.1
	 *
	 * @param object $request
	 */
	public function add_term( $request ) {
		$taxonomy = $request->get_param( 'taxonomy' );
		$name     = $request->get_param( 'name' );

		if ( ! $name || ! $taxonomy ) {
			return;
		}

		$result     = wp_insert_term( $name, $taxonomy );
		$term_array = array();

		if ( ! is_wp_error( $result ) ) {

			$term = get_term_by( 'id', $result['term_id'], $taxonomy );

			$term_array = array(
				'id'    => $result['term_id'],
				'title' => $term->name,
			);

			return new \WP_REST_Response(
				$term_array,
				200
			);
		} else {

			$error_message = $result->get_error_message();

			return new \WP_REST_Response(
				$error_message,
				500
			);
		}
	}

	/**
	 * Get term.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_term( $request ) {
		$term_id  = $request->get_param( 'id' );
		$taxonomy = $request->get_param( 'taxonomy' );

		if ( ! $term_id || ! $taxonomy ) {
			return;
		}

		$term = get_term_by( 'term_id', $term_id, $taxonomy );

		$prepared_term = $this->prepare_item_data( $term );
		$prepared_data = $this->prepare_meta_data( $term );

		if ( ! is_wp_error( $term ) ) {

			$term_data = array(
				'term'             => $prepared_term,
				'term_meta'        => $prepared_data,
				'term_meta_fields' => array(),
			);

			$response = apply_filters( 'shoppress/api/get_term', $term_data, $term_id, $taxonomy );

			return new \WP_REST_Response(
				$response,
				200
			);
		} else {

			$error_message = $term->get_error_message();

			return new \WP_REST_Response(
				$error_message,
				500
			);
		}
	}

	/**
	 * Delete a term.
	 *
	 * @since 1.3.1
	 *
	 * @param object $request
	 */
	public function delete_term( $request ) {
		$term_id  = $request->get_param( 'id' );
		$taxonomy = $request->get_param( 'taxonomy' );

		$result = wp_delete_term( $term_id, $taxonomy );

		if ( ! is_wp_error( $result ) ) {

			return new \WP_REST_Response(
				(int) $term_id,
				200
			);
		} else {

			$error_message = $result->get_error_message();

			return new \WP_REST_Response(
				$error_message,
				500
			);
		}
	}

	/**
	 * Update term.
	 *
	 * @since 1.3.1
	 *
	 * @param object $request
	 */
	public function update_term( $request ) {
		$term_id      = $request->get_param( 'id' );
		$updated_data = $request->get_json_params();
		$taxonomy     = $updated_data['term'] ?? '';

		if ( ! $term_id || ! is_array( $updated_data ) && empty( $taxonomy ) ) {
			return;
		}

		$term_data = array();
		foreach ( $updated_data as $key => $value ) {

			if ( in_array( $key, self::$default_term_fields ) ) {

				$value = ( is_array( $value ) && isset( $value['value'] ) ) ? $value['value'] : $value;

				$term_data[ $key ] = sanitize_text_field( $value );
			} elseif ( $key !== 'term' ) {

				// update value if it was string
				if ( is_array( $value ) && isset( $value['url'] ) ) {
					$value = json_encode( $value );
				}

				update_term_meta( $term_id, $key, $value );
			}
		}

		if ( ! empty( $term_data ) ) {

			wp_update_term( $term_id, $taxonomy, $term_data );
		}
	}

	/**
	 * Prepare meta list to display in the table.
	 *
	 * @since 1.2.0
	 *
	 * @param int   $user_id
	 * @param array $meta_in_table
	 */
	private function prepare_meta_list( $post_id, $meta_in_table ) {

		if ( ! $post_id || ! is_array( $meta_in_table ) ) {
			return array();
		}

		$meta_value = array();

		foreach ( $meta_in_table as $key => $value ) {

			$post_meta          = get_post_meta( $post_id, $key );
			$meta_value[ $key ] = $post_meta[0] ?? '';
		}

		return $meta_value;
	}

	/**
	 * Prepare post data.
	 *
	 * @since 1.2.0
	 *
	 * @param array $post
	 *
	 * @return array
	 */
	private function prepare_item_data( $item ) {

		if ( ! $item ) {
			return array();
		}

		$prepared_item_data = array(
			'id'    => $item->ID ?? $item->term_id,
			'title' => $item->post_title ?? $item->name,
		);

		return $prepared_item_data;
	}

	/**
	 * Prepare term data
	 *
	 * @since 1.3.1
	 *
	 * @param array $term
	 *
	 * @return array
	 */
	private function prepare_meta_data( $term ) {

		if ( ! $term ) {
			return array();
		}

		$meta_keys = get_term_meta( $term->term_id );

		$keys_values = array();
		if ( is_array( $meta_keys ) ) {

			foreach ( $meta_keys as $key => $value ) {

				$unserialized_value = maybe_unserialize( $value[0] ?? '' );
				$meta_value_json    = json_decode( $value[0] ?? '', true );

				if ( $meta_value_json !== null ) {
					$unserialized_value = $meta_value_json;
				}

				$keys_values[ $key ] = $unserialized_value !== false ? $unserialized_value : $value;
			}
		}

		foreach ( $term as $key => $value ) {

			if ( in_array( $key, self::$default_term_fields ) ) {

				$keys_values[ $key ] = $value ?? '';
			}
		}

		return $keys_values;
	}

	/**
	 * Custom list filter.
	 *
	 * @since 1.4.0
	 *
	 * @param object $request
	 */
	public function custom_list_filter( $request ) {
		$items_group   = $request->get_param( 'items_group' );
		$page          = $request->get_param( 'page' );
		$meta_in_table = $request->get_param( 'meta_in_table' );

		if ( ! $items_group ) {
			return;
		}

		$items_data = array(
			'total_pages' => 1,
			'items'       => array(),
		);

		$items_per_page = apply_filters( 'shoppress/api/get_custom_list/per_page', 10, $items_group );
		$items_data     = apply_filters( 'shoppress/api/get_custom_list/data', $items_data, $items_group, $page, $items_per_page, $meta_in_table );

		$max_num_pages      = $items_data['total_pages'] ?? 1;
		$prepare_items_data = $items_data['items'] ?? array();

		$response = rest_ensure_response(
			new \WP_REST_Response(
				$prepare_items_data,
				200
			)
		);

		$response->header( 'total_pages', (int) $max_num_pages );

		return $response;
	}
}
