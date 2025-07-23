<?php

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Kata_Elementor_Sticky' ) ) {
	class Kata_Elementor_Sticky {

		/**
		 * Kata_Elementor_Sticky class constructor.
		 *
		 * Initializes the class by adding necessary actions.
		 *
		 * @return void
		 * @since 1.4.5
		 */
		public function __construct() {
			$this->add_actions();
		}

		/**
		 * Adds actions to Elementor's section effects.
		 *
		 * This function hooks into Elementor's section effects to register controls.
		 *
		 * @return void
		 * @since 1.4.5
		 */
		private function add_actions() {
			add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'register_controls' ) );
			add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'register_scripts' ) );
		}

		/**
		 * Registers scripts for the Kata Sticky element.
		 *
		 * This function hooks into Elementor's frontend to enqueue necessary scripts for the Kata Sticky element.
		 *
		 * @return void
		 * @since 1.4.5
		 */
		public function register_scripts() {
			$stickyLib = Kata_Plus::$url . 'includes/elementor/options/sticky/sticky-sidebar.js';
			wp_enqueue_script( 'sticky-sidebar', $stickyLib, array( 'jquery', 'elementor-frontend' ), Kata_Plus::$version, true );

			$sticky = Kata_Plus::$url . 'includes/elementor/options/sticky/script.js';
			wp_enqueue_script( 'kata-elementor-sticky', $sticky, array( 'jquery', 'elementor-frontend', 'sticky-sidebar' ), Kata_Plus::$version, true );
		}


		/**
		 * Registers controls for the Kata Sticky element.
		 *
		 * This function hooks into Elementor's section effects to register controls for the Kata Sticky element.
		 * It adds controls for sticky position, offset, Stay On, and stay in column options.
		 *
		 * @param Element_Base $element The Elementor element instance.
		 *
		 * @return void
		 * @since 1.4.5
		 */
		public function register_controls( Element_Base $element ) {

			$active_breakpoint = Plugin::$instance->breakpoints->get_active_breakpoints();
			$active_devices    = array_reverse( array_keys( $active_breakpoint ) );
			$active_devices    = array_merge( [ 'desktop' ], $active_devices );

			if ( in_array( 'widescreen', $active_devices, true ) ) {
				$active_devices = array_merge( array_slice( $active_devices, 0, 1 ), [ 'desktop' ], array_slice( $active_devices, 1 ) );
			}

			$sticky_device_options = [];

			foreach ( $active_devices as $device ) {
				$label = 'desktop' === $device ? esc_html__( 'Desktop', 'kata-plus' ) : $active_breakpoint[ $device ]->get_label();
				$sticky_device_options[ $device ] = $label;
			}

			$element->start_controls_section(
				'kata_sticky_section',
				array(
					'label' => esc_html__( 'Kata Sticky', 'kata-plus' ),
					'tab'   => Controls_Manager::TAB_ADVANCED,
				)
			);

			$element->add_control(
				'kata_sticky',
				[
					'label'              => esc_html__( 'Sticky', 'kata-plus' ),
					'type'               => Controls_Manager::SELECT,
					'separator'          => 'before',
					'render_type'        => 'none',
					'frontend_available' => true,
					'assets'             => $this->assets(),
					'options'            => [
						''       => esc_html__( 'None', 'kata-plus' ),
						'top'    => esc_html__( 'Top', 'kata-plus' ),
						'bottom' => esc_html__( 'Bottom', 'kata-plus' ),
					],
				]
			);

			$element->add_control(
				'kata_sticky_in_column',
				[
					'label'        => esc_html__( 'Stay In Column', 'kata-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'render_type'  => 'none',
					'condition'    => array(
						'kata_sticky!' => '',
					),
					'frontend_available' => true,
				]
			);

			$element->add_control(
				'kata_sticky_on',
				[
					'label'              => esc_html__( 'Sticky On', 'kata-plus' ),
					'type'               => Controls_Manager::SELECT2,
					'multiple'           => true,
					'label_block'        => true,
					'default'            => $active_devices,
					'options'            => $sticky_device_options,
					'render_type'        => 'none',
					'frontend_available' => true,
					'condition'          => [
						'kata_sticky!' => '',
					],
				]
			);

			$element->add_control(
				'kata_sticky_offset',
				[
					'label'              => esc_html__( 'Offset', 'kata-plus' ),
					'type'               => Controls_Manager::NUMBER,
					'default'            => 0,
					'min'                => 0,
					'max'                => 500,
					'required'           => true,
					'render_type'        => 'none',
					'frontend_available' => true,
					'condition'          => [
						'kata_sticky!' => '',
					],
				]
			);

			$element->end_controls_section();
		}

		/**
		 * Returns an array of assets required for the sticky functionality.
		 *
		 * The assets include two scripts: 'sticky-sidebar' and 'kata-elementor-sticky'.
		 * Both scripts are loaded conditionally based on the 'kata_sticky' value.
		 *
		 * @return array An array containing the assets configuration.
		 * @since 1.4.5
		 */
		private function assets() {
			return [
				'scripts' => [
					[
						'name' => 'sticky-sidebar',
						'conditions' => [
							'terms' => [
								[
									'name' => 'kata_sticky',
									'operator' => '!==',
									'value' => '',
								],
							],
						],
					],
					[
						'name' => 'kata-elementor-sticky',
						'conditions' => [
							'terms' => [
								[
									'name' => 'kata_sticky',
									'operator' => '!==',
									'value' => '',
								],
							],
						],
					],
				],
			];
		}

	}
	new Kata_Elementor_Sticky();
}
