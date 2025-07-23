<?php
/**
 * Fast mode Template 2
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */
$kata_options = get_option( 'kata_options' )['fast-mode'];
$address      = isset( $kata_options['site-address'] ) && ! empty( $kata_options['site-address'] ) ? $kata_options['site-address'] : '37 Wintergreen Ave. White Rock, BC V4B 0V6';
$phone        = isset( $kata_options['site-phone'] ) && ! empty( $kata_options['site-phone'] ) ? $kata_options['site-phone'] : '123456789';
$facebook     = isset( $kata_options['site-facebook'] ) && ! empty( $kata_options['site-facebook'] ) ? $kata_options['site-facebook'] : 'https://facebook.com/facebookid';
$twitter      = isset( $kata_options['site-twitter'] ) && ! empty( $kata_options['site-twitter'] ) ? $kata_options['site-twitter'] : 'https://twitter.com/twitterid';
$linkedin     = isset( $kata_options['site-linkedin'] ) && ! empty( $kata_options['site-linkedin'] ) ? $kata_options['site-linkedin'] : 'https://linkedin.com/in/linkedinid';
$instagram    = isset( $kata_options['site-instagram'] ) && ! empty( $kata_options['site-instagram'] ) ? $kata_options['site-instagram'] : 'https://instagram.com/instagramid';
?>

<div id="kt-fst-mod-4" class="kt-fst-mod-wrapper">
	<h1 id="page-title" class="chose-bussiness-type"><?php echo esc_html__( 'Contact Information', 'kata-plus' ); ?></h1>
	<div class="kt-fst-mod-inner-wrapper">

		<div class="kt-fst-get-info-row">
			<div class="kt-fst-get-info">
				<label for="site-address" class="kt-fst-get-label"><?php echo esc_html__( 'Address', 'kata-plus' ); ?></label>
				<input type="text" name="site-address" id="site-address" value="<?php echo esc_attr( $address ); ?>">
			</div>
			<div class="kt-fst-get-info">
				<label for="site-phone" class="kt-fst-get-label"><?php echo esc_html__( 'Phone', 'kata-plus' ); ?></label>
				<input type="text" name="site-phone" id="site-phone" value="<?php echo esc_attr( $phone ); ?>">
			</div>
		</div>

		<label for="site-socials" class="kt-fst-get-label"><?php echo esc_html__( 'Socials', 'kata-plus' ); ?></label>
		<div class="kt-fst-get-info-row">
			<div class="kt-fst-get-info social">
				<div class="fm-social">
					<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( '', 'font-awesome/facebook', '', '' ) ); ?>
				</div>
				<input type="text" name="facebook" class="site-socials" id="site-facebook" placeholder="<?php echo esc_attr( 'https://facebook.com/facebookid' ); ?>" value="">
			</div>
			<div class="kt-fst-get-info social">
				<div class="fm-social">
					<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( '', 'font-awesome/twitter', '', '' ) ); ?>
				</div>
				<input type="text" name="twitter" class="site-socials" id="site-twitter" placeholder="<?php echo esc_attr( 'https://twitter.com/twitterid' ); ?>" value="">
			</div>
			<div class="kt-fst-get-info social">
				<div class="fm-social">
					<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( '', 'font-awesome/linkedin', '', '' ) ); ?>
				</div>
				<input type="text" name="linkedin" class="site-socials" id="site-linkedin" placeholder="<?php echo esc_attr( 'https://linkedin.com/in/linkedinid' ); ?>" value="">
			</div>
			<div class="kt-fst-get-info social">
				<div class="fm-social">
					<?php echo wp_kses_post( Kata_Plus_Helpers::get_icon( '', 'font-awesome/instagram', '', '' ) ); ?>
				</div>
				<input type="text" name="instagram" class="site-socials" id="site-instagram" placeholder="<?php echo esc_attr( 'https://instagram.com/instagramid' ); ?>" value="">
			</div>
		</div>
	</div>
</div>
<div class="kt-fst-mod-footer-area kt-fst-mod-4">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=3' ) ); ?>" class="prev-step">
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_12" data-name="Path 12" d="M.854,5.844a.5.5,0,0,1-.707-.707L5.136.147A.494.494,0,0,1,5.489,0L5.5,0l.01,0a.491.491,0,0,1,.4.212l4.946,4.945a.5.5,0,0,1-.707.707L6.01,1.728V19.5a.5.5,0,0,1-1,0V1.686Z" transform="translate(0 11) rotate(-90)" fill="#adb8b8"/>
			</svg>
		</i>
		<?php echo esc_html__( 'Back', 'kata-plus' ); ?>
	</a>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=5&site-phone=' . esc_html( $phone ) . '&site-address=' . esc_html( $address ) . '&site-facebook=' . esc_url( $facebook ) . '&site-twitter=' . esc_url( $twitter ) . '&site-linkedin=' . esc_url( $linkedin ) . '&site-instagram=' . esc_url( $instagram ) . '/' ) ); ?>" class="next-step">
		<?php echo esc_html__( 'Next', 'kata-plus' ) ?>
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_11" data-name="Path 11" d="M19.333,14.156a.5.5,0,0,0-.707.707l4.989,4.99a.494.494,0,0,0,.353.147l.01,0,.01,0a.491.491,0,0,0,.4-.212l4.946-4.945a.5.5,0,0,0-.707-.707L24.49,18.272V.5a.5.5,0,0,0-1,0V18.314Z" transform="translate(0 29.479) rotate(-90)" fill="#00d6f9"/>
			</svg>
		</i>
	</a>
</div>
