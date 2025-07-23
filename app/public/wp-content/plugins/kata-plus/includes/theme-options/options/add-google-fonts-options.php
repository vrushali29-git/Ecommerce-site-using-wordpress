<?php

/**
 * Styling & Typography Options.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kata_Plus_Theme_Options_Add_Google_Fonts_Typography' ) ) {
	class Kata_Plus_Theme_Options_Add_Google_Fonts_Typography extends Kata_Plus_Theme_Options {
		/**
		 * Set Options.
		 *
		 * @since   1.0.0
		 */
		public static function set_options() {
			// -> Start Section
			new \Kirki\Section(
				'kata_add_google_fonts_section',
				array(
					'title'      => esc_html__( 'Font Manager', 'kata-plus' ),
					'capability' => 'manage_options',
					'priority'   => 7,
					'icon'       => 'ti-uppercase',
				)
			);
			new \Kirki\Field\Repeater(
				array(
					'section'      => 'kata_add_google_fonts_section',
					'settings'     => 'kata_add_google_font_repeater',
					'type'         => 'repeater',
					'label'        => esc_html__( 'Add Font', 'kata-plus' ),
					'priority'     => 10,
					'button_label' => esc_html__( 'ADD NEW', 'kata-plus' ),
					'row_label'    => array(
						'type'  => 'field',
						'value' => esc_html__( 'Google Font', 'kata-plus' ),
						'field' => 'kata_google_fonts',
					),
					'default'      => '',
					'fields'       => array(
						'fonts'    => array(
							'section'  => 'kata_add_google_fonts_section',
							'settings' => 'kata_google_fonts',
							'type'     => 'select',
							'label'    => esc_html__( 'Select Font', 'kata-plus' ),
							'default'  => 'select-font',
							'choices'  => self::get_google_fonts(),
						),
						'varients' => array(
							'section'     => 'kata_add_google_fonts_section',
							'settings'    => 'kata_google_fonts_varients',
							'type'        => 'text',
							'default'     => '300,400,regular,400italic,500',
							'label'       => esc_html__( 'Weights', 'kata-plus' ),
							'description' => esc_html__( 'Fill the field with your desired font weights', 'kata-plus' ),
						),
					),
				)
			);
		}

		/**
		 * Get Google fonts
		 *
		 * @since 1.0.0
		 */
		public static function get_google_fonts() {

			$path         = Kata_Plus::$upload_dir . '/google-fonts/';
			$google_fonts = file_exists( $path . '\settings.json' ) ? file_get_contents( $path . '\settings.json' ) : '';
			if ( ! $google_fonts ) {
				$response      = wp_safe_remote_get(
					'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyCh-hO93LNoX1xatkeNtoxJI1NQAOBcOb4',
					array(
						'timeout'    => 30,
						'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
					)
				);
				$response_code = wp_remote_retrieve_response_code( $response );
				if ( ! is_wp_error( $response ) && $response_code == 200 ) {
					$google_fonts = $response['body'];
					if ( ! file_exists( $path ) ) {
						Kata_Plus_Helpers::rmdir( $path );
						Kata_Plus_Helpers::mkdir( $path );
					}
					Kata_Plus_Helpers::wrfile( $path . '\settings.json', $google_fonts );

				} elseif ( is_wp_error( $response ) || $response_code !== 200 ) {
					$fonts_list['try-later'] = __( 'Please try again later', 'kata-plus' );
				}
			} else {
				$fonts_list                = array();
				$fonts_list['select-font'] = __( 'Select Font', 'kata-plus' );
				$fonts                     = json_decode( $google_fonts );
				if ( ! empty( $fonts->items ) ) {
					foreach ( $fonts->items as $font ) {
						$fonts_list[ $font->family ] = $font->family;
					}
					return $fonts_list;
				}
			}
			return false;
		}
	} // class

	Kata_Plus_Theme_Options_Add_Google_Fonts_Typography::set_options();
}
