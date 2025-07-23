<?php
namespace Styler\Utils;

/**
 * Styler UploadHandler Class.
 *
 * @author  ClimaxThemes
 * @package Styler
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UploadHandler {

	private $path;
	private $subfolder;
	private $filename;
	private $extension;

	/**
	 * Constructor
	 *
	 * @since     1.0.0
	 */
	function __construct( $subfolder, $filename, $extension ) {
		$this->subfolder = $subfolder;
		$this->filename  = $filename;
		$this->extension = $extension;

		$this->init();
	}


	/**
	 * Init Upload Dir
	 *
	 * @since     1.0.0
	 */
	private function init() {

		$styler_dir = implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), $this->subfolder, $this->extension ) ) . DIRECTORY_SEPARATOR;
		if ( ! realpath( $styler_dir ) ) {
			wp_mkdir_p( $styler_dir );
		}

		$this->path = realpath( $styler_dir ) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Write Data
	 *
	 * @since     1.0.0
	 */
	public function write( $content ) {
		@unlink( $this->path . $this->filename . '.' . $this->extension );
		return file_put_contents( $this->path . $this->filename . '.' . $this->extension, $content );
	}

	/**
	 * Delete File
	 *
	 * @since     1.0.0
	 */
	public function delete() {
		return @unlink( $this->path . $this->filename . '.' . $this->extension );
	}
} // Class
