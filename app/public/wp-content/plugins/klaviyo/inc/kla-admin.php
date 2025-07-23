<?PHP
/**
 * WPKlaviyoAdmin.
 *
 * @package WooCommerceKlaviyo
 * @version 2.0.0
 */

/**
 * Handles settings page and menu item..
 */
class WPKlaviyoAdmin {

	const CONFLICTING_PLUGINS = array(
		'wp-security-hardening/wp-hardening.php',  // WP Hardening
		'falcon/falcon.php',  // Falcon
		'wp-force-login/wp-force-login.php',  // Force Login
		'w3-total-cache/w3-total-cache.php',  // W3 Total Cache
		'eps-301-redirects/eps-301-redirects.php',  // 301 Redirects
		'jwt-authentication-for-wp-rest-api/jwt-auth.php',  // JWT Authentication
		'password-protected/password-protected.php',  // Password Protected
		'wp-rocket/wp-rocket.php',  // WP Rocket
		'sitepress‑multilingual‑cms/sitepress.php',  // WPML
		'make-paths-relative/make-paths-relative.php',  // Make paths relative
	);

	const SMS_DISCLOSURE_TEXT = 'By checking this box and entering your phone number above, you consent to receive marketing text messages (such as [promotion codes] and [cart reminders]) from [company name] at the number provided, including messages sent by autodialer. Consent is not a condition of any purchase. Message and data rates may apply. Message frequency varies. You can unsubscribe at any time by replying STOP or clicking the unsubscribe link (where available) in one of our messages. View our Privacy Policy [link] and Terms of Service [link].';

