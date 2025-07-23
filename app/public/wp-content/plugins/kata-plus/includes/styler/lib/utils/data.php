<?php
namespace Styler\Utils;

defined( 'ABSPATH' ) || exit;

use Styler\Compatibilities\KirkiStyler\StyleSheet as KirkiStyleSheet;

class DataHandler {
	/**
	 * Array of files.
	 *
	 * @access  public
	 * @var     string
	 */
	public static $files;

	/**
	 * Import.
	 *
	 * @param   string $data       Data to import
	 * @param   string $witch_type Type of data.
	 *
	 * @since   3.0.0
	 */
	public function Import( $data, $witch_type = 'all' ) {
		$term = get_term_by( 'slug', $witch_type, 'styler-data' );

		if ( ! $term ) {
			wp_insert_term( $witch_type, 'styler-data' );
			$term = get_term_by( 'slug', $witch_type, 'styler-data' );
		}

		if( ! $term ) {
			return false;
		}

		if( ! is_object( $data ) && ! is_array( $data ) ) {
			$data = json_decode( $data );
		}

		switch ( $witch_type ) {
			case 'kirki':
				KirkiStyleSheet::get_instance()->import_customizer( $data );
				break;
		}

		return true;
	}

	/**
	 * Import.
	 *
	 * @param   string $witch_type Type of data.
	 *
	 * @since   3.0.0
	 */
	public function Export( $witch_type = 'all' ) {
		$term = get_term_by( 'slug', $witch_type, 'styler-data' );

		if ( ! $term ) {
			wp_insert_term( $witch_type, 'styler-data' );
			$term = get_term_by( 'slug', $witch_type, 'styler-data' );
		}

		if( ! $term ) {
			return false;
		}

		return get_term_meta( $term->term_id );
	}

} // class
