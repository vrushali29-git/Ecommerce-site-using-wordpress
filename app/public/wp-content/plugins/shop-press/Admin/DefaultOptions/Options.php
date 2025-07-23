<?php
/**
 * Default Options.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\DefaultOptions;

defined( 'ABSPATH' ) || exit;

class Options {

	public static function get_field_values( $fields ) {
		if ( ! is_array( $fields ) ) {
			return array();
		}

		$exclude_components = array(
			'heading',
			'pages',
			'builder',
			'divider',
			'link',
			'terms',
		);
		$values             = array();
		foreach ( $fields as $key => $field ) {

			$component = $field['component'] ?? false;
			if ( in_array( $component, $exclude_components ) ) {

				continue;
			} elseif ( 'tabs' == $component ) {

				foreach ( $field['tabs'] as $tab_fields ) {
					$values = array_merge(
						$values,
						static::get_field_values( $tab_fields['fields'] ?? array() )
					);
				}
				$values[ $key ] = $field['default'] ?? '';
			} elseif ( 'group_fields' == $component ) {

				$values = array_merge(
					$values,
					static::get_field_values( $field['fields'] ?? array() )
				);
			} else {
				$name            = $field['name'] ?? false;
				$values[ $name ] = $field['default'] ?? '';
			}
		}

		return $values;
	}

	public static function get_default_template_settings() {
		$SettingsFieldsClasses = array(
			\ShopPressPro\Admin\SettingsFields\Templates::class,
			\ShopPress\Admin\SettingsFields\Templates::class,
		);

		$default_options = array();
		foreach ( $SettingsFieldsClasses as $SettingsFieldsClass ) {

			if ( ! class_exists( $SettingsFieldsClass ) ) {
				continue;
			}

			$SettingsFieldsClassMethods = get_class_methods( $SettingsFieldsClass );
			foreach ( $SettingsFieldsClassMethods as $method ) {

				$options                              = $SettingsFieldsClass::{$method}();
				$default_options[ $method ]           = static::get_field_values( $options );
				$default_options[ $method ]['status'] = false;
			}
		}

		return $default_options;
	}

	public static function get_default_module_settings() {
		$SettingsFieldsClasses = array(
			\ShopPress\Admin\SettingsFields\Modules::class,
			\ShopPressPro\Admin\SettingsFields\Modules::class,
		);

		$default_options = array();
		foreach ( $SettingsFieldsClasses as $SettingsFieldsClass ) {

			if ( ! class_exists( $SettingsFieldsClass ) ) {
				continue;
			}

			$SettingsFieldsClassMethods = get_class_methods( $SettingsFieldsClass );
			foreach ( $SettingsFieldsClassMethods as $method ) {

				$options                              = $SettingsFieldsClass::{$method}();
				$default_options[ $method ]           = static::get_field_values( $options );
				$default_options[ $method ]['status'] = false;
			}
		}

		return $default_options;
	}

	/**
	 * Returns the default options for sp_admin.
	 *
	 * @since 1.2.0
	 *
	 * @return array $default_options
	 */
	public static function get_default_options() {
		$default_options = array(
			'templates' => static::get_default_template_settings(),
			'modules'   => static::get_default_module_settings(),
			'addons'    => array(),
			'general'   => array(),
		);

		$default_options = apply_filters( 'shoppress/default_options', $default_options );

		return $default_options;
	}
}
