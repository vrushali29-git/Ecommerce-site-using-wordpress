<?php
/**
 * Rollback Page.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.3.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$request = wp_remote_get( 'https://katademos.climaxthemes.org/rollback/files.php' );

if ( is_wp_error( $request ) ) {
	echo '<div class="notice e-notice e-notice--error e-notice--dismissible">
			<i class="e-notice__dismiss" role="button" aria-label="Dismiss" tabindex="0"></i>
			<div class="e-notice__aside"></div>
			<div class="e-notice__content">
				<p><b>' . esc_html( $request->errors['http_request_failed'][0] ) . '</p>
			</div>
		</div>';

	return false;
}

$versions = json_decode( $request['body'], true );
?>

<div class="kata-admin kata-dashboard-page wrap about-wrap">
	<?php $this->header(); ?>
	<div class="kata-container">
		<div class="kata-row">
			<div class="kata-col-sm-12">
				<p><?php esc_html_e( 'Rollback to a previous version.', 'kata-plus' ); ?></p>
			</div>
			<div class="kata-col-sm-12">
				<div class="kata-select-version">
					<label for="rollback-version"><?php esc_html_e( 'Rollback Version', 'kata-plus' ); ?></label>
					<select name="version" id="rollback-version">
						<?php
						if ( is_array( $versions ) ) {
							foreach ( $versions as $slug => $get_version ) {

								foreach ( $get_version as $version ) {

									if ( $slug === 'kata-plus-pro' && ! class_exists( 'Kata_Plus_Pro' ) ) {
										continue;
									}

									if ( $slug === 'kata' ) {
										if ( version_compare( kata::$version, $version, '==' ) ) {
											continue;
										}
									}

									if ( $slug === 'kata-plus' ) {
										if ( version_compare( Kata_Plus::$version, $version, '==' ) ) {
											continue;
										}
									}

									if ( $slug === 'kata-plus-pro' ) {
										if ( version_compare( Kata_Plus_Pro::$version, $version, '==' ) ) {
											continue;
										}
									}

									$version = sanitize_text_field( $version );
									echo '<option value="' . esc_attr( $version ) . '" data-slug="' . esc_attr( $slug ) . '">' . esc_html( str_replace( '-', ' ', $slug ) . ' - ' . $version ) . '</option>';
								}
							}
						}
						?>
					</select>
					<button>
						<?php esc_html_e( 'Reinstall', 'kata-plus' ); ?>
						<div></div>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
do_action( 'kata_plus_control_panel' );
