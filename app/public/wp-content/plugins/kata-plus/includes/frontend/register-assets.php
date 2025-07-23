<?php
/**
 * Plugin Assets.
 *
 * @package KataPlus
 */

defined( 'ABSPATH' ) || exit;

class Kata_Plus_Register_Assets {
	/**
	 * Init.
	 *
	 * @since 1.3.0
	 */
	public static function init() {
		self::hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.3.0
	 */
	private static function hooks() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_scripts' ) );
	}

	/**
	 * Returns an array of scripts with their URLs.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	private static function get_scripts_list() {
		$assets = Kata_Plus::$assets;

		$scripts = array(
			'kata-plus-owl'                        => $assets . 'js/frontend/kata-owl.js',
			'kata-plus-owlcarousel'                => $assets . 'js/libraries/owlcarousel.js',
			'kata-plus-owlcarousel-thumbs'         => $assets . 'js/libraries/owl.carousel2.thumbs.min.js',
			'kata-plus-lightgallery'               => $assets . 'js/libraries/lightgallery.js',
			'kata-plus-video-player'               => $assets . 'js/frontend/video-player.js',
			'kata-plus-accordion-toggle'           => $assets . 'js/frontend/accordion-toggle.js',
			'zilla-likes'                          => $assets . 'js/libraries/zilla-likes.js',
			'kata-blog-posts'                      => $assets . 'js/frontend/kata-blog-posts.js',
			'kata-plus-contact-form'               => $assets . 'js/frontend/contact-form.js',
			'nice-select'                          => $assets . 'js/libraries/jquery.nice-select.js',
			'kata-plus-comments'                   => $assets . 'js/frontend/comments.js',
			'kata-social-share'                    => $assets . 'js/frontend/social-share.js',
			'kata-plus-search'                     => $assets . 'js/frontend/search.js',
			'superfish'                            => $assets . 'js/libraries/superfish.js',
			'kata-plus-menu-navigation'            => $assets . 'js/frontend/menu-navigation.js',
			'kata-plus-content-toggle'             => $assets . 'js/frontend/content-toggle.js',
			'kata-plus-book-table'                 => $assets . 'js/frontend/book-table.js',
			'kata-plus-datepicker-config'          => $assets . 'js/frontend/datepicker.config.js',
			'kata-plus-flatpickr'                  => $assets . 'js/libraries/flatpickr.min.js',
			'kata-plus-audio-player'               => $assets . 'js/frontend/audio-player.js',
			'kata-plus-owl-owlcarousel2-filter-js' => $assets . 'js/libraries/owlcarousel2-filter.min.js',
			'kata-plus-carousel-grid-js'           => $assets . 'js/frontend/carousel-grid.js',
			'kata-plus-cart'                       => $assets . 'js/frontend/cart.js',
			'kata-plus-juxtapose'                  => $assets . 'js/libraries/juxtapose.js',
			'kata-plus-comparison-slider'          => $assets . 'js/frontend/comparison-slider.js',
			'kata-plus-dialog'                     => $assets . 'js/libraries/dialog.js',
			'kata-plus-contact-toggle'             => $assets . 'js/frontend/contact-toggle.js',
			'jquery-countdown'                     => $assets . 'js/libraries/jquery-countdown.js',
			'kata-plus-countdown'                  => $assets . 'js/frontend/countdown.js',
			'kata-plus-food-menu'                  => $assets . 'js/frontend/food-menu.js',
			'kata-plus-food-menu-toggle'           => $assets . 'js/frontend/food-menu-toggle.js',
			'kata-gift-card'                       => $assets . 'js/frontend/gift-card.js',
			'kata-plus-grid-js'                    => $assets . 'js/frontend/grid.js',
			'kata-plus-hamburger-menu'             => $assets . 'js/frontend/hamburger-menu.js',
			'kata-image-carousel'                  => $assets . 'js/frontend/image-carousel.js',
			'jquery-zoom'                          => $assets . 'js/libraries/jquery.zoom.min.js',
			'kata-plus-img-hover-zoom'             => $assets . 'js/frontend/img-hover-zoom.js',
			'kata-plus-language'                   => $assets . 'js/frontend/language-switcher.js',
			'kata-plus-login'                      => $assets . 'js/frontend/login.js',
			'kata-plus-masonry-grid-js'            => $assets . 'js/frontend/masonry-grid.js',
			'kata-plus-pricing-table'              => $assets . 'js/frontend/pricing-table.js',
			'kata-plus-progress-bar'               => $assets . 'js/frontend/progress-bar.js',
			'kata-plus-seo-analytic'               => $assets . 'js/frontend/seo-analytic.js',
			'kata-plus-sticky-box'                 => $assets . 'js/frontend/sticky-box.js',
			'kata-plus-tabs'                       => $assets . 'js/frontend/tabs.js',
			'kata-plus-tilt'                       => $assets . 'js/libraries/tilt.js',
			'kata-plus-team-member'                => $assets . 'js/frontend/team-member.js',
			'kata-plus-template-loader'            => $assets . 'js/frontend/template-loader.js',
			'kata-plus-testimonials-vertical'      => $assets . 'js/frontend/testimonials-vertical.js',
			'kata-plus-toggle-sidebox'             => $assets . 'js/frontend/toggle-sidebox.js',
		);

		return apply_filters( 'kata_plus/register_scripts', $scripts );
	}

	/**
	 * Returns an array of styles with their URLs.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	private static function get_styles_list() {
		$assets = Kata_Plus::$assets;

		$styles = array(
			'kata-plus-owl'                   => $assets . 'css/frontend/kata-owl.css',
			'kata-plus-owlcarousel'           => $assets . 'css/libraries/owlcarousel.css',
			'kata-plus-testimonials'          => $assets . 'css/frontend/testimonials.css',
			'kata-plus-shape'                 => $assets . 'css/frontend/shape.css',
			'kata-plus-title'                 => $assets . 'css/frontend/title.css',
			'kata-plus-button'                => $assets . 'css/frontend/button.css',
			'kata-plus-icon-box'              => $assets . 'css/frontend/icon-box.css',
			'kata-plus-video-player'          => $assets . 'css/frontend/video-player.css',
			'kata-plus-lightgallery'          => $assets . 'css/libraries/lightgallery.css',
			'kata-plus-list'                  => $assets . 'css/frontend/list.css',
			'kata-plus-accordion-toggle'      => $assets . 'css/frontend/accordion-toggle.css',
			'kata-plus-author-page'           => $assets . 'css/frontend/author-page.css',
			'kata-plus-banner'                => $assets . 'css/frontend/banner.css',
			'kata-plus-blog-posts'            => $assets . 'css/frontend/blog-posts.css',
			'zilla-likes'                     => $assets . 'css/libraries/zilla-likes.css',
			'kata-plus-brands'                => $assets . 'css/frontend/brands.css',
			'kata-plus-contact-form'          => $assets . 'css/frontend/contact-form.css',
			'nice-select'                     => $assets . 'css/libraries/nice-select.css',
			'kata-plus-counter'               => $assets . 'css/frontend/counter.css',
			'kata-plus-gallery'               => $assets . 'css/frontend/gallery.css',
			'kata-plus-subscribe'             => $assets . 'css/frontend/subscribe.css',
			'kata-plus-social-share'          => $assets . 'css/frontend/social-share.css',
			'kata-plus-search'                => $assets . 'css/frontend/search.css',
			'kata-plus-menu-navigation'       => $assets . 'css/frontend/menu-navigation.css',
			'kata-plus-content-toggle'        => $assets . 'css/frontend/content-toggle.css',
			'kata-plus-book-table'            => $assets . 'css/frontend/book-table.css',
			'kata-plus-datepicker'            => $assets . 'css/libraries/datepicker.css',
			'kata-plus-flatpickr'             => $assets . 'css/libraries/flatpickr.min.css',
			'kata-plus-audio-player'          => $assets . 'css/frontend/audio-player.css',
			'kata-plus-carousel-grid-css'     => $assets . 'css/frontend/carousel-grid.css',
			'kata-plus-cart'                  => $assets . 'css/frontend/cart.css',
			'kata-plus-juxtapose'             => $assets . 'css/libraries/juxtapose.css',
			'kata-plus-comparison-slider-css' => $assets . 'css/frontend/comparison-slider.css',
			'kata-plus-dialog'                => $assets . 'css/libraries/dialog.css',
			'kata-plus-contact-toggle'        => $assets . 'css/frontend/contact-toggle.css',
			'kata-plus-contact-form'          => $assets . 'css/frontend/contact-form.css',
			'kata-plus-countdown'             => $assets . 'css/frontend/countdown.css',
			'kata-plus-courses'               => $assets . 'css/frontend/courses.css',
			'kata-plus-food-menu'             => $assets . 'css/frontend/food-menu.css',
			'kata-plus-food-menu-toggle'      => $assets . 'css/frontend/food-menu-toggle.css',
			'kata-plus-gift-cards'            => $assets . 'css/frontend/gift-cards.css',
			'kata-plus-grid'                  => $assets . 'css/frontend/grid.css',
			'kata-plus-hamburger-menu'        => $assets . 'css/frontend/hamburger-menu.css',
			'kata-image-carousel'             => $assets . 'css/frontend/image-carousel.css',
			'kata-plus-img-hover-zoom'        => $assets . 'css/frontend/img-hover-zoom.css',
			'kata-plus-instagram'             => $assets . 'css/frontend/instagram.css',
			'kata-plus-language-switcher'     => $assets . 'css/frontend/language-switcher.css',
			'kata-plus-login'                 => $assets . 'css/frontend/login.css',
			'kata-plus-masonry-grid-css'      => $assets . 'css/frontend/masonry-grid.css',
			'kata-plus-pricing-table'         => $assets . 'css/frontend/pricing-table.css',
			'kata-plus-progress-bar'          => $assets . 'css/frontend/progress-bar.css',
			'kata-plus-seo-analytic'          => $assets . 'css/frontend/seo-analytic.css',
			'kata-plus-sticky-box'            => $assets . 'css/frontend/sticky-box.css',
			'kata-plus-tabs'                  => $assets . 'css/frontend/tabs.css',
			'kata-plus-task-process'          => $assets . 'css/frontend/task-process.css',
			'kata-plus-team-member'           => $assets . 'css/frontend/team-member.css',
			'kata-plus-testimonials-vertical' => $assets . 'css/frontend/testimonials-vertical.css',
			'kata-plus-fullpage'              => $assets . 'css/libraries/fullpage.css',
			'kata-plus-toggle-sidebox'        => $assets . 'css/frontend/toggle-sidebox.css',
			'kata-plus-categories-list'       => $assets . 'css/frontend/categories-list.css',
			'kata-plus-course-teacher'        => $assets . 'css/frontend/course-teacher.css',
			'kata-plus-date'                  => $assets . 'css/frontend/date.css',
			'kata-plus-divider'               => $assets . 'css/frontend/divider.css',
			'kata-plus-employee'              => $assets . 'css/frontend/employee-information.css',
			'kata-plus-google-map'            => $assets . 'css/frontend/google-map.css',
			'kata-plus-image-hotspot'         => $assets . 'css/frontend/hotspot.css',
			'kata-plus-infinite-image-scroll' => $assets . 'css/frontend/infinite-image-scroll.css',
			'kata-plus-next-previous-post'    => $assets . 'css/frontend/next-previous-post.css',
			'kata-plus-pricing-plan'          => $assets . 'css/frontend/pricing-plan.css',
			'kata-plus-recipes'               => $assets . 'css/frontend/recipes.css',
			'kata-plus-socials'               => $assets . 'css/frontend/socials.css',
			'kata-plus-timeline'              => $assets . 'css/frontend/timeline.css',
		);

		return apply_filters( 'kata_plus/register_styles', $styles );
	}

	/**
	 * Register Scripts
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public static function register_scripts() {
		$scripts = self::get_scripts_list();
		$styles  = self::get_styles_list();
		$version = Kata_Plus::$version;

		foreach ( $scripts as $handle => $script ) {
			wp_register_script( $handle, $script, array( 'jquery' ), $version, true );
		}

		foreach ( $styles as $handle => $style ) {
			wp_register_style( $handle, $style, array(), $version );
		}
	}
}
