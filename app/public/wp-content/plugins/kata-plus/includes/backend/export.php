<?php

/**
 * Kata Export Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Kata_Plus_Export' ) ) {
	class Kata_Plus_Export {


		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Export
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
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->actions();
		}


		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions() {
			add_action( 'export_filters', array( $this, 'add_export_filters' ) );
			add_action( 'export_wp', array( $this, 'export_content' ) );
			// apply_filters('export_wp_filename', [$this, 'export_content'] ,0 , 3);
		}

		/**
		 * Add Export Filters into Export List
		 *
		 * @since     1.0.0
		 */
		public function add_export_filters() {
			echo '<p><label><input type="radio" name="content" value="kata-attachment">' . __( 'Kata Plus Custom Media', 'kata-plus' ) . '</label></p>';
			echo '<p class="description" id="all-content-desc">' . __( 'Export media as a Zip file that includes media files from the Upload Folder and media.xml', 'kata-plus' ) . '</p>';
		}

		/**
		 * Export
		 *
		 * @since     1.0.0
		 */
		public function export_content( $args ) {
			switch ( $args['content'] ) {
				case 'kata-attachment':
					// Backend

					Kata_Plus_Autoloader::load( Kata_Plus::$dir . 'includes/backend', 'wp-export' );
					ob_start();
					kata_plus_export_wp( array( 'content' => 'attachment' ) );
					$attachments   = ob_get_clean();
					$zip_file_name = Kata_Plus::$upload_dir . DIRECTORY_SEPARATOR . 'kata-attachment.zip';
					ob_start();
					if ( file_exists( $zip_file_name ) ) {
						unlink( $zip_file_name );
					}

					$zip = new ZipArchive();
					if ( $zip->open( $zip_file_name, ZIPARCHIVE::CREATE ) != true ) {
						die( 'Could not open archive' );
					}
					preg_match_all( '/<guid isPermaLink="false">(.*?)<\/guid>/', $attachments, $matches );
					foreach ( $matches[1] as $match ) {
						$real_filename = str_replace( wp_get_upload_dir()['baseurl'], wp_get_upload_dir()['basedir'], $match );
						$real_filename = realpath( $real_filename );
						if ( file_exists( $real_filename ) ) {
							$zip->addFile( $real_filename, 'uploads' . str_replace( realpath( wp_get_upload_dir()['basedir'] ), '', $real_filename ) );
						}
					}
					preg_match_all( '/<wp:attachment_url><!\[CDATA\[(.*?)\]\]><\/wp:attachment_url>/', $attachments, $second_matches );
					foreach ( $second_matches[1] as $match ) {
						$real_filename = str_replace( wp_get_upload_dir()['baseurl'], wp_get_upload_dir()['basedir'], $match );
						$real_filename = realpath( $real_filename );
						if ( file_exists( $real_filename ) ) {
							$zip->addFile( $real_filename, 'uploads' . str_replace( realpath( wp_get_upload_dir()['basedir'] ), '', $real_filename ) );
						}
					}
					$zip->addFromString( 'media.xml', $attachments );
					$zip->close();
					ob_clean();
					header( 'Content-Description: File Transfer' );
					header( 'Content-Type: application/zip' );
					header( 'Content-Disposition: attachment; filename=' . basename( $zip_file_name ) );
					header( 'Content-Length: ' . filesize( $zip_file_name ) );
					readfile( $zip_file_name );
					unlink( $zip_file_name );
					die();
					break;
			}
		}
	}
	Kata_Plus_Export::get_instance();
}
