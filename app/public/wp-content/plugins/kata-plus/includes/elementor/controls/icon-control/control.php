<?php
/**
 * Icon Control Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Base_Data_Control;
use Elementor\Plugin;

Kata_Plus_Autoloader::load( Kata_Plus_Elementor::$dir . 'controls/icon-control', 'icons' );

class Kata_Plus_Elementor_Icon_Control extends Base_Data_Control {
	/**
	 * Control type name.
	 *
	 * @since   1.0.0
	 */
	public function get_type() {
		return 'kata_plus_icons';
	}


	/**
	 * Control enqueue live assets.
	 *
	 * @since   1.0.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'kata-plus-icon-control', Kata_Plus::$assets . 'css/backend/elementor-icon-control.css', array(), Kata_Plus::$version );
		wp_enqueue_script( 'lozad', Kata_Plus::$assets . 'js/libraries/lozad.min.js', array( 'jquery' ), Kata_Plus::$version, false );
		wp_enqueue_script( 'kata-plus-icon-control', Kata_Plus::$assets . 'js/backend/elementor-icon-control.js', array( 'jquery' ), Kata_Plus::$version, false );
	}

	/**
	 * Control HTML structure.
	 *
	 * @since   1.0.0
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<input type="hidden" class="kata-icon-control" id="<?php echo esc_attr( $control_uid ); ?>" data-setting="{{ data.name }}" value="wand">
				<# if ( data.controlValue ) { #>
					<img src="<?php echo esc_attr( Kata_Plus::$assets . 'fonts/svg-icons/{{ data.controlValue }}.svg' ); ?>" data-src="<?php echo esc_attr( Kata_Plus::$assets ); ?>fonts/svg-icons/">
				<# } else { #>
					<img src="" data-src="<?php echo esc_attr( Kata_Plus::$assets ); ?>fonts/svg-icons/">
				<# } #>
				<a href="#" class="kata-open-icons-dialog-btn <?php echo esc_attr( $control_uid ); ?>" data-title="{{ data.label }}"><?php esc_html_e( 'Choose', 'kata-plus' ); ?></a>
				<a href="#" class="kata-remove-icon <?php echo esc_attr( $control_uid ); ?>"><i class="eicon-close"></i></a>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{ data.description }}</div>
		<# } #>
		<?php
	}
}

Plugin::$instance->controls_manager->register( new Kata_Plus_Elementor_Icon_Control() );
