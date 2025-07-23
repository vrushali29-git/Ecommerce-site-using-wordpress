<?php
/**
 * Get Fields.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\API;

defined( 'ABSPATH' ) || exit;

use ShopPress\Admin\PagesFields;

class GetFields {
	/**
	 * Instance of this class.
	 *
	 * @since  1.0.0
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.0.0
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
	 * Get settings fields.
	 *
	 * @since 1.2.0
	 *
	 * @param object $request
	 */
	public function get_fields( $request ) {
		$type     = $request->get_param( 'type' );
		$template = $request->get_param( 'template' );
		$module   = $request->get_param( 'module' );
		$page     = $request->get_param( 'page' );
		$parent   = $request->get_param( 'parent' );
		$setting  = $request->get_param( 'setting' );

		if ( $type === 'features' && isset( $type ) ) {

			$templates = PagesFields\Templates::fields();
			$modules   = PagesFields\Modules::fields();

			return array(
				'templates' => $templates,
				'modules'   => $modules,
			);
		}

		if ( $type === 'template' && isset( $template ) ) {
			$class_name     = 'ShopPress\\Admin\\SettingsFields\\Templates';
			$class_name_pro = 'ShopPressPro\\Admin\\SettingsFields\\Templates';
			$method_name    = $template;
		} elseif ( $type === 'module' && isset( $module ) ) {
			$class_name     = 'ShopPress\\Admin\\SettingsFields\\Modules';
			$class_name_pro = 'ShopPressPro\\Admin\\SettingsFields\\Modules';
			$method_name    = $module;
		} elseif ( isset( $parent ) ) {
			$class_name     = 'ShopPress\\Admin\\SettingsFields\\' . $parent;
			$class_name_pro = 'ShopPressPro\\Admin\\SettingsFields\\' . $parent;
			$method_name    = $setting;
		}

		$fields = array();
		if ( class_exists( $class_name_pro ) && method_exists( $class_name_pro, $method_name ) ) {
			$fields = call_user_func( array( $class_name_pro, $method_name ) );
		}

		if ( method_exists( $class_name, $method_name ) ) {
			$fields = call_user_func( array( $class_name, $method_name ) );
		}

		return apply_filters( "shoppress/settings_fields/{$type}/{$method_name}", $fields );
	}
}
