<?php
namespace Styler\BreakPoints;

/**
 * Styler BreakPoint Class.
 *
 * @author  ClimaxThemes
 * @package Styler
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BreakPoint {

	private $name;
	private $label;
	private $size;
	private $pattern;
	private $direction  = 'max';
	private $is_enabled = false;

	/**
	 * Get Name
	 *
	 * @since 3.2.0
	 *
	 * @return string
	 */
	public function get_name() {
		return apply_filters( 'styler/breakPoints/breakpoint/name' , $this->name, $this );
	}

	/**
	 * Is Enabled
	 *
	 * Check if the breakpoint is enabled or not. The breakpoint instance receives this data from
	 * the Breakpoints Manager.
	 *
	 * @return bool $is_enabled class variable
	 */
	public function is_enabled() {
		return apply_filters( 'styler/breakPoints/breakpoint/is_enabled' , $this->is_enabled, $this );
	}

	/**
	 * Get Label
	 *
	 * Retrieve the breakpoint label.
	 *
	 * @since 3.2.0
	 *
	 * @return string $label class variable
	 */
	public function get_label() {
		return apply_filters( 'styler/breakPoints/breakpoint/label' , $this->label, $this );
	}

	/**
	 * Get Style Pattern
	 *
	 * Retrieve the breakpoint pattern.
	 *
	 * @since 3.2.0
	 *
	 * @return string $pattern class variable
	 */
	public function get_pattern() {
		return apply_filters( 'styler/breakPoints/breakpoint/pattern' , $this->pattern, $this );
	}

	/**
	 * Get Size
	 *
	 * Retrieve the breakpoint size.
	 *
	 * @since 3.2.0
	 *
	 * @return string $size class variable
	 */
	public function get_size() {
		return apply_filters( 'styler/breakPoints/breakpoint/size' , $this->size, $this );
	}

	/**
	 * Get Direction
	 *
	 * Returns the Breakpoint's direction ('min'/'max').
	 *
	 * @since 3.2.0
	 *
	 * @return string $direction class variable
	 */
	public function get_direction() {
		return apply_filters( 'styler/breakPoints/breakpoint/direction' , $this->direction, $this );
	}

	public function __construct( $args ) {
		$this->name       = $args['name'];
		$this->label      = $args['label'];
		$this->direction  = $args['direction'];
		$this->pattern    = $args['pattern'];
		$this->is_enabled = $args['is_enabled'];
		$this->size       = $args['size'];
	}
} // Class
