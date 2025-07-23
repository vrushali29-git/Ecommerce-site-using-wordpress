<?php

/**
 * Admin Help Page.
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
		<!-- FAQ -->
		<div class="kata-row">
			<div class="kata-help-intro">
				<p><?php echo esc_html__( 'With a simple search in Helpdesk or chatting with our online chat bot you can find the answer to your questions even faster. In case you did not find what you were looking for, our support team will quickly respond to you in the same chat.', 'kata-plus' ); ?></p>
				<a href="https://climaxthemes.com/kata/documentation/" class="help-links" target="_blank"><i class="ti-help-alt"></i><?php echo esc_html__( 'Search Helpdesk', 'kata-plus' ); ?></a>
				<a href="#" class="help-links chat-link"><i class="ti-headphone-alt"></i><?php echo esc_html__( 'Start Chat', 'kata-plus' ); ?></a>
			</div>
		</div>
		<div class="kata-row">
			<!-- System Status card -->
			<div class="kata-col-sm-8">
				<?php require_once self::$dir . 'views/_partials/system-status.php'; ?>
			</div>
			<div class="kata-col-sm-4">
				<!-- FAQ card -->
				<div class="kata-card kata-success faq-wrapper">
					<div class="kata-card-header">
						<h3><?php esc_html_e( 'Documentation', 'kata-plus' ); ?></h3>
					</div>
					<div class="kata-card-body">
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/covering-letter/"><?php echo esc_html__( 'kata Overview', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/demo-importer/"><?php echo esc_html__( 'Demo importer', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/styler/"><?php echo esc_html__( 'What is styler?', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/page-builder/"><?php echo esc_html__( 'Kata page builder & features', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/header-builder/"><?php echo esc_html__( 'Edit header using header builder', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/footer-builder/"><?php echo esc_html__( 'Edit footer using using footer builder', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/blog-builder/"><?php echo esc_html__( 'Edit blog & Sidebar', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/archive-builder/"><?php echo esc_html__( 'Edit archive & Sidebar', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/single-builder/"><?php echo esc_html__( 'Edit single posts & Sidebar', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/customizer-theme-option/"><?php echo esc_html__( 'Theme Options', 'kata-plus' ); ?></a></h4>
						</div>
						<div class="faq-item">
							<h4 class="faq-que"><a href="https://climaxthemes.com/kata/documentation/"><?php echo esc_html__( 'Documentation', 'kata-plus' ); ?></a></h4>
						</div>
					</div>
				</div>
			</div> <!-- end .kata-col-sm-6 -->
		</div> <!-- end .kata-row -->

		<!-- Change Log card -->
		<div class="kata-row">
			<div class="kata-col-sm-12">
				<div class="kata-card change-log">
					<div class="kata-card-header">
						<h3 class="changelog-title"><?php esc_html_e( 'Changelog', 'kata-plus' ); ?></h3>
					</div>
					<div class="kata-card-body changes">
						<?php require_once self::$dir . 'views/_partials/change-log.php'; ?>
					</div>
				</div>
			</div> <!-- end .kata-col-sm-12 -->
		</div> <!-- end .kata-row -->

	</div> <!-- end .container -->
</div> <!-- end .kata-admin -->
<?php
do_action( 'kata_plus_control_panel' );
