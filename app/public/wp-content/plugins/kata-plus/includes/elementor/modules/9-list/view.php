<?php
/**
 * List view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $this->get_settings_for_display();
$lists    = $settings['list'];

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
} ?>

<?php if ( $lists ) : ?>
	<ul class="kata-plus-list">
		<?php foreach ( $lists as $index => $list ) : ?>
			<?php
			$list_title_setting_key       = $this->get_repeater_setting_key( 'list_title', 'list', $index );
			$list_description_setting_key = $this->get_repeater_setting_key( 'list_description', 'list', $index );
			$this->add_inline_editing_attributes( $list_title_setting_key );
			$this->add_inline_editing_attributes( $list_description_setting_key );
			?>
			<li class="kata-plus-lists">
				<?php
				if ( $list['list_link']['url'] ) {
					$attr  = $list['list_link']['is_external'] != '' ? 'target=_blank' : '';
					$attr .= $list['list_link']['nofollow'] != '' ? ' rel=nofollow' : '';
					?>
					<a href="<?php echo esc_attr( $list['list_link']['url'] ); ?>" <?php echo esc_attr( $attr ); ?>>
					<?php
				}
				if ( $list['list_icon'] ) {
					?>
						<span class="list-icon-wrapper">
							<?php echo Kata_Plus_Helpers::get_icon( '', $list['list_icon'], '', '' ); ?>
						</span>
					<?php } if ( $list['list_title'] ) { ?>
						<span class="list-title elementor-inline-editing" <?php echo $this->get_render_attribute_string( $list_title_setting_key ); ?>><?php echo wp_kses( $list['list_title'], wp_kses_allowed_html( 'post' ) ); ?></span>
					<?php } if ( $list['list_description'] ) { ?>
						<span class="list-description elementor-inline-editing" <?php echo $this->get_render_attribute_string( $list_description_setting_key ); ?> data-class="list-subtitle"<?php echo $this->get_render_attribute_string( $list_description_setting_key ); ?>><?php echo wp_kses( $list['list_description'], wp_kses_allowed_html( 'post' ) ); ?></span>
					<?php } ?>
				<?php if ( $list['list_link']['url'] ) { ?>
					</a>
				<?php } ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
endif;

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
