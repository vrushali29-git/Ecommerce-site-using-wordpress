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

if ( ! class_exists( 'Kata_Plus_Theme_Options_Header' ) ) {
	class Kata_Plus_Theme_Options_Header extends Kata_Plus_Theme_Options {
		/**
		 * Set Options.
		 *
		 * @since   1.0.0
		 */
		public static function set_options() {
			Kata_Plus_Autoloader::load( Kata_Plus::$dir . 'includes/elementor/builders', '01-builder-base' );

			// Header Section
			new \Kirki\Section(
				'kata_header_section',
				array(
					'icon'       => 'ti-layout-tab-window',
					'title'      => esc_html__( 'Header', 'kata-plus' ),
					'capability' => Kata_Plus_Helpers::capability(),
					'priority'   => 3,

				)
			);
			$header_url = class_exists( 'Kata_Plus_Builders_Base' ) ? Kata_Plus_Builders_Base::get_instance()->builder_url( 'kata_header' ) : '';
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'kata_header_builder',
					'section'  => 'kata_header_section',
					'type'     => 'custom',
					'label'    => esc_html__( 'Header Builder', 'kata-plus' ),
					'default'  => '
					<a href="' . esc_url( $header_url ) . '" id="elementor-switch-mode-button" class="button customizer-kata-builder-button button-large">
						<span class="elementor-switch-mode-off"><i class="eicon-elementor-square" aria-hidden="true"></i> Go to Header Builder</span>
					</a>',
				)
			);
		}
	} // class

	Kata_Plus_Theme_Options_Header::set_options();
}
