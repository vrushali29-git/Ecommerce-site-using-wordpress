<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Styler Control for Global Use
 */
class StylerGlobalField {

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public static $instance;
	private $registered = array();

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
	 *
	 * Get Control Uid
	 *
	 * @return string
	 */
	private function check( $field_id ) {
		if ( in_array( $field_id, $this->registered ) ) {
			/* Translators: %s replaced with $field_id */
			throw new \Exception( \wp_sprintf( __( 'Fatal error: Cannot redeclare <%s>' ), $field_id ), 1 );
		}
		$this->registered[] = $field_id;
	}

	/**
	 *
	 * Get Control Uid
	 *
	 * @return string
	 */
	public function get_value( $field_id, $key ) {
		$term = get_term_by( 'slug', 'global', 'styler-data' );

		if ( $term === null || $term === false ) {
			wp_insert_term( 'global', 'styler-data' );
			$term = get_term_by( 'slug', 'global', 'styler-data' );
		}

		$meta = get_term_meta( $term->term_id, $field_id, true );

		if ( is_array( $meta ) && isset( $meta[ $key ] ) ) {
			return $meta[ $key ];
		}

		return '';
	}

	/**
	 *
	 * Get Control Uid
	 *
	 * @return string
	 */
	public function get_control_uid( $name, $field_id ) {
		return str_replace( array( '_', ' ' ), '-', $field_id . '-' . $name );
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @since     1.0.0
	 */
	private function enqueue_admin_scripts() {

		$asset_file = include( STYLER_PATH . 'build/styler-chunk.asset.php');

		wp_enqueue_script( 'styler-global-chunk', STYLER_URL . 'build/styler-chunk.js', $asset_file['dependencies'], $asset_file['version'], false );

		$asset_file = include STYLER_PATH . '/build/styler-global.asset.php';

		wp_enqueue_script( 'styler-global', STYLER_URL . 'build/styler-global.js', array_merge( array( 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ), $asset_file['dependencies'] ), $asset_file['version'], true );


		add_filter( 'styler-localize-data', array( __CLASS__, 'get_localize_data' ) );

		localize_styler_data();
		wp_enqueue_media();

		wp_enqueue_style( 'styler-global', STYLER_ASSETS_URL . 'build/styler-global.css' );
	}

	/**
	 * Get Styler localize data
	 *
	 * @return array
	 */
	public static function get_localize_data( $data ) {
		$stylerData = array();

		$term = get_term_by( 'slug', 'global', 'styler-data' );

		if ( $term === null || $term === false ) {
			wp_insert_term( 'global', 'styler-data' );
			$term = get_term_by( 'slug', 'global', 'styler-data' );
		}

		$meta = get_term_meta( $term->term_id );

		foreach ( $meta as $key => $value ) {
			$value = maybe_unserialize( current( $value ) );

			if ( ! is_array( $value['stdata'] ) && ! is_object( $value['stdata'] ) ) {
				$value['stdata'] = json_decode( $value['stdata'], true );
			}

			$stylerData['global'][ $key ] = $value['stdata'];
		}

		$data['GeneratedStyles'] += $stylerData;

		return $data;
	}

	/**
	 * Field Render
	 *
	 * @param int   $field_id
	 * @param array $arguments
	 * Arguments are [ title, selector, hover, parent, isSvg, isInput, desc ]
	 * @return void
	 */
	public function field( $field_id, $arguments ) {
		$this->check( $field_id );
		$this->enqueue_admin_scripts();
		?>
			<div class="global-styler-control-field">
				<div class="global-styler-control-input-wrapper">
					<label class="global-control-title"><?php echo esc_attr( $arguments['title'] ); ?></label>
					<div class="tmp-styler-dialog-btn <?php echo esc_attr( $this->get_control_uid( 'styler-btn', $field_id ) ); ?>"
						data-title="<?php echo esc_attr( $arguments['title'] ); ?>"
						data-field-id="<?php echo esc_attr( $field_id ); ?>"
						data-id="global"
						data-parent-id=""
						data-selector="<?php echo isset( $arguments['selector'] ) ? esc_attr( $arguments['selector'] ) : ''; ?>"
						data-wrapper="<?php echo isset( $arguments['wrapper'] ) ? esc_attr( $arguments['wrapper'] ) : ''; ?>"
						data-type=""
						data-is-svg="<?php echo isset( $arguments['isSVG'] ) ? esc_attr( $arguments['isSVG'] ) : ''; ?>"
						data-is-input="<?php echo isset( $arguments['isInput'] ) ? esc_attr( $arguments['isInput'] ) : ''; ?>"
						data-hover-selector="<?php echo isset( $arguments['hover'] ) ? esc_attr( $arguments['hover'] ) : ''; ?>">

						<input type="hidden" id="<?php echo esc_attr( $this->get_control_uid( 'stdata', $field_id ) ); ?>"
							value="<?php echo esc_attr( $this->get_value( $field_id, 'stdata' ) ); ?>" data-setting="stdata"/>
							<input type="hidden" id="<?php echo esc_attr( $this->get_control_uid( 'cid', $field_id ) ); ?>"
							value="<?php echo esc_attr( $this->get_value( $field_id, 'cid' ) ); ?>" data-setting="cid"/>
						<img src="<?php echo esc_url( STYLER_ASSETS_URL . 'img/styler-icon.svg' ); ?>">
					</div>
				</div>
			</div>
			<?php if ( isset( $arguments['desc'] ) ) : ?>
				<div class="global-styler-control-field-description"><?php echo esc_attr( $arguments['desc'] ); ?></div>
			<?php endif; ?>
		<?php
	}
}

function styler_global_field( $field_id, $arguments ) {
	StylerGlobalField::get_instance()->field( $field_id, $arguments );
}
