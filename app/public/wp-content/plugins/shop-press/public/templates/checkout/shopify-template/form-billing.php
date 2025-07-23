<?php
/**
 * Checkout billing information form
 *
 * @package ShopPress
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

$is_phone_number_field = sp_get_module_settings( 'shopify_checkout', 'phone_number_field' );
$is_company_name_field = sp_get_module_settings( 'shopify_checkout', 'company_name_field' );

?>

<div class="woocommerce-billing-fields">
	<h2 class="title-section"><?php esc_html_e( 'Contact information', 'shop-press' ); ?></h2>
	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );
		if ( array_key_exists( 'billing_email', $fields ) ) {
			\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_email', $fields['billing_email'], $checkout->get_value( 'billing_email' ) );
			// woocommerce_form_field( 'billing_email', $fields['billing_email'], $checkout->get_value( 'billing_email' ) );
		}
		?>
	</div>

	<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
		<h2 class="title-section"><?php esc_html_e( 'Billing &amp; Shipping', 'shop-press' ); ?></h2>
	<?php else : ?>
		<h2 class="title-section"><?php esc_html_e( 'Billing details', 'shop-press' ); ?></h2>
	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );
		?>
		<div class="fields-name-box">
			<?php
			if ( array_key_exists( 'billing_first_name', $fields ) ) {
				\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_first_name', $fields['billing_first_name'], $checkout->get_value( 'billing_first_name' ) );
			}
			?>
			<?php
			if ( array_key_exists( 'billing_last_name', $fields ) ) {
				\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_last_name', $fields['billing_last_name'], $checkout->get_value( 'billing_last_name' ) );
			}
			?>
		</div>

		<?php
		if ( array_key_exists( 'billing_company', $fields ) && $is_company_name_field ) {
			\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_company', $fields['billing_company'], $checkout->get_value( 'billing_company' ) );
		}

		if ( array_key_exists( 'billing_address_1', $fields ) ) {
			\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_address_1', $fields['billing_address_1'], $checkout->get_value( 'billing_address_1' ) );
		}

		if ( array_key_exists( 'billing_address_2', $fields ) ) {
			\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_address_2', $fields['billing_address_2'], $checkout->get_value( 'billing_address_2' ) );
		}

		if ( array_key_exists( 'billing_city', $fields ) ) {
			\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_city', $fields['billing_city'], $checkout->get_value( 'billing_city' ) );
		}
		?>

		<div class="fields-country-box">
			<?php
			if ( array_key_exists( 'billing_country', $fields ) ) {
				\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_country', $fields['billing_country'], $checkout->get_value( 'billing_country' ) );
			}
			?>
			<?php
			if ( array_key_exists( 'billing_state', $fields ) ) {
				\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_state', $fields['billing_state'], $checkout->get_value( 'billing_state' ) );
			}
			?>
			<?php
			if ( array_key_exists( 'billing_postcode', $fields ) ) {
				\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_postcode', $fields['billing_postcode'], $checkout->get_value( 'billing_postcode' ) );
			}
			?>
		</div>

		<?php

		if ( array_key_exists( 'billing_phone', $fields ) && $is_phone_number_field ) {
			\ShopPress\Modules\Shopify::woocommerce_form_field( 'billing_phone', $fields['billing_phone'], $checkout->get_value( 'billing_phone' ) );
		}
		foreach ( $fields as $key => $field ) {
			if ( $key == 'billing_email' || $key == 'billing_first_name' ||
				$key == 'billing_last_name' || $key == 'billing_company' ||
				$key == 'billing_country' || $key == 'billing_address_1' ||
				$key == 'billing_address_2' || $key == 'billing_city' ||
				$key == 'billing_state' || $key == 'billing_phone' || $key == 'billing_postcode' ) {
				continue;
			}
			\ShopPress\Modules\Shopify::woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
							id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?>
							type="checkbox" name="createaccount" value="1"/>
					<span><?php esc_html_e( 'Create an account?', 'shop-press' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php \ShopPress\Modules\Shopify::woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>
