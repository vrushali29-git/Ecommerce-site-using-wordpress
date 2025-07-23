<?php
namespace Styler;

/**
 * Styler StyleSheet Class.
 *
 * @author  ClimaxThemes
 * @package Styler
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Styler\BreakPoints\Manager as BreakPointManager;
use Styler\Init;
class StyleSheet {

	/**
	 * Prepared Styles.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public $styles = array(
		// 'smallmobile' => array(),
		// 'mobile'      => array(),
		// 'tablet'      => array(),
		// 'tabletlandscape' => array(),
		// 'laptop'      => array(),
		// 'desktop'     => array()
	);

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public static $instance;

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	protected static $ignoreList = array(
		'background-image-id',
		'background-type',
		'background-position-x',
		'background-position-y',
		'background-position-type',
		'gradient-first-color',
		'gradient-second-color',
		'gradient-angle',
		'use-as-color',
		'box-shadow-temp',
		'text-shadow-temp',
		'background-size-type',
		'background-size-x',
		'background-id',
		'background-size-y',
	);

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.0.0
	 * @return  object
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Define the functionality .
	 *
	 * Load the dependencies.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		// $this->dependencies();
		$this->actions();
	}

	/**
	 * Add WP Hooks
	 *
	 * @since     1.0.0
	 */
	public function actions() {

		// add_action('init', [$this, 'init']);
		add_filter( 'styler-localize-data', array( $this, 'add_selectors' ) );
		add_filter( 'styler-localize-data', array( $this, 'add_breakpoints' ) );

		add_action( 'delete_post', array( $this, 'delete_generated_style' ) );
	}

	function delete_generated_style( $post_id ) {
		if ( $path = realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'elementor', 'css', "post-{$post_id}.css" ) ) ) ) {
			@unlink( $path );
		}

		if ( $path = realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'gutenberg', 'css', "post-{$post_id}.css" ) ) ) ) {
			@unlink( $path );
		}

		if ( $path = realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'kirki', 'css', "post-{$post_id}.css" ) ) ) ) {
			@unlink( $path );
		}
	}

	/**
	 * Parse Item Style
	 *
	 * @since     1.0.0
	 */
	public function prepare( $stData ) {

		if ( ! is_array( $stData ) ) {
			$stData = \json_decode( $stData, true );
		}

		if ( ! is_array( $stData ) ) {
			return '';
		}

		$styles      = '';
		$breakpoints = BreakPointManager::get_instance()->get_breakpoints();

		foreach ( \array_reverse( $breakpoints, true ) as $device => $value ) {

			if ( ! isset( $stData[ $device ] ) ) {
				continue;
			}

			if ( empty( $stData[ $device ] ) ) {
				continue;
			}

			if ( ! isset( $this->styles [ $device ] ) ) {
				$this->styles [ $device ] = array();
			}

			foreach ( $stData[ $device ] as $action => $actionObject ) {

				if ( ! isset( $actionObject['selector'] ) || empty( $actionObject['selector'] ) ) {
					continue;
				}

				if ( empty( $actionObject['data'] ) ) {
					continue;
				}

				$actionObject['data'] = wp_unslash( $actionObject['data'] );

				$selector = $this->get_selector( $actionObject['selector'] );
				$style    = \wp_sprintf( '%s{%s}', $selector, $this->convert_to_css( $actionObject['data'] ) );
				$styles  .= \str_replace(
					array( '{{SIZE}}', '{{STYLE}}' ),
					array( $breakpoints[ $device ]->get_size(), $style ),
					$breakpoints[ $device ]->get_pattern()
				) . "\n";

				if ( ! isset( $this->styles[ $device ] ) ) {
					$this->styles[ $device ] = array();
				}

				\array_push( $this->styles[ $device ], $style );

			}
		}
		$styles = str_replace( '{{WRAPPER}}', '', $styles );
		return $styles;
	}

	private function get_selector( $selector ) {

		$selector = trim( \str_replace( '  ', ' ', $selector ) );

		$selector = trim(
			\str_replace(
				array(
					' :hover',
					' :after',
					' :before',
					' :placeholder',
				),
				array( ':hover', ':after', ':before', ':placeholder' ),
				$selector
			)
		);

		return $selector;
	}

	public function parse() {

		$breakpoints = BreakPointManager::get_instance()->get_breakpoints();
		$styles      = '';
		$sorted      = array(
			'desktop'         => isset( $this->styles['desktop'] ) ? array_unique( $this->styles['desktop'] ) : false,
			'laptop'          => isset( $this->styles['laptop'] ) ? array_unique( $this->styles['laptop'] ) : false,
			'tabletlandscape' => isset( $this->styles['tabletlandscape'] ) ? array_unique( $this->styles['tabletlandscape'] ) : false,
			'tablet'          => isset( $this->styles['tablet'] ) ? array_unique( $this->styles['tablet'] ) : false,
			'mobile'          => isset( $this->styles['mobile'] ) ? array_unique( $this->styles['mobile'] ) : false,
			'mobileLandscape'     => isset( $this->styles['mobileLandscape'] ) ? array_unique( $this->styles['mobileLandscape'] ) : false,
		);

		foreach ( $sorted as $breakpoint => $data ) {

			if ( ! $data ) {
				continue;
			}

			$styles .= \str_replace(
				array( '{{SIZE}}', '{{STYLE}}' ),
				array( $breakpoints[ $breakpoint ]->get_size(), \implode( '', $data ) ),
				$breakpoints[ $breakpoint ]->get_pattern()
			) . "\n";

		}

		$styles = str_replace( '{{WRAPPER}}', '', $styles );

		return $styles;
	}

	/**
	 * Convert To CSS
	 *
	 * @since     1.0.0
	 */
	private function convert_to_css( $properties ) {
		$css = '';

		foreach ( $properties as $property => $data ) {

			if ( ! isset( $data['value'] ) && isset( $data['object_value'] )  ) {
				$data['value'] = '';
				foreach ( $data['object_value'] as $key => $value ) {
					$data['value'] .= "$key($value) ";
				}
			}

			if( ! isset( $data['value'] ) ) {
				continue;
			}

			if( $data['value'] === 'not-completed' ) {
				continue;
			}

			if( isset( $data['lock'] ) && $data['lock'] === false ) {
				continue;
			}

			if( ! is_string( @$data['value'] ) ) {
				continue;
			}

			if ( ! strlen( @$data['value'] ) || array_search( $property, static::$ignoreList ) !== false ) {
				continue;
			}

			if ( $property === 'background-image'
				&& isset( $properties['background-type'] )
				&& $properties['background-type']['value'] !== 'gradient'
			) {
				continue;
			}

			if ( $property === 'font-family' && strpos( $data['value'], '\\' ) !== false ) {
				$data['value'] = str_replace('\\', '', $data['value']);
				$data['value'] = str_replace('"', '', $data['value']);
				if ( ! $data['value'] ) {
					$data['value'] = '';
				}
			}

			if ( $property === 'font-family'
				&& isset( $properties['font-family'] )
				&& \strpos( $data['value'], 'sans-serif' ) === false
			) {
				$data['value'] .= ',sans-serif';
			}

			if ( ( $property === 'background' || $property === 'background-position' || $property === 'background-repeat' || $property === 'background-size' )
				&& isset( $properties['background-type'] )
				&& $properties['background-type']['value'] === 'gradient'
			) {
				continue;
			}

			if ( ( $property === 'background' ) && isset( $properties['background-type'] )
			&& $properties['background-type']['value'] === 'classic' ) {
				$property = 'background-image';
			}

			if ( ( $property === 'background' ) && isset( $properties['background-id'] )
			&& isset( $properties['background-id']['value']['value'] ) &&
			is_numeric( $properties['background-id']['value']['value'] ) && strpos( $property . ':' . $data['value'], 'url(' ) !== false ) {
				$property = 'background-image';
			}

			$data['value'] = str_replace( '!important', '', $data['value'] );
			$value = $data['value'] . ( isset( $data['important'] ) && $data['important'] === true ? ' !important' : '' );
			$value = \str_replace( '%u', ( isset( $data['unit'] ) ? $data['unit'] : 'px' ), $value );
			$css  .= $property . ':' . $value . ';';
		}

		$css = str_replace(
			array(
				'pxpx',
				'%px',
				'empx',
				'content:\\'
			),
			array(
				'px',
				'%',
				'em',
				"content:''"
			),
			$css
		);

		$css = str_replace("content:''\;", "content:'';", $css);

		return $css;
	}

	/**
	 * Parse Content
	 *
	 * @since     1.0.0
	 */
	public function parse_content( $post_id, $type = 'post', $subfolder = '' ) {

		$uploader = new \Styler\Utils\UploadHandler( $subfolder, $type . '-' . $post_id, 'css' );
		$styles   = $this->parse();

		$csstidy = new \csstidy();

		// Set some options :
		$csstidy->set_cfg(
			array(
				'sort_properties' => true,
				'sort_selectors'  => true,
				'preserve_css'  => true,
				'merge_selectors' => false,
				'template' => 'high',
				'css_level' => 'CSS3.0',
			)
		);

		// Parse the CSS
		$csstidy->parse( $styles );

		// Get back the optimized CSS Code
		$styles = $csstidy->print->plain();

		if ( $styles ) {
			$uploader->write( $styles );
		} else {
			$uploader->delete();
		}
	}

	/**
	 * Add Selectors into Localized Data.
	 *
	 * @since 1.0.0
	 */
	public function add_selectors( $data ) {

		$breakpoints = array_keys( BreakPointManager::get_instance()->get_breakpoints() );
		$selectors   = array();

		foreach ( $breakpoints as $breakpoint ) {
			$selectors[ $breakpoint ] = array(
				'normal'       => '{{WRAPPER}} {{SELECTOR}}',
				'hover'        => '{{WRAPPER}} {{SELECTOR}}:hover',
				'parent-hover' => '{{WRAPPER}}:hover {{SELECTOR}}',
				'before'       => '{{WRAPPER}} {{SELECTOR}}:before',
				'after'        => '{{WRAPPER}} {{SELECTOR}}:after',
				'placeholder'  => '{{WRAPPER}} {{SELECTOR}}::placeholder',
			);
		}

		$data['selectors'] = $selectors;
		return $data;
	}

	/**
	 * Add Breakpoints into Localized Data.
	 *
	 * @since 1.0.0
	 */
	public function add_breakpoints( $data ) {

		$breakpoints         = BreakPointManager::get_instance()->get_breakpoints();
		$data['breakpoints'] = array();
		foreach ( $breakpoints as $breakpoint => $value ) {
			$data['breakpoints'][ $breakpoint ] = array(
				'label'      => $value->get_label(),
				'size'       => $value->get_size(),
				'direction'  => $value->get_direction(),
				'pattern'    => $value->get_pattern(),
				'is_enabled' => $value->is_enabled(),
			);
		}

		return apply_filters( 'styler/stylesheet/localizeBreakpoints', $data );
	}
} // Class

StyleSheet::get_instance();
