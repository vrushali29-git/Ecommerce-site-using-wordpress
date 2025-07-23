<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Styler Control for Widgets
 */
class StylerWidgetField {

	private static $data;

	/**
	 *
	 * Get Control Uid
	 *
	 * @return string
	 */
	public static function get_control_uid( $name, $field_id ) {
		return str_replace( array( '_', ' ' ), '-', $field_id . '-' . $name );
	}

	/**
	 * Field Render
	 *
	 * @param \WP_Widget $widget
	 * @param int        $field_id
	 * @param array      $arguments
	 * @param array      $widget->widget_data
	 * Arguments are [ title, selector, hover, parent, isSvg, isInput, desc ]
	 * @return void
	 */
	public static function field( \WP_Widget $widget, $field_id, $arguments, $widget_data ) {
		?>
			<div class="wp-widget-styler-control-field">
				<div class="wp-widget-styler-control-input-wrapper">
					<label class="wp-widget-control-title"><?php echo esc_attr( $arguments['title'] ); ?></label>
					<div class="tmp-styler-dialog-btn <?php echo esc_attr( static::get_control_uid( 'styler-btn', $field_id ) ); ?>"
						data-title="<?php echo esc_attr( $arguments['title'] ); ?>"
						data-field-id="<?php echo esc_attr( $field_id ); ?>"
						data-id="<?php echo esc_attr( ltrim( rtrim( $widget->get_field_id( '' ), '-' ), 'widget-' ) ); ?>"
						data-parent-id=""
						data-selector="<?php echo isset( $arguments['selector'] ) ? esc_attr( $arguments['selector'] ) : ''; ?>"
						data-wrapper="#<?php echo esc_attr( ltrim( rtrim( $widget->get_field_id( '' ), '-' ), 'widget-' ) ); ?>"
						data-type=""
						data-is-svg="<?php echo isset( $arguments['isSVG'] ) ? esc_attr( $arguments['isSVG'] ) : ''; ?>"
						data-is-input="<?php echo isset( $arguments['isInput'] ) ? esc_attr( $arguments['isInput'] ) : ''; ?>"
						data-hover-selector="<?php echo isset( $arguments['hover'] ) ? esc_attr( $arguments['hover'] ) : ''; ?>">

						<input type="hidden" name="<?php echo esc_attr( $widget->get_field_name( 'stdata' ) ); ?>" id="<?php echo esc_attr( static::get_control_uid( 'stdata', $field_id ) ); ?>"
							value="<?php echo esc_attr( @$widget_data['stdata'] ); ?>" data-setting="stdata"/>
						<input type="hidden" name="<?php echo esc_attr( $widget->get_field_name( 'cid' ) ); ?>" id="<?php echo esc_attr( static::get_control_uid( 'cid', $field_id ) ); ?>"
							value="<?php echo esc_attr( $field_id ); ?>" data-setting="cid"/>
						<input type="hidden" name="styler-field-name" value="<?php echo esc_attr( $widget->name ); ?>"/>
						<img src="<?php echo esc_url( STYLER_ASSETS_URL . 'img/styler-icon.svg' ); ?>">
					</div>
				</div>
			</div>
			<?php if ( isset( $arguments['desc'] ) ) : ?>
				<div class="wp-widget-styler-control-field-description"><?php echo esc_html( $arguments['desc'] ); ?></div>
			<?php endif; ?>
		<?php
	}
}
