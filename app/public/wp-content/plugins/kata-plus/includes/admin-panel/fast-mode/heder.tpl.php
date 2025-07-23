<?php
/**
 * Fast mode Template 1
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="kt-fst-mod-header">
	<div class="kt-fst-help">
		<img src="<?php echo esc_url( Kata_Plus::$assets . 'images/admin/dashboard/fast-mode-help-icon.svg' ); ?>" alt="kata fast mode help">
	</div>
	<div class="kt-fst-close">
		<a href="<?php echo esc_url( Kata_Plus_Fast_Mode::close_url() ); ?>">
			<img src="<?php echo esc_url( Kata_Plus::$assets . 'images/admin/dashboard/fast-mode-close-icon.svg' ); ?>" alt="kata fast mode help">
		</a>
	</div>
</div>
