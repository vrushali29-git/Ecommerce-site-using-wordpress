<?php
/**
 * Settings.
 *
 * @package ShopPress
 */

namespace ShopPress;

defined( 'ABSPATH' ) || exit;

class Settings {
	/**
	 * Settings
	 *
	 * @var array
	 */
	private static $settings = array();

	/**
	 * Get settings.
	 *
	 * @since 1.0.0
	 */
	public static function get_settings() {

		if ( empty( static::$settings ) ) {

			static::$settings = get_option( 'sp_admin' );

			if ( ! is_array( static::$settings ) ) {
				$settings = json_decode( static::$settings, true );

				static::$settings = $settings;
			}
		}

		return static::$settings;
	}

	/**
	 * Update settings.
	 *
	 * @since 1.2.0
	 */
	public static function update_settings( $settings ) {

		static::$settings = $settings;

		update_option( 'sp_admin', json_encode( $settings ) );
	}

	/**
	 * Returns the component settings
	 *
	 * @param string $template_id
	 * @param string $setting_key
	 * @param mixed  $default
	 *
	 * @since 1.2.0
	 *
	 * @return mixed
	 */
	public static function get_template_settings( $template_id, $setting_key = null, $default = '' ) {
		$settings = static::get_settings();

		if ( ! is_null( $setting_key ) ) {

			return $settings['templates'][ $template_id ][ $setting_key ] ?? $default;
		}

		return $settings['templates'][ $template_id ] ?? $default;
	}

	/**
	 * Returns the module settings
	 *
	 * @param string $module_id
	 * @param string $setting_key
	 * @param mixed  $default
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_module_settings( $module_id, $setting_key = null, $default = '' ) {
		$settings = static::get_settings();

		if ( ! is_null( $setting_key ) ) {

			return $settings['modules'][ $module_id ][ $setting_key ] ?? $default;
		}

		return $settings['modules'][ $module_id ] ?? $default;
	}

	/**
	 * Check the component status
	 *
	 * @param string $template_id
	 *
	 * @since 1.2.0
	 *
	 * @return boolean
	 */
	public static function is_template_active( $template_id ) {
		$settings = static::get_template_settings( $template_id );

		return (bool) ( $settings['status'] ?? false );
	}

	/**
	 * Check the module status
	 *
	 * @param string $module_id
	 *
	 * @since 1.2.0
	 *
	 * @return boolean
	 */
	public static function is_module_active( $module_id ) {
		$settings = static::get_module_settings( $module_id );

		return (bool) ( $settings['status'] ?? false );
	}
}
