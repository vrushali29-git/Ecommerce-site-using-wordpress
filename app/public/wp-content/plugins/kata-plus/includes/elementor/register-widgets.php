<?php
/**
 * Register Widgets.
 *
 * @package KataPlus
 */

defined( 'ABSPATH' ) || exit;

class Kata_Plus_Register_Widgets {
	/**
	 * Init Register Widgets.
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
		add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widgets' ) );
	}

	/**
	 * Retrieves the list of widgets.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	private static function get_widgets_list() {
		$widgets = array(
			'1-text'              => array(
				'class_name' => 'Kata_Plus_Text',
				'is_pro'     => false,
			),
			'2-title'             => array(
				'class_name' => 'Kata_Plus_Title',
				'is_pro'     => false,
			),
			'3-image'             => array(
				'class_name' => 'Kata_Plus_Image',
				'is_pro'     => false,
			),
			'4-button'            => array(
				'class_name' => 'Kata_Plus_Button',
				'is_pro'     => false,
			),
			'5-icon-box'          => array(
				'class_name' => 'Kata_Plus_IconBox',
				'is_pro'     => false,
			),
			'6-spacer'            => array(
				'class_name' => 'Kata_Plus_Spacer',
				'is_pro'     => false,
			),
			'7-icon'              => array(
				'class_name' => 'Kata_Plus_Icon',
				'is_pro'     => false,
			),
			'8-video-player'      => array(
				'class_name' => 'Kata_Plus_Video_Player',
				'is_pro'     => false,
			),
			'9-list'              => array(
				'class_name' => 'Kata_Plus_List',
				'is_pro'     => false,
			),
			'10-testimonials'     => array(
				'class_name' => 'Kata_Plus_Testimonials',
				'is_pro'     => false,
			),
			'11-shape'            => array(
				'class_name' => 'Kata_Shape',
				'is_pro'     => false,
			),
			'accordion-toggle'    => array(
				'class_name' => 'Kata_Accordion_Toggle',
				'is_pro'     => false,
			),
			'address'             => array(
				'class_name' => 'Kata_Plus_Address',
				'is_pro'     => false,
			),
			'author-box'          => array(
				'class_name' => 'Kata_Plus_Author_Box',
				'is_pro'     => false,
			),
			'banner'              => array(
				'class_name' => 'Kata_Plus_Banner',
				'is_pro'     => false,
			),
			'grid'                   => array(
				'class_name' => 'Kata_Plus_Grid',
				'is_pro'     => false,
			),
			'socials'                => array(
				'class_name' => 'Kata_Plus_Socials',
				'is_pro'     => false,
			),
			'blog-posts'          => array(
				'class_name' => 'Kata_Plus_Blog_Posts',
				'is_pro'     => false,
			),
			'brands'              => array(
				'class_name' => 'Kata_Plus_Brands',
				'is_pro'     => false,
			),
			'contact-form'        => array(
				'class_name' => 'Kata_Plus_ContactForm',
				'is_pro'     => false,
			),
			'counter'             => array(
				'class_name' => 'Kata_Plus_Counter',
				'is_pro'     => false,
			),
			'email'               => array(
				'class_name' => 'Kata_Plus_Email',
				'is_pro'     => false,
			),
			'gallery'             => array(
				'class_name' => 'Kata_Plus_Gallery',
				'is_pro'     => false,
			),
			'logo'                => array(
				'class_name' => 'Kata_Plus_Logo',
				'is_pro'     => false,
			),
			'menu'                => array(
				'class_name' => 'Kata_Plus_Menu_Navigation',
				'is_pro'     => false,
			),
			'phone'               => array(
				'class_name' => 'Kata_Plus_Phone',
				'is_pro'     => false,
			),
			'post-comments'       => array(
				'class_name' => 'Kata_Plus_Post_Comments',
				'is_pro'     => false,
			),
			'post-content'        => array(
				'class_name' => 'Kata_Plus_Post_Content',
				'is_pro'     => false,
			),
			'post-excerpt'        => array(
				'class_name' => 'Kata_Plus_Post_Excerpt',
				'is_pro'     => false,
			),
			'post-featured-image' => array(
				'class_name' => 'Kata_Plus_Post_Featured_Image',
				'is_pro'     => false,
			),
			'post-metadata'       => array(
				'class_name' => 'Kata_Plus_Post_Metadata',
				'is_pro'     => false,
			),
			'post-title'          => array(
				'class_name' => 'Kata_Plus_Post_Title',
				'is_pro'     => false,
			),
			'recent-posts'        => array(
				'class_name' => 'Kata_Plus_Recent_Posts',
				'is_pro'     => false,
			),
			'search'              => array(
				'class_name' => 'Kata_Plus_Search',
				'is_pro'     => false,
			),
			'single-testimonial'  => array(
				'class_name' => 'Kata_Plus_Single_Testimonial',
				'is_pro'     => false,
			),
			'social-share'        => array(
				'class_name' => 'Kata_Social_Share',
				'is_pro'     => false,
			),
			'subscribe'           => array(
				'class_name' => 'Kata_Plus_Subscribe',
				'is_pro'     => false,
			),
		);

		$widgets = apply_filters( 'kata_plus/elementor/widgets', $widgets );

		return $widgets;
	}

	/**
	 * Checks if the deps plugin of the widget is active.
	 *
	 * @since 1.3.0
	 *
	 * @param string $plugin_slug
	 *
	 * @return bool
	 */
	private static function is_deps_plugin_active( $plugin_slug ) {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( $plugin_slug );
	}

	/**
	 * Register Widgets
	 *
	 * @since 1.3.0
	 *
	 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
	 *
	 * @return void
	 */
	public static function register_widgets( $widgets_manager ) {
		$widgets_list = self::get_widgets_list();

		foreach ( $widgets_list as $key => $widget ) {

			if ( isset( $widget['plugin'] ) && false === self::is_deps_plugin_active( $widget['plugin'] ) ) {
				continue;
			}

			if ( isset( $widget['deps_classes'] ) ) {

				foreach ( $widget['deps_classes'] as $class ) {

					Kata_Plus_Autoloader::load( $class['path'], $class['file_name'] );
				}
			}

			$plugin_path = ( true === $widget['is_pro'] && class_exists( 'Kata_Plus_Pro' ) ) ? Kata_Plus_Pro::$dir : Kata_Plus::$dir;
			$custom_path = $widget['custom_path'] ?? false;
			$widget_file = $custom_path ? $custom_path : $plugin_path . 'includes/elementor/modules/' . $key . '/config.php';
			if ( file_exists( $widget_file ) ) {
				require_once $widget_file;
			}

			$class_name = $widget['class_name'];
			if ( class_exists( $class_name ) ) {
				$widgets_manager->register( new $class_name() );
			}
		}
	}
}
