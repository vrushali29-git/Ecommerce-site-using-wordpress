<?php
/**
 * Layout Options.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;

if ( ! class_exists( 'Kata_Plus_Theme_Options_Footer' ) ) {
	class Kata_Plus_Theme_Options_Footer extends Kata_Plus_Theme_Options {
		/**
		 * Set Options.
		 *
		 * @since   1.0.0
		 */
		public static function set_options() {
			Kata_Plus_Autoloader::load( Kata_Plus::$dir . 'includes/elementor/builders', '01-builder-base' );

			// Footer section
			new \Kirki\Section(
				'kata_footer_section',
				array(
					'title'      => esc_html__( 'Footer', 'kata-plus' ),
					'icon'       => 'ti-layout-accordion-merged',
					'capability' => Kata_Plus_Helpers::capability(),
					'priority'   => 7,

				)
			);
			$footer_url = class_exists( 'Kata_Plus_Builders_Base' ) ? Kata_Plus_Builders_Base::get_instance()->builder_url( 'kata_footer' ) : '';
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings'    => 'kata_footer_builder',
					'section'     => 'kata_footer_section',
					'type'        => 'custom',
					'label'       => esc_html__( 'Footer Builder', 'kata-plus' ),
					'description' => esc_html__( 'Click on the button below to customize the Footer\'s layout', 'kata-plus' ),
					'default'     => '
					<a href="' . esc_url( $footer_url ) . '" id="elementor-switch-mode-button" class="button customizer-kata-builder-button button-large">
						<span class="elementor-switch-mode-off"><i class="eicon-elementor-square" aria-hidden="true"></i> Go to Footer Builder</span>
					</a>',
				)
			);
		}
	} // class

	Kata_Plus_Theme_Options_Footer::set_options();
}
