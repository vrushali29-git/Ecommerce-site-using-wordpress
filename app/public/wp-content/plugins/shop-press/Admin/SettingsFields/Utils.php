<?php
/**
 * Fields Utils.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\SettingsFields;

defined( 'ABSPATH' ) || exit;

class Utils {
	/**
	 * Get builder fields.
	 *
	 * @since 1.2.0
	 *
	 * @param string $title
	 * @param string $type
	 * @param bool   $option
	 *
	 * @return array $builder_fields
	 */
	public static function builder_fields( $title, $type, $option = true ) {
		$builder_fields = array(
			array(
				'title'     => $title,
				'component' => 'heading',
			),
			array(
				'type'      => $type,
				'component' => 'pages',
				'option'    => $option,
			),
		);

		return $builder_fields;
	}

	/**
	 * Get user roles.
	 *
	 * @since 1.2.0
	 *
	 * @return array $option_roles
	 */
	public static function get_option_roles() {
		$option_roles = array();
		$roles        = wp_roles()->role_names;
		foreach ( $roles as $role => $name ) {

			$option_roles[] = array(
				'value' => $role,
				'label' => $name,
			);
		}

		return $option_roles;
	}

	/**
	 * Get Woocommerce Attributes.
	 *
	 * @param string $message
	 * @param array  $fields
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public static function get_woocommerce_attributes() {
		$list = array();

		if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
			return $list;
		}

		$taxonomies = wc_get_attribute_taxonomies();
		foreach ( $taxonomies as $key => $taxonomy ) {
			$list[] = array(
				'label' => $taxonomy->attribute_label,
				'value' => $taxonomy->attribute_name,
			);
		}

		return $list;
	}

	/**
	 * Add Notice to fields.
	 *
	 * @param string $message
	 * @param array  $fields
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function add_notice_to_fields( $message, $type, $fields ) {
		$fields = array_merge(
			array_slice( $fields, 0, 1 ),
			array(
				array(
					'description' => $message,
					'component'   => 'notice',
					'notice_type' => $type,
				),
			),
			array_slice( $fields, 1 )
		);

		return $fields;
	}

	/**
	 * Get the terms of a taxonomy.
	 *
	 * @param string $taxonomy
	 *
	 * @since 1.3.1
	 *
	 * @return array
	 */
	public static function get_terms( $taxonomy ) {

		if ( ! $taxonomy ) {
			return array();
		}

		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		);

		$terms = get_terms( $args );

		$terms_list = array();
		if ( ! is_wp_error( $terms ) ) {

			foreach ( $terms as $key => $value ) {

				$terms_list[] = array(
					'value' => $value->term_id,
					'label' => $value->name,
				);
			}
		}

		return $terms_list;
	}
}
