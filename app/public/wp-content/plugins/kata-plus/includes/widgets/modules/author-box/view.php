<?php
/**
 * Author Box widget view.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Content variables
$image       = $image_id ? '<img class="kata-author-box-widget-image" src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '">' : '';
$name        = $name ? '<p class="kata-author-box-widget-name">' . $name . '</p>' : '';
$description = $description ? '<p class="kata-author-box-widget-description">' . $description . '</p>' : '';
// Social variables
$twitter     = $twitter && $twitter_link ? '<a href="' . esc_url( $twitter_link ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'twitter' ) . '</a>' : '';
$facebook    = $facebook && $facebook_link ? '<a href="' . esc_url( $facebook_link ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'facebook' ) . '</a>' : '';
$instagram   = $instagram && $instagram_link ? '<a href="' . esc_url( $instagram_link ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'instagram' ) . '</a>' : '';
$google_plus = $google_plus && $google_plus_link ? '<a href="' . esc_url( $google_plus_link ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'google' ) . '</a>' : '';
$pinterest   = $pinterest && $pinterest_link ? '<a href="' . esc_url( $pinterest_link ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'pinterest-alt' ) . '</a>' : '';
$dribbble    = $dribbble && $dribbble_link ? '<a href="' . esc_url( $dribbble_link ) . '">' . Kata_Plus_Helpers::get_icon( 'themify', 'dribbble' ) . '</a>' : '';

if ( ! empty( $title ) ) {
	echo wp_kses_post( $before_title ) . apply_filters( 'widget_title', $title ) . wp_kses_post( $after_title );
}
?>

<div class="kata-author-box-widget">
	<div class="kata-author-box-widget-content">
		<?php echo wp_kses( $image, wp_kses_allowed_html( 'post' ) ); ?>
		<?php echo wp_kses( $description, wp_kses_allowed_html( 'post' ) ); ?>
		<?php echo wp_kses( $name, wp_kses_allowed_html( 'post' ) ); ?>
	</div>
	<div class="kata-author-box-widget-social">
		<?php echo wp_kses( $twitter, wp_kses_allowed_html( 'post' ) ); ?>
		<?php echo wp_kses( $facebook, wp_kses_allowed_html( 'post' ) ); ?>
		<?php echo wp_kses( $instagram, wp_kses_allowed_html( 'post' ) ); ?>
		<?php echo wp_kses( $google_plus, wp_kses_allowed_html( 'post' ) ); ?>
		<?php echo wp_kses( $pinterest, wp_kses_allowed_html( 'post' ) ); ?>
		<?php echo wp_kses( $dribbble, wp_kses_allowed_html( 'post' ) ); ?>
	</div>
</div>
