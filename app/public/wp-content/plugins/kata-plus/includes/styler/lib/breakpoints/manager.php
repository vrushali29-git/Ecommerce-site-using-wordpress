<?php
namespace Styler\BreakPoints;

/**
 * Styler Manager Class.
 *
 * @author  ClimaxThemes
 * @package Styler
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Styler\BreakPoints\BreakPoint;

class Manager {

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public static $instance;

	/**
	 * BreakPoints.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     Styler
	 */
	private $breakpoints;


	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.0.0
	 * @return  object
	 */
	public static function get_instance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get Breakpoints
	 *
	 * Retrieve the array containing instances of all breakpoints existing in the system, or a single breakpoint if a
	 * name is passed.
	 *
	 * @since 3.2.0
	 *
	 * @param $breakpoint_name
	 * @return Breakpoint[]|Breakpoint
	 */
	public function get_breakpoints( $breakpoint_name = null ) {

		if ( ! $this->breakpoints ) {
			$this->init_breakpoints();
		}

		return self::get_items( $this->breakpoints, $breakpoint_name );
	}

	/**
	 * Get Items
	 *
	 * @param array $haystack
	 * @param string||int $needle
	 * @return mixed The whole haystack or the needle from the haystack when requested
	 */
	protected static function get_items( array $haystack, $needle = null ) {
		if ( $needle ) {
			return isset( $haystack[ $needle ] ) ? $haystack[ $needle ] : null;
		}

		return $haystack;
	}

	/**
	 * Get Breakpoint Size
	 *
	 * @param string $device_name
	 * @return int the min breakpoint of the passed device
	 *@since 3.2.0
	 */
	public function get_breakpoint_size( $device_name ) {

		$breakpoints = $this->get_breakpoints();

		if( ! isset( $breakpoints[ $device_name ] ) ) {
			return -1;
		}

		$current_device_breakpoint = $breakpoints[ $device_name ];
		$breakpoint_size = $current_device_breakpoint->get_size();

		return apply_filters( 'styler-get-breakpoint-size', $breakpoint_size, $current_device_breakpoint );
	}


	/**
	 * Get Default Config
	 *
	 * Retrieve the default breakpoints config array. The 'selector' property is used for CSS generation (the
	 * Stylesheet::add_device() method).
	 *
	 * @return array
	 */
	public static function get_default_config() {
		return [
			// 'smallmobile' => [
			// 	'label' => __( 'Small Mobile', 'elementor' ),
			// 	'size' => 320,
			// 	'direction' => 'max',
			// 	'pattern' => '@media(max-width: {{SIZE}}px){{{STYLE}}}',
			// 	'is_enabled' => true,
			// ],
			'mobile' => [
				'label' => __( 'Mobile', 'styler' ),
				'size' => 767,
				'direction' => 'max',
				'pattern' => '@media(max-width: {{SIZE}}px){{{STYLE}}}',
				'is_enabled' => true,
			],
			'mobileLandscape' => [
				'label' => __( 'M Landscape', 'styler' ),
				'size' => 880,
				'direction' => 'max',
				'pattern' => '@media(max-width: {{SIZE}}px){{{STYLE}}}',
				'is_enabled' => true,
			],
			'tablet' => [
				'label' => __( 'Tablet', 'styler' ),
				'size' => 1024,
				'direction' => 'max',
				'pattern' => '@media(max-width: {{SIZE}}px){{{STYLE}}}',
				'is_enabled' => true,
			],
			'tabletlandscape' => [
				'label' => __( 'T Landscape', 'styler' ),
				'size' => 1200,
				'direction' => 'max',
				'pattern' => '@media(max-width: {{SIZE}}px){{{STYLE}}}',
				'is_enabled' => true,
			],
			'laptop' => [
				'label' => __( 'Laptop', 'styler' ),
				'size' => 1366,
				'direction' => 'max',
				'pattern' => '@media(max-width: {{SIZE}}px){{{STYLE}}}',
				'is_enabled' => true,
			],
			'desktop' => [
				'label' => __( 'Desktop', 'styler' ),
				'size' => 1367,
				'direction' => 'min',
				'pattern' => '{{STYLE}}',
				'is_enabled' => true,
			],
		];
	}

	/**
	 * Init Breakpoints
	 *
	 * Creates the breakpoints array, containing instances of each breakpoint. Returns an array of ALL breakpoints,
	 * Just Enables
	 *
	 * @since 3.2.0
	 */
	private function init_breakpoints() {
		$breakpoints = [];
		$default_config = self::get_default_config();

		foreach ( $default_config as $breakpoint_name => $breakpoint_config ) {
			$args = [ 'name' => $breakpoint_name ] + $breakpoint_config;

			$breakpoints[ $breakpoint_name ] = new Breakpoint( $args );
		}

		$this->breakpoints = $breakpoints;
	}

} // Class

// Manager::get_instance();
