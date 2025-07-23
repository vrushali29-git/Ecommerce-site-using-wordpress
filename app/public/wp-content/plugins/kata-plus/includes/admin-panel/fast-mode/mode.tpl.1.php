<?php
/**
 * Fast mode Template 1.
 * Choose Website Type.
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
<div id="kt-fst-mod-1" class="kt-fst-mod-wrapper">
	<h1 id="page-title" class="chose-bussiness-type"><?php echo esc_html__( 'What is the type of website you want to create?', 'kata-plus' ); ?></h1>
	<div class="kt-fst-mod-inner-wrapper">
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=business' ) ); ?>" target="_blank"><?php echo esc_html__( 'Business', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=business' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=portfolio' ) ); ?>" target="_blank"><?php echo esc_html__( 'Portfolio', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=portfolio' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=blog' ) ); ?>" target="_blank"><?php echo esc_html__( 'Blog', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=blog' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=food' ) ); ?>" target="_blank"><?php echo esc_html__( 'Food', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=food' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=software' ) ); ?>" target="_blank"><?php echo esc_html__( 'Software', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=software' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=health' ) ); ?>" target="_blank"><?php echo esc_html__( 'Health', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=health' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=design' ) ); ?>" target="_blank"><?php echo esc_html__( 'Design', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=design' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=online-store' ) ); ?>" target="_blank"><?php echo esc_html__( 'Online Store', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=online-store' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
		<div class="website-type">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=personal' ) ); ?>" target="_blank"><?php echo esc_html__( 'Personal', 'kata-plus' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=2&websitetype=personal' ) ); ?>" target="_blank" class="fast-wrap-link"></a>
		</div>
	</div>
</div>
