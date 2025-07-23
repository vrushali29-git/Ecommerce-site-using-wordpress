<?php
/**
 * Audio Chooser Class.
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

class Kata_Plus_Elementor_Audio_Chooser_Control extends Base_Data_Control {

	/**
	 * Get control type.
	 *
	 * Retrieve the control type
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'kata_plus_icons_audio_chooser';
	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * Used to register and enqueue custom scripts and styles
	 * for this control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_media();
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );

		wp_enqueue_script( 'kata-plus-audio-chooser-control', Kata_Plus::$assets . 'js/backend/audio-chooser-control.js', array( 'jquery' ), '', true );
	}

	/**
	 * Get default settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return array(
			'label_block' => true,
		);
	}

	/**
	 * Render control output in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();

		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<a href="#" class="smc-ec-select-file elementor-button" style="padding: 10px 15px; display: block; text-align: center; border: 1px dashed #d5dadf;" id="select-file-<?php echo esc_attr( $control_uid ); ?>" ><?php echo esc_html__( 'Choose Audio File', 'music-player-for-elementor' ); ?></a> <br />

				<input type="text" class="smc-selected-audio-url" id="<?php echo esc_attr( $control_uid ); ?>" data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}">
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>

		<?php
	}
}
Plugin::$instance->controls_manager->register( new Kata_Plus_Elementor_Audio_Chooser_Control() );
