<?php
/**
 * Fast mode Template 2
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . '/wp-admin/includes/translation-install.php';
$languages    = get_available_languages();
$translations = wp_get_available_translations();
$locale       = get_locale();
if ( ! in_array( $locale, $languages, true ) ) {
	$locale = '';
}
$current_offset = get_option( 'gmt_offset' );
$selected_zone  = get_option( 'timezone_string' );

$check_zone_info = true;
// Remove old Etc mappings. Fallback to gmt_offset.
if ( false !== strpos( $selected_zone, 'Etc/GMT' ) ) {
	$selected_zone = '';
}

if ( empty( $selected_zone ) ) { // Create a UTC+- zone if no timezone string exists.
	$check_zone_info = false;
	if ( 0 == $current_offset ) {
		$selected_zone = 'UTC+0';
	} elseif ( $current_offset < 0 ) {
		$selected_zone = 'UTC' . $current_offset;
	} else {
		$selected_zone = 'UTC+' . $current_offset;
	}
}

$blogname        = get_option( 'blogname' );
$blogdescription = get_option( 'blogdescription' );
$admin_email     = get_option( 'admin_email' );
$siteurl         = get_option( 'siteurl' );
$timezone_string = get_option( 'timezone_string' );
?>

<div id="kt-fst-mod-2" class="kt-fst-mod-wrapper">
	<h1 id="page-title" class="chose-bussiness-type"><?php echo esc_html__( 'Website General Information', 'kata-plus' ); ?></h1>
	<div class="kt-fst-mod-inner-wrapper">
		<div class="kt-fst-get-info-row">
			<div class="kt-fst-get-info">
				<label for="site-title"><?php echo esc_html__( 'Site Title', 'kata-plus' ); ?></label>
				<input type="text" id="site-title" value="<?php echo esc_attr( $blogname ); ?>">
			</div>
			<div class="kt-fst-get-info">
				<label for="site-tagline"><?php echo esc_html__( 'Site Tagline', 'kata-plus' ); ?></label>
				<input type="text" id="site-tagline" value="<?php echo esc_attr( $blogdescription ); ?>">
			</div>
		</div>
		<div class="kt-fst-get-info-row">
			<div class="kt-fst-get-info">
				<label for="site-address"><?php echo esc_html__( 'Site Address', 'kata-plus' ); ?></label>
				<input type="text" id="site-address" value="<?php echo esc_attr( $siteurl ); ?>" disabled style="background: #f7f8fa; cursor: no-drop;">
			</div>
			<div class="kt-fst-get-info">
				<label for="site-language"><?php echo esc_html__( 'Language', 'kata-plus' ); ?></label>
				<?php
					wp_dropdown_languages(
						array(
							'name'                        => 'WPLANG',
							'id'                          => 'WPLANG',
							'selected'                    => $locale,
							'languages'                   => $languages,
							'translations'                => $translations,
							'show_available_translations' => current_user_can( 'install_languages' ) && wp_can_install_language_pack(),
						)
					);
					?>
			</div>
		</div>
		<div class="kt-fst-get-info-row">
			<div class="kt-fst-get-info">
				<label for="admin-email"><?php echo esc_html__( 'Admin Email', 'kata-plus' ); ?></label>
				<input type="email" id="admin-email" value="<?php echo esc_attr( $admin_email ); ?>" data-valid="<?php echo esc_attr__( 'Please enter a valid email address', 'kata-plus' ); ?>">
			</div>
			<div class="kt-fst-get-info">
				<label for="timezone_string"><?php echo esc_html__( 'Timezone', 'kata-plus' ); ?></label>
				<select id="timezone_string" name="timezone_string" aria-describedby="timezone-description">
					<?php echo wp_timezone_choice( $selected_zone, get_user_locale() ); ?>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="kt-fst-mod-footer-area kt-fst-mod-2">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=3&websitetype=' . $_GET['websitetype'] . '&blogname=' . $blogname . '&blogdescription=' . $blogdescription . '&siteurl=' . $siteurl . '&admin-email=' . $admin_email . '&WPLANG=' . $locale . '&timezone_string=' . $timezone_string . '/' ) ); ?>" class="next-step">
		<?php echo esc_html__( 'Next', 'kata-plus' ) ?>
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_11" data-name="Path 11" d="M19.333,14.156a.5.5,0,0,0-.707.707l4.989,4.99a.494.494,0,0,0,.353.147l.01,0,.01,0a.491.491,0,0,0,.4-.212l4.946-4.945a.5.5,0,0,0-.707-.707L24.49,18.272V.5a.5.5,0,0,0-1,0V18.314Z" transform="translate(0 29.479) rotate(-90)" fill="#00d6f9"/>
			</svg>
		</i>
	</a>
</div>
