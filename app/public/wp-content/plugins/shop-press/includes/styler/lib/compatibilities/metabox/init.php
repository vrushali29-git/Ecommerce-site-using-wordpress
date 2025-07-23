<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Styler Control for RWMB
 */
class RWMB_Styler_Field extends \RWMB_Multiple_Values_Field {

	/**
	 *
	 * Admin Enqueue Scripts
	 *
	 * @return void
	 */
	public static function admin_enqueue_scripts() {
		wp_enqueue_script( 'styler-rwmb-metabox', STYLER_ASSETS_URL . 'dist/styler-rwmb-metabox.js', array( 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ), '', false );

		add_filter( 'styler-localize-data', array( __CLASS__, 'get_localize_data' ) );

		localize_styler_data();

		wp_enqueue_style( 'styler-rwmb-metabox', STYLER_ASSETS_URL . 'dist/styler-rwmb-metabox.css' );
	}

	/**
	 * Get Styler localize data
	 *
	 * @return array
	 */
	public static function get_localize_data( $data ) {
		$stylerData = get_post_meta( get_the_ID(), 'rwmb_styler_data', true );

		if ( $stylerData ) {
			$stylerData = static::json_decode( $stylerData );
		}

		if ( ! is_array( $stylerData ) ) {
			$stylerData = array();
		}

		$data['GeneratedStyles'] += $stylerData;

		return $data;
	}

	/**
	 * Json Decode
	 *
	 * @since     1.0.0
	 */
	private static function json_decode( $data ) {

		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$data[ $key ] = static::json_decode( $value );
				} else {
					$data[ $key ] = json_decode( $value, true );
				}
			}
		}

		return $data;
	}

	/**
	 *
	 * Get Control Uid
	 *
	 * @return string
	 */
	public static function get_control_uid( $name, $args ) {
		return str_replace( array( '_', ' ' ), '-', $args['id'] . $name );
	}

	/**
	 *
	 * Save Handler
	 *
	 * @return void
	 */
	public static function save( $new, $old, $post_id, $field ) {
		if ( empty( $field['id'] ) || ! $field['save_field'] && isset( $_REQUEST[ $field['id'] ] ) ) {
			return;
		}

		$new    = $_REQUEST[ $field['id'] ];
		$stdata = get_post_meta( $post_id, 'rwmb_styler_data', true );
		if ( $stdata && is_array( $stdata ) ) {
			$stdata[ 'rwmb' . $post_id ]                = is_array( $stdata[ 'rwmb' . $post_id ] ) ? $stdata[ 'rwmb' . $post_id ] : array();
			$stdata[ 'rwmb' . $post_id ][ $new['cid'] ] = $new['stdata'];
		} else {
			$stdata = array(
				'rwmb' . $post_id => array(
					$new['cid'] => $new['stdata'],
				),
			);
		}

		update_post_meta( $post_id, 'rwmb_styler_data', $stdata );

		\Styler\Compatibilities\MetaBox\StyleSheet::get_instance()->parse_style( $post_id );

		$name    = $field['id'];
		$storage = $field['storage'];

		$storage->delete( $post_id, $name );
		$storage->update( $post_id, $name, $new );
	}

	/**
	 *
	 * Control HTML structure
	 *
	 * @return html
	 */
	public static function html( $meta, $args ) {
		ob_start();

		?>
			<div class="rwmb-styler-control-field">
				<div class="rwmb-styler-control-input-wrapper">
					<div class="tmp-styler-dialog-btn <?php echo esc_attr( static::get_control_uid( 'styler-btn', $args ) ); ?>"
						data-title="<?php echo esc_attr( $args['name'] ); ?>"
						data-field-id="<?php echo esc_attr( $args['id'] ); ?>"
						data-id="rwmb<?php echo esc_attr( get_the_ID() ); ?>"
						data-parent-id=""
						data-selector="<?php echo isset( $args['selector'] ) ? esc_attr( $args['selector'] ) : ''; ?>"
						data-wrapper="<?php echo isset( $args['wrapper'] ) ? esc_attr( $args['wrapper'] ) : ''; ?>"
						data-type=""
						data-is-svg="<?php echo isset( $args['isSvg'] ) ? esc_attr( $args['isSvg'] ) : ''; ?>"
						data-is-input="<?php echo isset( $args['isInput'] ) ? esc_attr( $args['isInput'] ) : ''; ?>"
						data-hover-selector="<?php echo isset( $args['hover'] ) ? esc_attr( $args['hover'] ) : ''; ?>"
					>
					<input type="hidden" name="<?php echo esc_attr( $args['id'] . '[stdata]' ); ?>" id="<?php echo esc_attr( static::get_control_uid( 'stdata', $args ) ); ?>"
							value="<?php echo @esc_attr( $meta[0]['stdata'] ); ?>" data-setting="stdata"/>
						<input type="hidden" name="<?php echo esc_attr( $args['id'] . '[cid]' ); ?>" id="<?php echo esc_attr( static::get_control_uid( 'cid', $args ) ); ?>"
							value="<?php echo @esc_attr( $meta[0]['cid'] ); ?>" data-setting="cid"/>
						<img src="<?php echo esc_url( STYLER_ASSETS_URL . 'img/styler-icon.svg' ); ?>">
					</div>
				</div>
			</div>
			<?php if ( $args['label_description'] ) : ?>
				<div class="rwmb-styler-control-field-description"><?php echo esc_attr( $args['label_description'] ); ?></div>
			<?php endif; ?>
		<?php

		return ob_get_clean();
	}
}//end class
