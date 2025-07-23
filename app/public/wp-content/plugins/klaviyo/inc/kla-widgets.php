<?php
/**
 * Klaviyo_EmailSignUp_Widget.
 *
 * @package WooCommerceKlaviyo
 * @version 2.0.0
 */

/**
 * Legacy Signup Form Widget.
 *
 * @extends WP_Widget
 */
class Klaviyo_EmailSignUp_Widget extends WP_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			false,
			$name           = 'Klaviyo: Legacy Email Sign Up',
			$widget_options = array(
				'description' => 'Allow people to subscribe to a Klaviyo email list using a Legacy Signup Form.',
			)
		);
	}

	/**
	 * Echos the widget content.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		extract($args);
		$klaviyo_settings = get_option( 'klaviyo_settings' );
		$list_id          = $instance['list_id'];

		if ( empty( $list_id ) ) {
			return;
		}

		// check if the form fields were submitted with any value
		// if they were, use the value submitted
		// else, use a default (empty string or 'Subscribe').
		$title       = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$description = ( ! empty( $instance['description'] ) ) ? $instance['description'] : '';
		$button_text = ( ! empty( $instance['button_text'] ) ) ? $instance['button_text'] : 'Subscribe';
		$styles      = ( ! empty( $instance['button_styles'] ) ) ? $instance['button_styles'] : '';

		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( trim( $title ) != '' ) {
			echo $before_title . $title . $after_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<form id="kla_embed_' . esc_attr($this->id) . '" class="" action="//manage.kmail-lists.com/subscriptions/subscribe" data-ajax-submit="//manage.kmail-lists.com/ajax/subscriptions/subscribe" method="GET" target="_blank" novalidate="novalidate">' . "\n";
		echo '  <input type="hidden" name="g" value="' . esc_attr($list_id) . '">' . "\n";

		if ( ! empty( $description ) ) {
			echo '  <p>' . esc_html($description) . '</p>' . "\n";
		}

		echo '  <div class="klaviyo_field_group">' . "\n";
		echo '    <label for="kla_email_' . esc_attr($this->id) . '" style="display:none;">' . esc_html($title) . '</label>' . "\n";
		echo '    <input type="text" value="" name="email" id="kla_email_' . esc_attr($this->id) . '" placeholder="Your email" />' . "\n";
		echo '    <button type="submit" class="klaviyo_submit_button" style="' . esc_attr($styles) . '">' . esc_html($button_text) . '</button>' . "\n";

		echo '  </div>' . "\n";
		echo '  <div class="klaviyo_messages">' . "\n";
		echo '    <div class="success_message" style="display:none;"></div>' . "\n";
		echo '    <div class="error_message" style="display:none;"></div>' . "\n";
		echo '  </div>' . "\n";
		echo '</form>' . "\n";
		echo '<script type="text/javascript" src="//www.klaviyo.com/media/js/public/klaviyo_subscribe.js"></script>' . "\n"; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		echo '<script type="text/javascript">' . "\n";
		echo '  KlaviyoSubscribe.attachToForms("#kla_embed_' . esc_attr($this->id) . '", {' . "\n";
		echo '    hide_form_on_success: true' . "\n";
		echo '  });' . "\n";
		echo '</script>' . "\n";

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * This function should check that `$new_instance` is set correctly. The newly-calculated
	 * value of `$instance` should be returned. If false is returned, the instance won't be
	 * saved/updated.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		return array_merge( $old_instance, $new_instance );
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			$instance,
			array(
				'title'         => '',
				'list_id'       => '',
				'description'   => '',
				'button_text'   => '',
				'button_styles' => '',
			)
		);
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e(( 'Title:' )); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></label></p>
		<label for="<?php echo esc_attr($this->get_field_id( 'description' )); ?>"><?php esc_html_e( 'List Description:' ); ?></label>
		<textarea class="widefat" rows="3" cols="20" id="<?php echo esc_attr($this->get_field_id( 'description' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'description')); ?>"><?php echo esc_html($instance['description']); ?></textarea>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'button_text' )); ?>"><?php esc_html_e( 'Button Text:' ); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'button_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'button_text' )); ?>" type="text" value="<?php echo esc_attr($instance['button_text']); ?>" /></label></p>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'list_id' )); ?>"><?php esc_html_e( 'List ID:' ); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'list_id' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'list_id' )); ?>" type="text" value="<?php echo esc_attr($instance['list_id']); ?>" /></label></p>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'button_styles' )); ?>"><?php esc_html_e( 'Button Styles:' ); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'button_styles' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'button_styles' )); ?>" type="text" value="<?php echo esc_attr($instance['button_styles']); ?>" /></label></p>
		<?php
	}
}

/**
 * Signup Form Builder Widget.
 *
 * @extends WP_Widget
 */
class Klaviyo_EmbedEmailSignUp_Widget extends WP_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			false,
			$name           = 'Klaviyo: Email Sign Up',
			$widget_options = array(
				'description' => 'Allow people to subscribe to a Klaviyo email list designed using Klaviyo\'s built-in Signup Form Builder.',
			)
		);
	}

	/**
	 * Echos the widget content.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		extract( $args );
		$klaviyo_settings = get_option( 'klaviyo_settings' );
		$form_id          = $instance['form_id'];

		if ( empty( $form_id ) ) {
			return;
		}

		$title = $instance['title'];

		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( trim( $title ) != '' ) {
			echo $before_title . $title . $after_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<div class="klaviyo-form-' . esc_attr($form_id) . '"></div>';

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * This function should check that `$new_instance` is set correctly. The newly-calculated
	 * value of `$instance` should be returned. If false is returned, the instance won't be
	 * saved/updated.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		return array_merge( $old_instance, $new_instance );
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			$instance,
			array(
				'title'   => '',
				'form_id' => '',
			)
		);
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' ); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></label></p>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'form_id' )); ?>"><?php esc_html_e( 'Form ID:' ); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'form_id' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'form_id' )); ?>" type="text" value="<?php echo esc_attr($instance['form_id']); ?>" /></label></p>
		<?php
	}
}

?>
