<?php
/**
 * Fast mode Template 3
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

require_once ABSPATH . '/wp-admin/includes/translation-install.php';
$kata_options = get_option( 'kata_options' )['fast-mode'];
$logo         = isset( $kata_options['site-logo'] ) && ! empty( $kata_options['site-logo'] ) ? 'background-image: url(' . wp_get_attachment_url( $kata_options['site-logo'] ) . ');' : '';
$logo_id      = isset( $kata_options['site-logo'] ) && ! empty( $kata_options['site-logo'] ) ? $kata_options['site-logo'] : '';
$icon         = isset( $kata_options['site-icon'] ) && ! empty( $kata_options['site-icon'] ) ? 'background-image: url(' . wp_get_attachment_url( $kata_options['site-icon'] ) . ');' : '';
$icon_id      = isset( $kata_options['site-icon'] ) && ! empty( $kata_options['site-icon'] ) ? $kata_options['site-icon'] : '';
?>

<div id="kt-fst-mod-3" class="kt-fst-mod-wrapper">
	<h1 id="page-title" class="chose-bussiness-type"><?php echo esc_html__( 'Logo & Icon', 'kata-plus' ); ?></h1>
	<div class="kt-fst-mod-inner-wrapper">
		<div class="kt-fst-get-info-row">
			<div class="kt-fst-get-info site-logo-icon site-icon">
				<p><?php echo esc_html__( 'Site Icon', 'kata-plus' ); ?></p>
				<div class="image-place-holder" style="<?php echo esc_attr( $icon ); ?>"></div>
				<a href="#" class="change-image" data-frame-title="<?php echo esc_attr( 'Upload Site Icon', 'kata-plus' ); ?>" data-insert-title="<?php echo esc_attr( 'Set as site icon', 'kata-plus' ); ?>"><?php echo esc_html__( 'Change', 'kata-plus' ); ?></a>
				<i><?php echo esc_html__( 'Site Icons should be square.', 'kata-plus' ); ?></i>
				<i><?php echo esc_html__( 'Recommended size: 512 × 512 pixels.', 'kata-plus' ); ?></i>
			</div>
			<div class="kt-fst-get-info site-logo-icon site-logo">
				<p><?php echo esc_html__( 'Logo', 'kata-plus' ); ?></p>
				<div class="image-place-holder" style="<?php echo esc_attr( $logo ); ?>"></div>
				<a href="#" class="change-image" data-frame-title="<?php echo esc_attr( 'Upload Logo', 'kata-plus' ); ?>" data-insert-title="<?php echo esc_attr( 'Set as site logo', 'kata-plus' ); ?>"><?php echo esc_html__( 'Change', 'kata-plus' ); ?></a>
			</div>
		</div>
	</div>
</div>
<div class="kt-fst-mod-footer-area kt-fst-mod-3">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2' ) ); ?>" class="prev-step">
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_12" data-name="Path 12" d="M.854,5.844a.5.5,0,0,1-.707-.707L5.136.147A.494.494,0,0,1,5.489,0L5.5,0l.01,0a.491.491,0,0,1,.4.212l4.946,4.945a.5.5,0,0,1-.707.707L6.01,1.728V19.5a.5.5,0,0,1-1,0V1.686Z" transform="translate(0 11) rotate(-90)" fill="#adb8b8"/>
			</svg>
		</i>
		<?php echo esc_html__( 'Back', 'kata-plus' ); ?>
	</a>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=4&site-icon=' . $icon_id . '&site-logo=' . $logo_id . '/' ) ); ?>" class="next-step">
		<?php echo esc_html__( 'Next', 'kata-plus' ) ?>
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_11" data-name="Path 11" d="M19.333,14.156a.5.5,0,0,0-.707.707l4.989,4.99a.494.494,0,0,0,.353.147l.01,0,.01,0a.491.491,0,0,0,.4-.212l4.946-4.945a.5.5,0,0,0-.707-.707L24.49,18.272V.5a.5.5,0,0,0-1,0V18.314Z" transform="translate(0 29.479) rotate(-90)" fill="#00d6f9"/>
			</svg>
		</i>
	</a>
</div>
