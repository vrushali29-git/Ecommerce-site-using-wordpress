<?php
/**
 * Author Box module view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings        = $this->get_settings();
$name            = get_the_author_meta( 'display_name' ) ? get_the_author_meta( 'display_name' ) : __( 'author', 'kata-plus' );
$avatar_size     = $settings['avatar_size']['size'];
$avatar          = get_avatar( get_the_author_meta( 'user_email' ), $avatar_size );
$email           = get_the_author_meta( 'user_email' );
$description     = get_the_author_meta( 'user_description' ) ? get_the_author_meta( 'user_description' ) : 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis error dignissimos fugiat voluptas, asperiores quibusdam exercitationem id quam reiciendis expedita quidem nemo? Illo quod suscipit est possimus officiis repellendus eum.';
$author_id       = get_the_author_meta( 'ID' );
$author_nicename = get_the_author_meta( 'nickname' );
$author_link     = get_author_posts_url( $author_id, $author_nicename );
$author_url      = Kata_Plus_Helpers::get_link_attr( $settings['author_link'] );
if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::start_parallax( $settings['parallax'], $settings['parallax_speed'], $settings['parallax_mouse_speed'] );
}
?>
<div class="kata-author-box">
	<?php
	if ( $settings['show_avatar'] == 'yes' ) {
		echo '<div class="kata-author-thumbnail kata-lazyload">' . $avatar . '</div>';
	}
	?>
	<div class="kata-plus-author-content">
		<h3 class="kata-plus-author-name">
			<?php echo wp_kses_post( $name ); ?>
		</h3>
		<?php
		if ( $settings['show_description'] == 'yes' ) {
			echo '<p class="kata-author-box-description">' . wp_kses_post( $description ) . '</p>';
		}
		?>
		<?php if ( $settings['author_link_content'] ) { ?>
			<a <?php echo $author_url->src . ' ' . $author_url->rel . ' ' . $author_url->target; ?> class="author-link"><?php echo wp_kses( $settings['author_link_content'], wp_kses_allowed_html( 'post' ) ); ?></a>
		<?php } ?>
	</div>
</div>
<?php

if ( class_exists( 'Kata_Plus_Pro_Elementor' ) && ! empty( $settings['parallax'] ) ) {
	Kata_Plus_Pro_Elementor::end_parallax( $settings['parallax'] );
}
if ( isset( $settings['custom_css'] ) ) {
	Kata_Plus_Elementor::module_custom_css_editor( $settings['custom_css'] );
}
