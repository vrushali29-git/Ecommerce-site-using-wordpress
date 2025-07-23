<?php
/**
 * Fast mode Template 6
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */
use Elementor\Plugin;
$blog_url   = did_action( 'elementor/loaded' ) ? Plugin::$instance->documents->get( Kata_Plus_Blog_Builder::get_instance()->get_builder_id() )->get_edit_url() : '';
$header_url = did_action( 'elementor/loaded' ) ? Plugin::$instance->documents->get( Kata_Plus_Header_Builder::get_instance()->get_builder_id() )->get_edit_url() : '';
$footer_url = did_action( 'elementor/loaded' ) ? Plugin::$instance->documents->get( Kata_Plus_Footer_Builder::get_instance()->get_builder_id() )->get_edit_url() : '';
$header_url = did_action( 'elementor/loaded' ) ? Plugin::$instance->documents->get( Kata_Plus_Header_Builder::get_instance()->get_builder_id() )->get_edit_url() : '';

?>


<div id="kt-fst-mod-6" class="kt-fst-mod-wrapper">
	<h1 id="page-title" class="chose-bussiness-type">
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
				<path id="cwb-check-icon" d="M94,0a24,24,0,1,0,24,24A24,24,0,0,0,94,0Zm10.359,13.359,3.282,3-18,19.922L80.359,27,83.5,23.858l5.859,5.859,15-16.359Z" transform="translate(-70)" fill="#59ce88"></path>
			</svg>
		</i>
		<?php echo esc_html__( 'Your website is ready to launch', 'kata-plus' ); ?>
	</h1>
	<div class="site-ready-wrapper">
		<div class="col">
			<img src="<?php echo esc_url( Kata_plus::$assets . 'images/admin/dashboard/kt-fst-launch-ico.svg' ); ?>" alt="Launch website" style="margin-bottom: 6px;">
			<h3><?php echo esc_html__( 'Launch the website right now.', 'kata-plus' ); ?></h3>
			<p><?php echo esc_html__( 'You can now see the website with the default content and you can edit it at any time.', 'kata-plus' ); ?></p>
			<a href="<?php echo esc_url( home_url() ); ?>"><?php echo esc_html__( 'Visit Site', 'kata-plus' ); ?></a>
		</div>
		<div class="col">
			<img src="<?php echo esc_url( Kata_plus::$assets . 'images/admin/dashboard/kt-fst-edit-ico.svg' ); ?>" alt="Edit website’s" style="margin-bottom: 6px;">
			<h3><?php echo esc_html__( 'Edit website’s content and design.', 'kata-plus' ); ?></h3>
			<p><?php echo esc_html__( 'You can easily customize the content and design of the website using the visual editor.', 'kata-plus' ); ?></p>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=7' ) ); ?>"><?php echo esc_html__( 'Edit Site', 'kata-plus' ); ?></a>
		</div>
	</div>
</div>

<div class="kt-fst-mod-footer-area kt-fst-mod-6">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-fast-mode&step=5' ) ); ?>" class="prev-step">
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_12" data-name="Path 12" d="M.854,5.844a.5.5,0,0,1-.707-.707L5.136.147A.494.494,0,0,1,5.489,0L5.5,0l.01,0a.491.491,0,0,1,.4.212l4.946,4.945a.5.5,0,0,1-.707.707L6.01,1.728V19.5a.5.5,0,0,1-1,0V1.686Z" transform="translate(0 11) rotate(-90)" fill="#adb8b8"/>
			</svg>
		</i>
		<?php echo esc_html__( 'Back', 'kata-plus' ); ?>
	</a>
	<a href="<?php echo esc_url( home_url() ); ?>" class="next-step">
		<?php echo esc_html__( 'Visit Site', 'kata-plus' ); ?>
		<i class="kata-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="11" viewBox="0 0 20 11">
				<path id="Path_11" data-name="Path 11" d="M19.333,14.156a.5.5,0,0,0-.707.707l4.989,4.99a.494.494,0,0,0,.353.147l.01,0,.01,0a.491.491,0,0,0,.4-.212l4.946-4.945a.5.5,0,0,0-.707-.707L24.49,18.272V.5a.5.5,0,0,0-1,0V18.314Z" transform="translate(0 29.479) rotate(-90)" fill="#00d6f9"/>
			</svg>
		</i>
	</a>
</div>