	/**
	 * Klaviyo plugin options.
	 *
	 * @var array
	 */
	private $klaviyo_options;

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		$this->klaviyo_options = get_option( 'klaviyo_settings' );

		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'plugins_loaded', array( $this, 'setup_admin' ) );
	}

	/**
	 * Include dependent files.
	 *
	 * @return void
	 */
	public function includes() {
		include_once KLAVIYO_PATH . 'includes/admin/class-kl-plugins-screen-updates.php';
	}

	/**
	 * Runs before admin_notices action and adds a wrapper div so we can hide the notices.
	 */
	public function inject_before_notices() {
		if ( $this->is_klaviyo_settings_page() ) {
			echo '<div class="klaviyo-layout__notice-list-hide" id="kl__notice-list">';
		}
	}

	/**
	 * Runs after admin_notices action and closes a wrapper div.
	 */
	public function inject_after_notices() {
		if ( $this->is_klaviyo_settings_page() ) {
			echo '</div>';
		}
	}

	/**
	 * Add the Klaviyo plugin style sheets for the admin area.
	 *
	 * @param string $hook The action slug so we can only enqueue for Klaviyo's settings page.
	 */
	public function enqueue_styles( $hook ) {
		if ( strpos( $hook, 'page_klaviyo_settings' ) !== false ) {
			wp_enqueue_style( 'wck-admin-settings', KLAVIYO_URL . 'includes/admin/css/wck-admin.css', null, WCK_API::VERSION );
		}
	}

	/**
	 * Handle settings page dependencies and add appropriate menu page. Also hide notices for new auth process.
	 */
	public function setup_admin() {
		if (! function_exists('add_menu_page') || ! current_user_can('manage_options')) {
			return;
		}
		// Continue supporting non-WooCommerce sites. Display old settings page if WC is not activated.
		if (is_plugin_active('woocommerce/woocommerce.php')) {
			// The last arg (int) ensures the submenu item is added at the end.
			add_action('admin_menu', array( $this, 'add_klaviyo_settings_oauth' ), 1000);
			// Hook into action as early as possible so it's the first to execute.
			add_action('admin_notices', array( $this, 'inject_before_notices' ), -9999);
			// Hook into action as late as possible so it's the last to execute.
			add_action('admin_notices', array( $this, 'inject_after_notices' ), PHP_INT_MAX);
		} else {
			add_action('admin_menu', array( $this, 'add_klaviyo_settings_original' ));
		}

		// This adds the "Settings" link to the Klaviyo plugin entry on the Installed Plugins page.
		add_filter('plugin_action_links_' . KLAVIYO_BASENAME, array( $this, 'plugin_settings_link' ));
	}

	/**
	 * Check if we are on the Klaviyo specific admin page nested under Marketing. The legacy settings page does not
	 * use this method. We cannot use get_post() here because this fires before the post data is setup. Instead, this
	 * uses the WP_Screen object which is globally available under the 'current_screen' key. The WP_Screen object
	 * base attribute corresponds to the $parent_slug and $menu_slug arguments in the add_submenu_page call.
	 *
	 * @return bool Whether the page is for Klaviyo's plugin settings nested under WooCommerce's Marketing tab.
	 */
	private function is_klaviyo_settings_page() {
		return 'marketing_page_klaviyo_settings' === $GLOBALS['current_screen']->base;
	}

	/**
	 * Add Klaviyo menu tab nested under WooCommerce's Marketing tab for oauth authentication process.
	 */
	public function add_klaviyo_settings_oauth() {
		add_submenu_page('woocommerce-marketing', 'Klaviyo settings', 'Klaviyo', 'manage_woocommerce', 'klaviyo_settings', array( $this, 'settings_oauth' ));
	}

	/**
	 * Checks active plugins against known conflicts list and returns an array of plugin names.
	 *
	 * @return array
	 */
	private function get_conflicting_plugins() {
		$installed_plugins = get_plugins();
		$active_plugins    = array_flip(get_option('active_plugins'));
		$active_conflicts  = array_intersect_key($installed_plugins, $active_plugins, array_flip(self::CONFLICTING_PLUGINS));
		$conflicts         = array_values(array_map(function ( $plugin_details ) {
return $plugin_details['Name'];
		}, $active_conflicts));
		return $conflicts;
	}

	/**
	 * Formats conflicting plugin names for display in warning.
	 *
	 * @param $plugins array
	 * @return mixed|string
	 */
	private function format_conflicting_plugins( $plugins ) {
		switch (count($plugins)) {
			case 0:
				return '';
			case 1:
				return $plugins[0];
			case 2:
				return $plugins[0] . ' and ' . $plugins[1];
			default:
				$last = array_pop($plugins);
				return implode(', ', $plugins) . ', and ' . $last;
		}
	}

	private function is_integrated() {
		return isset($this->klaviyo_options['klaviyo_public_api_key']);
	}

	private function display_ssl_error_notification() {
		return ! $this->is_integrated() && ! is_ssl();
	}

	/**
	 * Add Klaviyo menu tab for old authentication process.
	 */
	public function add_klaviyo_settings_original() {
		global $submenu;

		add_menu_page('Klaviyo', 'Klaviyo', 'manage_options', 'klaviyo_settings', array( $this, 'settings' ), KLAVIYO_URL . 'img/klaviyo-logo.png');
		add_submenu_page('klaviyo_settings', 'Help', 'Help', 'manage_options', 'klaviyo_help', array( $this, 'help' ));
		// Rename the slide-out menu option from Klaviyo to Settings.
		$submenu['klaviyo_settings'][0][0] = 'Settings'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/**
	 * Settings page content for new authentication process.
	 */
	public function settings_oauth() {
		include_once KLAVIYO_PATH . '/includes/admin/partials/wc-v1-auth-settings.php';
	}

	/**
	 * Help section content.
	 *
	 * @return void
	 */
	public function help() {
		$content = '';
		$content = '<ol>
                      <li><a href="#help-1">Where do I find my Klaviyo API keys?</a></li>
                      <li><a href="#help-4">How do I add a Klaviyo email sign up into my sidebar?</a></li>
                    </ol>
                    <p><a name="help-1"></a><h2>1) Where do I find my Klaviyo API keys?</h2></p>
                    <p>
                      You can find your Klaviyo API keys by going to the
                      <a href="https://www.klaviyo.com/account#api-keys-tab">account page</a> in Klaviyo.
                      Your <strong>public</strong> API key will be 6-7 characters long.
                      Your <strong>private</strong> API key will be 7 characters, a hyphen and then 16 more.<br /><br />

                      Once you have connected your Klaviyo account, tracking will be enabled for visitors.
                    </p>
                    <p><a name="help-2"></a><h2>2) How do I add a Klaviyo email sign up into my sidebar?</h2></p>
                    <p>
                      Make sure you have connected your Klaviyo account on the Klaviyo settings page.<br />
                      Then you can find the widget under Appearance &raquo; Widgets titled &quot;Klaviyo: Email Sign Up&quot;.
                    </p>';

		$content = $this->postbox( 'klaviyo-help', 'FAQ', $content );
		$this->admin_wrap( 'Klaviyo Plugin Help', $content );
	}

	/**
	 * Legacy settings page form content.
	 *
	 * @return void
	 */
	public function settings() {
		$klaviyo_settings = $this->process_settings();
		$content          = '';

		if ( function_exists( 'wp_nonce_field' ) ) {
			$content .= wp_nonce_field( 'klaviyo-update-settings', '_wpnonce', true, false );
		}

		$content .= '<div xmlns="http://www.w3.org/1999/html">
                    <form>
                        <section style="margin:20px 0px 20px">
                            <label for="klaviyo_public_api_key"><b>Public API Key</b></label>
                            <input type="text" class="regular-text" name="klaviyo_public_api_key" style="display:block" value="' . WCK()->options->get_klaviyo_option( 'klaviyo_public_api_key', null ) . '" />
                            <p style="margin: 2px"><small>You can find them on your <a href="https://www.klaviyo.com/account#api-keys-tab">Klaviyo account page</a></small></p>
                        </section>

                        <section style="margin:20px 0px 20px">
                            <p>
                                <b style="font-size: larger">Subscribe contacts at checkout</b></br>
                                <small>Contacts will be subscribed to the specified list when they click "Place Order"</small>
                            </p>

                            <label style="display:block" for="klaviyo_newsletter_list_id"><b>Klaviyo List ID for Email</b></label>
                            <input type="text" class="regular-text" name="klaviyo_newsletter_list_id" placeholder="Email list ID" style="display:block" value="' . WCK()->options->get_klaviyo_option( 'klaviyo_newsletter_list_id', null ) . '" />

                            <label style="display:block;margin:5px 0px 0px 0px" for="klaviyo_sms_list_id"><b>Klaviyo List ID for SMS</b></label>
                            <input type="text" class="regular-text" name="klaviyo_sms_list_id" placeholder="SMS list ID" style="display:block" value="' . WCK()->options->get_klaviyo_option( 'klaviyo_sms_list_id', null ) . '" />

                            <p style="margin: 2px"><small><a href="https://help.klaviyo.com/hc/en-us/articles/115005078647-Find-a-List-ID">How to find List ID</a></small></p>

                        </section>

                        <section style="margin:20px 0px 20px">
                            <p style="margin-bottom: 5px"><b style="font-size: large"> Email </b></p>
                            <input type="checkbox" name="klaviyo_subscribe_checkbox" value="true" ' . checked( WCK()->options->get_klaviyo_option( 'klaviyo_subscribe_checkbox', false ), 'true', false ) . ' />
                            <label for="klaviyo_subscribe_checkbox">Subscribe contacts to email marketing</label>

                            <p style="margin:2px"><small>Adds a checkbox to the checkout page for opt-in</small></p>
                            <label style="display:block;margin:10px 0px" for="klaviyo_newsletter_text"><b>Subscribe to newsletter text</b></label>
                            <input type="text" class="regular-text" name="klaviyo_newsletter_text" placeholder="Subscribe to email updates" style="display:block;margin:0px;width=100%" value="' . WCK()->options->get_klaviyo_option( 'klaviyo_newsletter_text', null ) . '" />

                            <p style="margin-bottom: 5px"><b style="font-size: large"> SMS </b></p>
                            <input type="checkbox" name="klaviyo_sms_subscribe_checkbox" value="true" ' . checked( WCK()->options->get_klaviyo_option( 'klaviyo_sms_subscribe_checkbox', false ), 'true', false ) . ' />
                            <label for="klaviyo_sms_subscribe_checkbox">Subscribe contacts to SMS marketing</label>

                            <p style="margin:2px"><small>Adds a checkbox to the checkout page for opt-in. You need to first <a href="https://help.klaviyo.com/hc/en-us/articles/360039190611-On-Demand-Training-Getting-Started-with-Klaviyo-SMS">set up SMS in Klaviyo</a></small></p>
                            <label style="display:block;margin:10px 0px" for="klaviyo_sms_consent_text"><b>SMS opt-in checkbox text</b></label>
                            <input type="text" class="regular-text" name="klaviyo_sms_consent_text" placeholder="Subscribe to SMS updates" style="display:block;margin:0px;width=100%" value="' . WCK()->options->get_klaviyo_option( 'klaviyo_sms_consent_text', null ) . '" />

                        </section>

                        <section style="margin:20px 0px 20px">
                            <label style="display:block;margin:10px 0px" for="klaviyo_sms_consent_disclosure_text"><b>SMS consent disclosure text</b></label>
                            <textarea rows="10" cols="20" class="regular-text" name="klaviyo_sms_consent_disclosure_text" placeholder="' . self::SMS_DISCLOSURE_TEXT . '" >' . WCK()->options->get_klaviyo_option( 'klaviyo_sms_consent_disclosure_text', null ) . '</textarea>

                            <p style="margin:2px"><small>You must include disclosure language for TCPA compliance. You should also update your Terms of Service and Privacy Policy to include the terms of your SMS marketing program</small></p>
                            <p><a href="https://help.klaviyo.com/hc/en-us/articles/360035055312-About-US-SMS-Compliance-Laws">Learn more about SMS consent and compliance</a></p>
                        </section>
                    </form>
                    </div>';

		$wrapped_content = $this->postbox( 'klaviyo-settings', 'Connect to Klaviyo', $content );

		$this->admin_wrap( 'Klaviyo Settings', $wrapped_content );
	}

	/**
	 * Legacy settings page form processing logic.
	 *
	 * @return mixed
	 */
	public function process_settings() {
		$klaviyo_notification = new WPKlaviyoNotification( 'settings_update' );

		if ( ! empty( $_POST['klaviyo_option_submitted'] ) ) {
			$klaviyo_settings = get_option( 'klaviyo_settings' );

			if ( isset( $_GET['page'] ) && 'klaviyo_settings' == $_GET['page'] && check_admin_referer( 'klaviyo-update-settings' ) ) {
				if ( isset( $_POST['klaviyo_public_api_key'] ) && strlen( sanitize_text_field( wp_unslash( $_POST['klaviyo_public_api_key'] ) ) ) < 8 ) {
					$klaviyo_settings['klaviyo_public_api_key'] = sanitize_text_field( wp_unslash( $_POST['klaviyo_public_api_key'] ) );
				}

				$klaviyo_setting_keys     = array(
					'klaviyo_public_api_key',
					'klaviyo_subscribe_checkbox',
					'klaviyo_newsletter_list_id',
					'klaviyo_newsletter_text',
					'klaviyo_sms_subscribe_checkbox',
					'klaviyo_sms_list_id',
					'klaviyo_sms_consent_text',
					'klaviyo_sms_consent_disclosure_text',
				);
				$klaviyo_updated_settings = array_fill_keys( $klaviyo_setting_keys, '' );

				// Only iterate through relevant keys in $_POST.
				foreach ( array_intersect_key( $_POST, array_flip( $klaviyo_setting_keys ) ) as $key => $value ) {
					if ( in_array( $key, array( 'klaviyo_newsletter_text', 'klaviyo_sms_consent_text', 'klaviyo_sms_consent_disclosure_text' ) ) ) {
						$value = trim( stripslashes( $value ) );
					}

					$klaviyo_updated_settings[ $key ] = $value;
				}

				$klaviyo_settings = array_merge( $klaviyo_settings, $klaviyo_updated_settings );

				if ( empty( $klaviyo_settings['klaviyo_sms_consent_disclosure_text'] ) ) {
					$klaviyo_settings['klaviyo_sms_consent_disclosure_text'] = self::SMS_DISCLOSURE_TEXT;
				}

				if ( $klaviyo_settings['klaviyo_subscribe_checkbox'] && ! $klaviyo_settings['klaviyo_newsletter_list_id'] ) {
					$klaviyo_notification->admin_message( 'add_email_list_id', 10 );
				}

				if ( $klaviyo_settings['klaviyo_sms_subscribe_checkbox'] && ! $klaviyo_settings['klaviyo_sms_list_id'] ) {
					$klaviyo_notification->admin_message( 'add_sms_list_id', 10 );
				}

				if (
					$klaviyo_settings['klaviyo_sms_list_id'] == $klaviyo_settings['klaviyo_newsletter_list_id']
					&& ! empty( $klaviyo_settings['klaviyo_sms_list_id'] )
					&& ! empty( $klaviyo_settings['klaviyo_newsletter_list_id'] )
				) {
					$klaviyo_notification->admin_message( 'same_list_ids', 10 );
				}

				$klaviyo_notification->display_message( 3 );
				update_option( 'klaviyo_settings', $klaviyo_settings );
			}
		}

		return get_option( 'klaviyo_settings' );
	}

	/**
	 * Callback method for "plugin_action_links_{$plugin_file}" hook. By default, this does not include
	 * a "settings" link in the Klaviyo entry of the Installed Plugins tab. This adds the Settings link.
	 *
	 * @param $links
	 * @return mixed
	 */
	public function plugin_settings_link( $links ) {
		$settings_link = '<a href="' . KLAVIYO_ADMIN . 'admin.php?page=klaviyo_settings">Settings</a>';
		array_unshift($links, $settings_link);

		return $links;
	}

	/**
	 * Plugin support panel content.
	 *
	 * @return string
	 */
	private function show_plugin_support() {
		$content = '<p>First, check the <a href="' . KLAVIYO_ADMIN . 'admin.php?page=klaviyo_help">Help Section</a>. If you still have questions or want to give feedback, send an email to <a href="http://docs.klaviyo.com/customer/portal/emails/new">Klaviyo support.</a></p>';
		return $this->postbox( 'klaviyo-support', 'Help / Feedback', $content );
	}

	/**
	 * Return a collection of elements.
	 *
	 * @param string $id Element id attribute.
	 * @param string $title Content title.
	 * @param string $content Content.
	 * @return string
	 */
	private function postbox( $id, $title, $content ) {
		$wrapper  = '';
		$wrapper .= '<div id="' . $id . '" class="postbox">';
		$wrapper .= '<div class="handlediv" title="Click to toggle"><br /></div>';
		$wrapper .= '<h2 class="hndle" style="font-size: large"><span>' . $title . '</span></h2>';
		$wrapper .= '<div class="inside">' . $content . '</div>';
		$wrapper .= '</div>';
		return $wrapper;
	}

	/**
	 * Wraps some content in other elements.
	 *
	 * @param string $title Header title.
	 * @param string $content Content.
	 * @return void
	 */
	private function admin_wrap( $title, $content ) {

		$showpluginsupport = $this->show_plugin_support();
        // phpcs:disable
		echo <<<EOT
        <div class="wrap">
          <div class="dashboard-widgets-wrap">
            <h2>{$title}</h2>
            <form method="post" action="">
              <div id="dashboard-widgets" class="metabox-holder">
                <div class="postbox-container" style="width:60%;">
                  <div class="meta-box-sortables ui-sortable">
                     {$content}
                    <p class="submit">
                      <input type="submit" name="klaviyo_option_submitted" class="button-primary" value="Save Settings" />
                    </p>
                  </div>
                </div>
                <div class="postbox-container" style="width:40%;">
                  <div class="meta-box-sortables ui-sortable">
                    {$showpluginsupport}
                  </div>
                </div>
                </div>
            </form>
          </div>
        </div>
EOT;
        // phpcs:enable
	}
}
