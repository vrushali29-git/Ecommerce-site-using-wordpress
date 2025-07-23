<?php
/**
 * WPKlaviyoNotification.
 *
 * @package WooCommerceKlaviyo
 * @version 2.0.0
 */

/**
 * Helper class for admin notifications.
 */
class WPKlaviyoNotification {

	/**
	 * Admin message text.
	 *
	 * @var string
	 */
	public $admin_message_text = '';
	/**
	 * Default message text.
	 *
	 * @var string
	 */
	public $default_message_text = '';

	/**
	 * Constructor.
	 *
	 * @param string $default_message_text Message content.
	 */
	public function __construct( $default_message_text = '' ) {
		$this->admin_message_text   = '';
		$this->default_message_text = $default_message_text;
	}

	/**
	 * Configuration warning.
	 *
	 * @return void
	 */
	public function config_warning() {
		if ( ! WPKlaviyo::is_connected() ) {
			if ( ! WCK()->options->get_klaviyo_option( 'admin_settings_message' ) ) {
				if ( ! ( isset( $_GET['page'] ) && 'klaviyo_settings' == $_GET['page'] ) ) {
					$this->admin_message( 'config_warning' );
				}
			}
		}
	}

	/**
	 * Admin message case statement.
	 *
	 * @param string  $message Message slug.
	 * @param integer $display_time Seconds to display message.
	 * @return void
	 */
	public function admin_message( $message = 'default_error', $display_time = 0 ) {
		$message_text = '';

		switch ( $message ) {
			case 'settings_update':
				$message_text = 'Klaviyo settings updated.';
				break;
			case 'config_warning':
				$message_text = 'Please go to the <a href="' . KLAVIYO_ADMIN . 'admin.php?page=klaviyo_settings">Klaviyo settings page</a> to add your API keys or to hide this warning.';
				break;
			case 'default_error':
				$message_text = 'An error occurred, please try again or contact Klaviyo support.';
				break;
			case 'add_sms_list_id':
				$message_text = 'Please add a List ID for SMS consent';
				break;
			case 'same_list_ids':
				$message_text = 'Both List IDs are same, please use different lists for registering Email and SMS consent';
				break;
			case 'add_email_list_id':
				$message_text = 'Please add a List ID for Email consent';
				break;
			default:
				$message_text = $message;
				break;
		}

		if ( in_array( $message, array( 'same_list_ids', 'add_sms_list_id', 'add_email_list_id' ) ) ) {
			echo '<div id="msg-' . esc_attr( $message ) . '" class="notice notice-warning updated-fade is-dismissible"><p>' . esc_html( $message_text ) . '</p></div>' . "\n";
		} else {
			echo '<div id="msg-' . esc_attr( $message ) . '" class="updated fade"><p>' . esc_html( $message_text ) . '</p></div>' . "\n";
		}

		if ( 0 != $display_time ) {
			echo '<script type="text/javascript">setTimeout(function () { jQuery("#msg-' . esc_attr( $message ) . '").hide("slow");}, ' . tag_escape( $display_time ) * 1000 . ');</script>';
		}
	}

	/**
	 * Appends message.
	 *
	 * @param string $message_text Message content.
	 * @return void
	 */
	public function add_message( $message_text ) {
		if ( trim( $this->admin_message_text ) != '' ) {
			$this->admin_message_text .= '<br />';
		}
		$this->admin_message_text .= $message_text;
	}

	/**
	 * Display message for specific amount of time.
	 *
	 * @param integer $display_time Seconds to display message.
	 * @return void
	 */
	public function display_message( $display_time = 0 ) {
		if ( trim( $this->admin_message_text ) != '' ) {
			$this->admin_message( $this->admin_message_text, $display_time );
		} else {
			$this->admin_message( $this->default_message_text, $display_time );
		}
	}
}
