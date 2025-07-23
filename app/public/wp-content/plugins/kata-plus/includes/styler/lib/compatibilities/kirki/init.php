<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Styler Control for Kirki Use
 */
class Kirki_Field_Styler extends \Kirki\Control\Base {

	public $type = 'styler';

	/**
	 * Enqueue Admin Scripts
	 *
	 * @since     1.0.0
	 */
	public function enqueue() {
		parent::enqueue();
		if ( ! wp_script_is( 'styler-kirki' ) ) {

			$asset_file = include( STYLER_PATH . 'build/styler-chunk.asset.php');

			wp_enqueue_script( 'styler-kirki-chunk', STYLER_URL . 'build/styler-chunk.js', $asset_file['dependencies'], $asset_file['version'], false );

			$asset_file = include STYLER_PATH . '/build/styler-kirki.asset.php';

			wp_enqueue_script( 'styler-kirki', STYLER_URL . 'build/styler-kirki.js', array_merge( array( 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ), $asset_file['dependencies'] ), $asset_file['version'], true );

			add_filter( 'styler-localize-data', array( __CLASS__, 'get_localize_data' ) );

			wp_enqueue_style(  'styler-style-dark', STYLER_URL . 'build/styler-dark.css', [],  $asset_file['version'], '(prefers-color-scheme: dark)');

			localize_styler_data();

			wp_enqueue_style(  'styler-style-chunk', STYLER_URL . 'build/styler-chunk.css', [],  $asset_file['version'] );

			wp_enqueue_style(  'styler-kirki', STYLER_URL . 'build/styler-kirki.css', [],  $asset_file['version'] );

			if ( is_rtl() ) {
				wp_enqueue_style( 'styler-style-rtl', STYLER_URL . 'src/rtl/styler-rtl.css', [],  $asset_file['version'] );
			}

			wp_set_script_translations(
				'styler-kirki-chunk',
				'styler',
				STYLER_PATH . 'languages'
			);
		}
	}

	/**
	 * Get Styler localize data
	 *
	 * @return array
	 */
	public static function get_localize_data( $data ) {
		$stylerData = array();

		$term = get_term_by( 'slug', 'kirki', 'styler-data' );

		if ( $term === null || $term === false ) {
			wp_insert_term( 'kirki', 'styler-data' );
			$term = get_term_by( 'slug', 'kirki', 'styler-data' );
		}

		$meta = get_term_meta( $term->term_id );

		foreach ( $meta as $key => $value ) {
			$value = maybe_unserialize( current( $value ) );
			
			if (! is_array($value))
			continue;
			if ( ! is_array( $value['stdata'] ) && ! is_object( $value['stdata'] ) ) {
				$value['stdata'] = json_decode( $value['stdata'], true );
			}

			$stylerData['kirki'][ $key ] = $value['stdata'];
		}

		$data['GeneratedStyles'] += $stylerData;
		return $data;
	}

	/**
	 *
	 * Get Control UID
	 *
	 * @return string
	 */
	public function get_control_uid( $name, $field_id ) {
		return str_replace( array( '_', ' ' ), '-', $field_id . '-' . $name );
	}

	/**
	 *
	 * Get Control Uid
	 *
	 * @return string
	 */
	public function get_value( $field_id, $key ) {
		$term = get_term_by( 'slug', 'kirki', 'styler-data' );

		if ( $term === null || $term === false ) {
			wp_insert_term( 'kirki', 'styler-data' );
			$term = get_term_by( 'slug', 'kirki', 'styler-data' );
		}

		$meta = get_term_meta( $term->term_id, $field_id, true );

		if( ! $meta ) {
			$meta = get_term_meta( $term->term_id, str_replace( 'styler_', '', $field_id ), true );
		}

		if ( is_array( $meta ) && isset( $meta[ $key ] ) ) {
			if ( is_array( $meta[ $key ] ) || is_object( $meta[ $key ] ) ) {
				$meta[ $key ] = json_encode( $meta[ $key ] );
			}
			return $meta[ $key ];
		}

		return '';
	}

	/**
	 * Render the Field Content
	 *
	 * @return void
	 */
	public function render_content() {
		// $this->enqueue_admin_scripts();
		$styler_data = get_option( 'styler_' . $this->id, false );
		$arguments   = $this->choices;
		?>
		<div class="kirki-styler-control-field">
				<div class="kirki-styler-control-input-wrapper">
					<label class="kirki-control-title"><?php echo esc_attr( $this->label ); ?></label>
					<div class="tmp-styler-dialog-btn <?php echo esc_attr( $this->get_control_uid( 'styler-btn', $this->id ) ); ?>"
						data-title="<?php echo esc_attr( $this->label ); ?>"
						data-field-id="<?php echo esc_attr( $this->id ); ?>"
						data-id="kirki"
						data-parent-id=""
						data-selector="<?php echo isset( $arguments['selector'] ) ? esc_attr( $arguments['selector'] ) : ''; ?>"
						data-wrapper="<?php echo isset( $arguments['wrapper'] ) ? esc_attr( $arguments['wrapper'] ) : ''; ?>"
						data-type=""
						data-is-svg="<?php echo isset( $arguments['isSVG'] ) ? esc_attr( $arguments['isSVG'] ) : ''; ?>"
						data-is-input="<?php echo isset( $arguments['isInput'] ) ? esc_attr( $arguments['isInput'] ) : ''; ?>"
						data-hover-selector="<?php echo isset( $arguments['hover'] ) ? esc_attr( $arguments['hover'] ) : ''; ?>">

						<input type="hidden" id="<?php echo esc_attr( $this->get_control_uid( 'stdata', $this->id ) ); ?>"
							value="<?php echo esc_attr( $this->get_value( $this->id, 'stdata' ) ); ?>" data-setting="stdata"/>
						<input type="hidden" id="<?php echo esc_attr( $this->get_control_uid( 'cid', $this->id ) ); ?>"
							value="<?php echo esc_attr( $this->id ); ?>" data-setting="cid"/>
						<img src="<?php echo esc_url( STYLER_ASSETS_URL . 'img/styler-icon.svg' ); ?>">
					</div>
				</div>
			</div>
			<?php if ( isset( $arguments['desc'] ) ) : ?>
				<div class="kirki-styler-control-field-description"><?php echo esc_attr( $arguments['desc'] ); ?></div>
			<?php endif; ?>
		<?php
	}
} // Class

// StylerKirkiControl::get_instance();
add_filter(
	'kirki_control_types',
	function ( $controls ) {
		$controls['styler'] = 'Kirki_Field_Styler';
		return $controls;
	}
);
