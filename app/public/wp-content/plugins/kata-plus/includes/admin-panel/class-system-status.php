<?php
/**
 * System Status Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_System_Status' ) ) {
	class Kata_Plus_System_Status {
		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_System_Status
		 */
		public static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @return  object
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Get server operating system.
		 *
		 * @since 1.0.0
		 */
		public static function get_os() {
			return array(
				'value' => PHP_OS,
			);
		}

		/**
		 * Get server software.
		 *
		 * @since 1.0.0
		 */
		public static function get_software() {
			return array(
				'value' => $_SERVER['SERVER_SOFTWARE'],
			);
		}

		/**
		 * Get PHP version.
		 *
		 * @since 1.0.0
		 */
		public static function get_php_version() {
			return array(
				'value' => PHP_VERSION,
			);
		}

		/**
		 * Get PHP `max_input_vars`.
		 *
		 * @since 1.0.0
		 */
		public static function get_php_max_input_vars() {
			return array(
				'value' => ini_get( 'max_input_vars' ),
			);
		}

		/**
		 * Get PHP `post_max_size`.
		 *
		 * @since 1.0.0
		 */
		public static function get_php_post_max_size() {
			return array(
				'value' => ini_get( 'post_max_size' ),
			);
		}

		/**
		 * Get PHP `max_filesize`.
		 *
		 * @since 1.0.0
		 */
		public static function get_php_max_filesize() {
			return array(
				'value' => ini_get( 'upload_max_filesize' ),
			);
		}

		/**
		 * Get PHP `max_input_time`.
		 *
		 * @since 1.0.0
		 */
		public static function get_php_max_input_time() {
			return array(
				'value' => ini_get( 'max_input_time' ),
			);
		}

		/**
		 * Get PHP `max_execution_time`.
		 *
		 * @since 1.0.0
		 */
		public static function get_php_max_execution_time() {
			return array(
				'value' => ini_get( 'max_execution_time' ),
			);
		}

		/**
		 * Get ZIP installed.
		 *
		 * @since 1.0.0
		 */
		public static function get_zip_installed() {
			$zip_installed = extension_loaded( 'zip' );

			return array(
				'value'   => $zip_installed ? 'Yes' : 'No',
				'warning' => ! $zip_installed,
			);
		}

		/**
		 * Get MySQL version.
		 *
		 * @since 1.0.0
		 */
		public static function get_mysql_version() {
			global $wpdb;

			$db_server_version = $wpdb->get_results( "SHOW VARIABLES WHERE `Variable_name` IN ( 'version_comment', 'innodb_version' )", OBJECT_K );

			return array(
				'value' => $db_server_version['version_comment']->Value . ' v' . $db_server_version['innodb_version']->Value,
			);
		}

		/**
		 * Get Memory limit.
		 *
		 * @since 1.0.0
		 */
		public static function get_memory_limit() {
			return array(
				'value' => WP_MEMORY_LIMIT,
			);
		}

		/**
		 * Get Memory limit.
		 *
		 * @since 1.0.0
		 */
		public static function get_server_memory_limit() {
			return array(
				'value' => ini_get( 'memory_limit' ),
			);
		}

		/**
		 * Get PHP `max_upload_size`.
		 *
		 * @since 1.0.0
		 */
		public static function get_max_upload_size() {
			return array(
				'value' => size_format( wp_max_upload_size() ),
			);
		}
	}

	Kata_Plus_System_Status::get_instance();
}
