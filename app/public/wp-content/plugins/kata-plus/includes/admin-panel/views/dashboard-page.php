<?php
/**
 * Theme Activation.
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
<div class="kata-admin kata-dashboard-page wrap about-wrap">
	<?php $this->header(); ?>
	<div class="kata-container">
		<div class="kata-row">
			<div class="kata-col-sm-12">
				<div class="kata-card kata-primary">
					<div class="kata-card-body">
						<?php do_action( 'kata_plus_license_form' ); ?>
						<div class="kata-container">
							<div class="kata-row">
								<div class="kata-help-intro kata-dashboard-box">
									<p><?php echo esc_html__( 'With a simple search in Helpdesk or chatting with our online chat bot you can find the answer to your questions even faster. In case you did not find what you were looking for, our support team will quickly respond to you in the same chat.', 'kata-plus' ); ?></p>
									<a href="https://climaxthemes.com/kata/documentation/" class="help-links" target="_blank"><i class="ti-help-alt"></i><?php echo esc_html__( 'Search Helpdesk', 'kata-plus' ); ?></a>
									<a href="#" class="help-links chat-link"><i class="ti-headphone-alt"></i><?php echo esc_html__( 'Start Chat', 'kata-plus' ); ?></a>
								</div>
							</div>
							<div class="kata-row">
								<!-- Documentation -->
								<div class="kata-col-sm-4 kata-dashboard-box">
									<div class="kata-dashboard-box-inner">
										<h3 class="kt-dashboard-box-title"><i class="dashicons dashicons-book"></i><?php echo esc_html__( 'Documentation', 'kata-plus' ); ?></h3>
										<div class="kt-dashboard-sidebar-widget">
											<p><?php
											/* translators: 1 & 2 is replaced with kata documentation URL */
											echo wp_sprintf( esc_html__( 'Are you familiar with how Kata works? Make sure to read our comprehensive %1$s online Documentation %2$s which includes helpful video tutorials', 'kata-plus' ), '<a href="https://climaxthemes.com/kata/documentation/" class="kt-dashboard-links">', '</a>' ); ?></p>
										</div>
									</div>
								</div>
								<!-- Review -->
								<div class="kata-col-sm-4 kata-dashboard-box">
									<div class="kata-dashboard-box-inner">
										<h3 class="kt-dashboard-box-title"><i class="dashicons dashicons-star-filled"></i><?php echo esc_html__( 'Rate us', 'kata-plus' ); ?></h3>
										<div class="kt-dashboard-sidebar-widget">
											<p><?php
											/* translators: 1 & 2 is replaced with kata review page at wordpress.org */
											echo wp_sprintf( esc_html__( 'Are you enjoying Kata? Please support us by leaving a positive review for us %1$shere%2$s', 'kata-plus' ), '<a href="https://wordpress.org/support/theme/kata/reviews/#new-post" class="kt-dashboard-links">', '</a>' ); ?></p>
										</div>
									</div>
								</div>
								<!-- Review -->
								<div class="kata-col-sm-4 kata-dashboard-box">
									<div class="kata-dashboard-box-inner">
										<h3 class="kt-dashboard-box-title"><i class="dashicons dashicons-facebook"></i><?php echo esc_html__( 'Kata Community', 'kata-plus' ); ?></h3>
										<div class="kt-dashboard-sidebar-widget">
											<p><?php
											/* translators: 1 & 2 is replaced with climaxthemes facebook */
											echo wp_sprintf( esc_html__( 'Feel free to join the Kata community and ask us technical questions, leave feedback or ask other users for help. %1$sjoin now%2$s', 'kata-plus' ), '<a href="https://www.facebook.com/climaxthemes/" class="kt-dashboard-links">', '</a>' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="kata-row">
			<!-- <div class="kata-col-sm-6 activation-image">
				<img src="<?php // echo esc_url( Kata_Plus::$assets . 'images/admin/dashboard/theme-active-1.png', Kata_Plus_Helpers::ssl_url() ); ?>">
				<p class="activation-dec"><?php // echo esc_html__( 'Enter the Download menu by logging in to your dashboard, just like shown in the image.', 'kata-plus' ); ?></p>
			</div>
			<div class="kata-col-sm-6 activation-image">
				<img src="<?php // echo esc_url( Kata_Plus::$assets . 'images/admin/dashboard/theme-active-2.png', Kata_Plus_Helpers::ssl_url() ); ?>">
				<p class="activation-dec"><?php // echo esc_html__( 'Now, as you can see in the following tab, by clicking the Download button, easily download your purchase code. Just copy the purchase code, and paste it in the activation field at the top of the current page to activate the theme.y', 'kata-plus' ) ?></p>
			</div>-->
		</div>
	</div>
</div>

<?php
do_action( 'kata_plus_control_panel' );
