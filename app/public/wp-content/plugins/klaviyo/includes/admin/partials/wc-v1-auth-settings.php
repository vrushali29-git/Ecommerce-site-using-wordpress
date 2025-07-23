<?php
/**
 * WooCommerceKlaviyo Oauth Partial.
 *
 * @package   WooCommerceKlaviyo
 * @version   2.0.0
 */

?>
<div class="wck-settings" xmlns="http://www.w3.org/1999/html">
	<div class="wck-content-wrapper">
		<div class="wck-content">
			<div class="wck-logo">
				<img src="<?php echo esc_url( KLAVIYO_URL ); ?>includes/admin/assets/wck-logo.svg">
			</div>
			<div class="wck-connect-wrapper">
				<?php
				$wck_conflicting_plugins = $this->get_conflicting_plugins();
				if ( $wck_conflicting_plugins ) {
					?>
					<div class="wck-notification-wrapper" >
						<div class="wck-notification wck-warning" >
							<div class="wck-notification-icon-frame">
								<img src="<?php echo esc_url( KLAVIYO_URL ); ?>includes/admin/assets/warning-icon.svg">
							</div>
							<div class="wck-notification-text-frame">
								<span class="wck-notification-copy">To avoid functionality disruptions, disable the following plugins: <?php echo esc_html($this->format_conflicting_plugins($wck_conflicting_plugins)); ?>.</span>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if ( $this->display_ssl_error_notification() ) { ?>
					<div class="wck-notification-wrapper <?php echo $wck_conflicting_plugins ?  'second-notification' : ''; ?>" >
						<div class="wck-notification wck-error" >
							<div class="wck-notification-icon-frame">
								<img src="<?php echo esc_url( KLAVIYO_URL ); ?>includes/admin/assets/error-icon.svg">
							</div>
							<div class="wck-notification-text-frame">
								<span class="wck-notification-copy">Ensure your WooCommerce store URL has https and SSL enabled.<br/><a target="_blank" href="https://help.klaviyo.com/hc/en-us/articles/4515829067803#h_01HQBY3VJZHKRFK4MF12HQZBF0">Learn more<img class="wck-outbound-icon" src="<?php echo esc_url( KLAVIYO_URL ); ?>includes/admin/assets/outbound.svg"></img></a></span>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="wck-content-subtitles">
					<?php if ( $this->is_integrated() ) { ?>
						<span class="wck-content-title">Your Klaviyo + WooCommerce integration is connected!</span>
						<span class="wck-content-subtitle">Head back to the Klaviyo dashboard to continue with next steps for getting your account up and running or to modify any of your Klaviyo + WooCommerce integration settings.</a> </span>
						<div class="connect-buttons">
							<fieldset class="connect-button">
								<a id="wck_manage_settings" class="button button-primary" href="https://www.klaviyo.com/integration/woocommerce" target="_blank">Go to your WooCommerce settings</a>
							</fieldset>
						</div>
					<?php } else { ?>
						<span class="wck-content-title">Connect your Klaviyo account to use the Klaviyo + WooCommerce integration.</span>
						<span class="wck-content-subtitle">Build custom segments, send automations, and track purchase activity in Klaviyo. Log in to authorize an account connection. New to Klaviyo and want to learn more? Check out our <a class="subtitle-guide-link" href="https://help.klaviyo.com/hc/en-us/articles/115005255808-How-to-Integrate-with-WooCommerce" target="_blank">How to Integrate with WooCommerce guide.</a> </span>
						<div class="connect-buttons">
							<fieldset class="connect-button">
								<a id="wck_oauth_connect" class="button button-primary <?php echo is_ssl() ?  '' : 'wck-disabled'; ?>" <?php echo is_ssl() ?  'href="' . esc_url('https://www.klaviyo.com/integration-oauth-one/woocommerce/auth/handle?url=' . get_home_url()) . '"' : ''; ?>>Connect Account</a>
								<a id="wck_create_account" class="button create-account <?php echo is_ssl() ?  '' : 'wck-disabled'; ?>" <?php echo is_ssl() ?  'href="' . esc_url('https://www.klaviyo.com/signup/woocommerce?utm_medium=app-store&utm_source=platform-partner&utm_campaign=woocomm-launch') . '"' : ''; ?> target="_blank">Create Account</a>
							</fieldset>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
