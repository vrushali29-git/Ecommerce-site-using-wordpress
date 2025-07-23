<?php
/**
 * Autoloader Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Autoloader' ) ) {
	class Kata_Plus_Autoloader {
		/**
		 * Array of files.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $files;

		/**
		 * Register.
		 *
		 * @param   string $base_dir   The base dir to require.
		 * @param   string $name       The name of file.
		 * @param   string $prefix     The file prefix.
		 * @param   string $suffix     The file suffix.
		 *
		 * @since   1.0.0
		 */
		public static function register( $base_dir, $name, $prefix = '', $suffix = '' ) {
			if ( $prefix ) {
				$prefix = $prefix . '-';
			}

			if ( $suffix ) {
				$suffix = '-' . $suffix;
			}

			if ( is_array( $name ) ) {
				foreach ( $name as $n ) {
					self::$files[] = array(
						'base_dir' => trim( $base_dir ),
						'name'     => trim( $n ),
						'prefix'   => trim( $prefix ),
						'suffix'   => trim( $suffix ),
					);
				}
			} else {
				self::$files[] = array(
					'base_dir' => trim( $base_dir ),
					'name'     => trim( $name ),
					'prefix'   => trim( $prefix ),
					'suffix'   => trim( $suffix ),
				);
			}
		}

		/**
		 * Load the All files
		 *
		 * @since   1.0.0
		 */
		public static function run() {
			if ( is_array( self::$files ) ) {
				foreach ( self::$files as $file ) {
					$file_target = $file['base_dir'] . '/' . $file['prefix'] . $file['name'] . $file['suffix'] . '.php';

					if ( ! self::require_file( $file_target ) ) {
						throw new Exception( 'The file "' . esc_html( $file_target ) . '" doesn`t exist', 1 );
					}
				}
			}
		}

		/**
		 * Load file.
		 *
		 * @param   string $base_dir   The base dir to require.
		 * @param   string $name       The name of file.
		 * @param   string $prefix     The file prefix.
		 * @param   string $suffix     The file suffix.
		 * @return  bool    True if the file is loaded, false if not.
		 *
		 * @since   1.0.0
		 */
		public static function load( $base_dir, $name, $prefix = '', $suffix = '' ) {
			if ( $prefix ) {
				$prefix = $prefix . '-';
			}

			if ( $suffix ) {
				$suffix = '-' . $suffix;
			}

			if ( is_array( $name ) ) {
				foreach ( $name as $n ) {
					$file_target = $base_dir . '/' . $prefix . $n . $suffix . '.php';
					if ( ! self::require_file( $file_target ) ) {
						throw new Exception( 'The file "' . esc_html( $file_target ) . '" doesn`t exists', 1 );
					}
				}
			} else {
				$file_target = $base_dir . '/' . $prefix . $name . $suffix . '.php';
				if ( ! self::require_file( $file_target ) ) {
					throw new Exception( 'The file "' . esc_html( $file_target ) . '" doesn`t exists', 1 );
				}
			}

			return true;
		}

		/**
		 * If a file exists, require it from the file system.
		 *
		 * @param string $file The file to require.
		 * @return bool True if the file exists, false if not.
		 */
		private static function require_file( $file ) {
			if ( file_exists( $file ) ) {
				require_once $file;
				return true;
			}

			return false;
		}
	} // class
}
