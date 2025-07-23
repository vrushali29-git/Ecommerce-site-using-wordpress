<?php
/**
 * off-canvas.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Templates\Main as Templates;
use ShopPress\Helpers\ThumbnailGenerator;

?>

<div class="sp-header-toggle <?php esc_attr_e( $args['open_in'] ); ?> sp-open-<?php esc_attr_e( $args['open_in'] ); ?>">
	<div class="sp-header-toggle-click">
		<?php
		if ( $args['placeholder'] ) :
			switch ( $args['placeholder'] ) {
				case 'image':
					if ( $args['image']['id'] ) {
						ThumbnailGenerator::instance()->image_resize_output(
							$args['image']['id'],
							( isset( $args['image_custom_dimension'] ) && ! empty( $args['image_custom_dimension'] ) ? $args['image_custom_dimension'] : $args['image_size'] )
						);
					}
					break;

				case 'text':
					echo '<p class="header-toggle-text">' . wp_kses( $args['text'], wp_kses_allowed_html( 'post' ) ) . '</p>';
					break;

				case 'icon':
				default:
					$icon = sp_render_icon( $args['icon'] ?? '' );

					echo wp_kses_post( $icon );
					echo '<p class="header-toggle-text">' . wp_kses( $args['text'], wp_kses_allowed_html( 'post' ) ) . '</p>';
					break;
			}
		endif;
		?>
	</div>
	<?php
	if ( $args['header_toggle_template'] ) :
		?>
		<div class="sp-header-toggle-content-wrap" style="display: none;">
			<div class="sp-header-toggle-close">
				<?php
				if ( $args['close_icon'] ) {
					echo wp_kses_post(
						sp_render_icon(
							$args['close_icon'],
							array(
								'class'       => '',
								'aria-hidden' => 'true',
							)
						)
					);
				}
				?>
			</div>
			<?php

			$template = Templates::get_template_by_title( $args['header_toggle_template'], 'elementor_library' );
			if ( $template ) {

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo sp_get_builder_content( $template->ID );
			} else {
				echo '<p>' . __( 'Please Choose a valid template', 'shop-press' ) . '</p>';
			}
			?>
		</div>
		<?php
	else :
		echo __( 'Please Choose Template', 'shop-press' );
	endif;
	?>
</div>
