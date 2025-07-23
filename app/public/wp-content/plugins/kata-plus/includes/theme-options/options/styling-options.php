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

if ( ! class_exists( 'Kata_Plus_Theme_Options_Styling_Typography' ) ) {
	class Kata_Plus_Theme_Options_Styling_Typography extends Kata_Plus_Theme_Options {
		/**
		 * Set Options.
		 *
		 * @since   1.0.0
		 */
		public static function set_options() {

			// -> Start Styling & Typography section
			new \Kirki\Section(
				'kata_styling_typography_section',
				array(
					'title'      => esc_html__( 'Advanced Styling', 'kata-plus' ),
					'capability' => Kata_Plus_Helpers::capability(),
					'icon'       => 'ti-palette',
					'priority'   => 4,
				)
			);
			new \Kirki\Field\Color(
				array(
					'type'     => 'color',
					'settings' => 'kata_base_color',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Primary Color', 'kata-plus' ),
					'default'  => '',
					'choices'  => array(
						'alpha' => true,
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_body_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Body', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'body',
						'wrapper'  => '',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_all_heading',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'All Heading', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'h1, h2, h3, h4, h5, h6',
						'hover'    => 'h1:hover, h2:hover, h3:hover, h4:hover, h5:hover, h6:hover',
						'wrapper'  => '',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_h1_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Heading 1', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'h1',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_h2_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Heading 2', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'h2',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_h3_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Heading 3', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'h3',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_h4_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Heading 4', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'h4',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_h5_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Heading 5', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'h5',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_h6_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Heading 6', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'h6',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_p_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Paragraph (p tag)', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'p',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_blockquote_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Blockquote', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'blockquote',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_a_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Links (a tag)', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'a',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_ul_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'ul tag', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'ul',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_li_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'li tag', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'li',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'ol_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'ol tag', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'ol',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_img_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Image (img tag)', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => '.elementor img, img',
						'hover'    => '.elementor img:hover,img:hover',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_button_element',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Button', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'a.kata-button',
						'wrapper'  => 'body',
					),
				)
			);

			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_table_element',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Table', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'table',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_tr_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'tr tag', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'tr',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_th_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'th tag', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'th',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_td_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'td tag', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'td',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_input_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Input', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'input',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_textarea_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Textarea', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'textarea',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_select_tag',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Select', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'select',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_cehckbox',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Checkbox', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'input type="checkbox',
						'wrapper'  => 'body',
					),
				)
			);
			Kirki::add_field(
				self::$opt_name,
				array(
					'settings' => 'styler_radio',
					'section'  => 'kata_styling_typography_section',
					'label'    => esc_html__( 'Radio', 'kata-plus' ),
					'type'     => 'styler',
					'choices'  => array(
						'selector' => 'input type="radio',
						'wrapper'  => 'body',
					),
				)
			);
		}

		/**
		 * Added Fonts
		 *
		 * @since 1.0.0
		 */
		public static function added_fonts() {
			$fonts                      = get_theme_mod( 'kata_add_google_font_repeater' );
			$added_fonts                = array();
			$added_fonts['select-font'] = 'Select Font';
			if ( $fonts ) {
				foreach ( $fonts as $key => $font ) {
					$added_fonts[ $font['fonts'] ] = $font['fonts'];
				}
			}
			return $added_fonts;
		}
	} // class

	Kata_Plus_Theme_Options_Styling_Typography::set_options();
}
