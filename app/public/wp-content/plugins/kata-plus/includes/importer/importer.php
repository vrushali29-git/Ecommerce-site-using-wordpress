<?php

/**
 * Importer Class.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if (! defined('ABSPATH')) {
	exit;
}

use Elementor\Utils;
use Elementor\Plugin;

if (! class_exists('Kata_Plus_Importer')) {
	class Kata_Plus_Importer
	{

		/**
		 * The directory of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $dir;

		/**
		 * The url of the file.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $url;

		/**
		 * The url of Kata API.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $api_url;

		/**
		 * Demo data.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $demos_data;

		/**
		 * The directory of demo folder.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $demos_data_dir;

		/**
		 * The url protocol.
		 *
		 * @access  public
		 * @var     string
		 */
		public static $protocol;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_Importer
		 */
		public static $instance;

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
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct()
		{
			$this->definitions();
			$this->actions();
			$this->dependencies();
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions()
		{
			self::$dir            = Kata_Plus::$dir . 'includes/importer/';
			self::$url            = Kata_Plus::$url . 'includes/importer/';
			self::$demos_data_dir = Kata_Plus::$upload_dir . '/importer/';
			self::$protocol       = class_exists('Kata_Plus_Helpers') ? Kata_Plus_Helpers::ssl_url() : 'https://';
			self::$api_url        = self::$protocol . 'katademos.climaxthemes.com/Demo';
		}

		/**
		 * Add actions.
		 *
		 * @since   1.0.0
		 */
		public function actions()
		{
			if (isset($_GET['page']) && $_GET['page'] == 'kata-plus-demo-importer') {
				add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
				add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
				add_action('kata_plus_before_start_importer', array($this, 'build_importer_dir'));
				add_action('admin_footer', array($this, 'update_elementor_kit'));
			}
			add_action('wp_ajax_ImporterBuildSteps', array($this, 'BuildSteps'));
			add_action('wp_ajax_reset_site', array($this, 'reset_site'));
			add_action('wp_ajax_BuildImporter', array($this, 'BuildImporter'));
			add_action('wp_ajax_Importer', array($this, 'Importer'));
			add_action('wp_ajax_ImportDone', array($this, 'ImportDone'));
			add_action('wp_ajax_activate_elementor_container', array($this, 'activate_elementor_container'));
			add_filter('pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false');

			// Disable Slider Revolution Redirection
			if (class_exists('RevSliderAdmin')) {
				$RevSliderAdmin = new RevSliderAdmin();
				remove_action('admin_init', array($RevSliderAdmin, 'open_welcome_page'));
			}
		}

		/**
		 * Load dependencies.
		 *
		 * @since   1.0.0
		 */
		public function dependencies()
		{
			Kata_Plus_Autoloader::load(self::$dir . 'core', 'API');
		}

		/**
		 * Build Importer Directory.
		 *
		 * @since   1.0.0
		 */
		public function build_importer_dir()
		{
			if (! wp_mkdir_p(self::$demos_data_dir)) {
				wp_mkdir_p(self::$demos_data_dir);
			}
		}

		/**
		 * Get Demo Data From API.
		 *
		 * @since   1.0.0
		 */
		public static function get_demo_data($action, $key = false, $method = false)
		{

			$data = '{}';
			switch ($action) {
				case 'List':
					$data = json_decode(wp_remote_request(static::$api_url . '/' . $action . '/')['body']);
					$data = isset($data->list) ? $data->list : $data;
					break;
				case 'Contents':
					$data = json_decode(wp_remote_request(static::$api_url . '/' . $action . '/' . $key)['body']);
					break;
				case 'Information':
					$data = json_decode(wp_remote_request(static::$api_url . '/' . $action . '/' . $key)['body']);
					break;
				case 'Plugins':
					$data = json_decode(wp_remote_request(static::$api_url . '/' . $action . '/' . $key)['body']);
					break;
				case 'Content':
					$data = json_decode(wp_remote_request(static::$api_url . '/' . $action . '/' . $key . '/' . $method)['body']);
					break;
				case 'Images':
					$data = json_decode(wp_remote_request(static::$api_url . '/' . $action . '/' . $key)['body']);
					break;
			}

			return $data;
		}

		/**
		 * Demos List.
		 *
		 * @since   1.0.0
		 */
		public function demos()
		{
			return Kata_Plus_API::get_instance()->demo('List');
		}

		/**
		 * GoPro.
		 *
		 * @since   1.0.0
		 */
		public function GoPro($demo_name, $screenshot, $is_pro)
		{
			if ($is_pro && ! class_exists('Kata_Plus_pro')) {
				$this->access_denied_step($demo_name, $screenshot, '<p style="margin-top: 0;">' . __('To import Pro & Fast demos you must buy the pro version', 'kata-plus') . '</p><div class="ktp-go-pro"><a href="' . esc_url('https://my.climaxthemes.com/buy/') . '" class="kata-btn"> ' . __('Buy Pro version', 'kata-plus') . '</a></div>');
				return false;
			} elseif (class_exists('Kata_Plus_Pro_license_Activation') && class_exists('Kata_Plus_pro') && $is_pro) {
				$license = new Kata_Plus_Pro_license_Activation(Kata_Plus_Pro::$version, Kata_Plus_Pro::$slug);

				if ('Please fill all parameters.' == $license->getRemote_package()) {
					$this->access_denied_step($demo_name, $screenshot, '<p style="margin-top: 0;">' . __('Please enter your license activation key', 'kata-plus') . '</p><div class="ktp-go-pro"><a href="' . esc_url(admin_url('?page=kata-plus-theme-activation')) . '" target="_blank" class="kata-btn"> ' . __('Enter license', 'kata-plus') . '</a></div>');
					return false;
				}
				return true;
			}
		}

		/**
		 * Header modal.
		 *
		 * @since   1.0.0
		 */
		public function HeaderModal($name)
		{
?>
			<ul class="kata-import-wizard-header">
				<li class="kt-importer-step kt-last-step" data-step="2"> <a href="#"><span>3. </span><?php echo esc_html__('Import Progress', 'kata-plus'); ?></a> </li>
				<li class="kt-importer-step" data-step="1"> <a href="#"><span>2. </span><?php echo esc_html__('Select Content', 'kata-plus'); ?></a> </li>
				<li class="kt-importer-step kt-active-step" data-step="0"> <a href="#"> <span>1. </span><?php echo esc_html__('Install Plugins', 'kata-plus'); ?></a> </li>
			</ul>
			<h2 class="kata-import-demo-title"><?php echo esc_html($name); ?></h2>
		<?php
		}

		/**
		 * Body Modal.
		 *
		 * @since   1.0.0
		 */
		public function BuildSteps()
		{
			check_ajax_referer('kata_importer_nonce', 'nonce');

			$demo_option_in18 = array(
				__('pages', 'kata-plus'),
				__('media', 'kata-plus'),
				__('templates', 'kata-plus'),
				__('menu', 'kata-plus'),
				__('options', 'kata-plus'),
				__('contact form', 'kata-plus'),
				__('fonts', 'kata-plus'),
				__('Shop Pages', 'kata-plus'),
				__('My account', 'kata-plus'),
				__('Product Loops', 'kata-plus'),
				__('size chart', 'kata-plus'),
				__('product badges', 'kata-plus'),
				__('Essentials', 'kata-plus'),
				__('products', 'kata-plus'),
				__('header, footer...', 'kata-plus'),
			);

			// allow upload svg
			if (is_multisite()) {
				$FileToImport = explode(' ', get_site_option('upload_filetypes'));
				$size         = sizeof($FileToImport);
				if (! in_array('svg', $FileToImport)) {
					$FileToImport[$size] = 'svg';
					$FileToImport          = implode(' ', $FileToImport);
					update_site_option('upload_filetypes', $FileToImport);
				}
			}

			$settings = $_POST;
			$key      = $settings['key'];
			if (! isset($key)) {
				return;
			}

			$key            = $key;
			$demo_name      = $settings['name'];
			$screenshot     = $settings['screenshot'];
			$demo_url       = $settings['demo_url'];
			$authentication = Kata_Plus_API::get_instance()->authenticate($key);

			$demo_data = Kata_Plus_API::get_instance()->demo('Information', $key);

			$is_pro               = $demo_data->is_pro;
			$is_hidden            = $demo_data->is_hidden;
			$required_plugins_arr = array();

			if (isset($demo_data->data->plugins)) {
				foreach ($demo_data->data->plugins as $plugin) {
					$required_plugins_arr[] = $plugin->slug;
				}
				$plugins          = TGM_Plugin_Activation::$instance->plugins;
				$tgmpa_list_table = new TGMPA_List_Table();
				$required_plugins = array();
			}

			$demo_deta_contents = array();
			if (isset($demo_data->data->contents)) {
				$demo_deta_contents = $demo_data->data->contents;
				if (isset($demo_deta_contents->builders)) {
					$builders = $demo_deta_contents->builders;
					unset($demo_deta_contents->builders);
					$demo_deta_contents->builders = $builders;
				}
			}

			$this->GoPro($demo_name, $screenshot, $is_pro);

		?>

			<i class="ti-close"></i>
			<div class="kata-lightbox-contents">
				<?php if ($demo_data) : ?>
					<div class="kata-lightbox">
						<?php
						$modal_title = isset($_POST['currnet_location']) && $_POST['currnet_location'] == 'fastmode' ? __('Website Installation', 'kata-plus') : $demo_data->data->name;
						self::HeaderModal($modal_title);
						?>
						<input type="hidden" name="demo_url" class="demo_url" value="<?php echo esc_attr($demo_url); ?>">
						<div class="kata-lightbox-content kata-importer-settings">

							<?php $this->requirements($settings); ?>

							<?php if (isset($_POST['currnet_location']) && $_POST['currnet_location'] == 'fastmode') { ?>
								<div class="steps step1 active" data-tab="step3">
									<div class="kata-col-import-demo-image">
										<img src="<?php echo esc_url($demo_data->data->images[0]); ?>" alt="<?php echo esc_attr($demo_data->data->name); ?>">
									</div>
									<div class="kata-col-import-demo">
										<h3><?php echo esc_html__('Importing in Progress', 'kata-plus'); ?></h3>
										<div class="meter">
											<span style="width: 0;"></span>
										</div>
										<div class="kata-importer-tasks">
											<ul class="tasks">

											</ul>
										</div>
										<!-- end .kata-import-content-wrap -->
									</div>
								</div>
								<div class="steps step2" data-tab="step2">
									<div class="kata-col-import-demo-image">
										<img src="<?php echo esc_url($demo_data->data->images[0]); ?>" alt="<?php echo esc_attr($demo_data->data->name); ?>">
									</div>
									<div class="kata-col-import-demo">
										<div class="kata-import-content-wrap wp-clearfix">
											<div class="kata-checkbox-wrap all">
												<input type="checkbox" class="kata-checkbox-input all" id="all" name="import_content" value="all">
												<label for="all" class="kata-checkbox-label"></label>
												<span><?php echo __('All', 'kata-plus') ?></span>
											</div>
											<?php
											foreach ($demo_deta_contents as $type => $content) {
											?>
												<div class="kata-checkbox-wrap <?php echo esc_attr($type); ?>">
													<input type="checkbox" class="kata-checkbox-input <?php echo esc_attr($type); ?>" data-type="<?php echo esc_attr($type); ?>" name="import_content" value="<?php echo esc_attr($content); ?>">
													<label for="<?php echo esc_attr($type); ?>" class="kata-checkbox-label"></label>
													<span>
														<?php
														$name = str_replace('_', ' ', self::import_option_name($type));
														echo esc_html__($name, 'kata-plus');
														?>
													</span>
												</div>
											<?php
											}
											?>
										</div>
										<div class="kata-import-demo-btn-wrap">
											<a href="#" class="kata-import-demo-btn disabled" data-key="<?php echo esc_attr($key); ?>"><?php echo esc_html__('Import', 'kata-plus'); ?></a>
											<!-- <a href="#" class="kata-import-demo-reset" data-details="<?php // echo esc_attr( $key );
																											?>"><span class="updating-message dashicons dashicons-update-alt"></span><?php // echo esc_html__('Uninstall', 'kata-plus');
																																														?></a> -->
										</div>
									</div>
								</div>
								<div class="steps step3" data-tab="step1">
									<div class="kata-col-import-demo-image">
										<img src="<?php echo esc_url($demo_data->data->images[0]); ?>" alt="<?php echo esc_attr($demo_data->data->name); ?>">
									</div>
									<div class="kata-col-import-demo">
										<div class="kata-install-plugins">
											<h3><?php echo esc_html__('Required plugins', 'kata-plus'); ?></h3>
											<a href="#" class="kata-btn kata-btn-install-plugins" data-action="<?php echo esc_attr__('install', 'kata-plus'); ?>"><?php echo esc_html__('Install & Activate All Plugins', 'kata-plus'); ?></a>
										</div>
										<div class="kata-required-plugins">
											<?php
											if ($required_plugins_arr) {
												foreach ($required_plugins_arr as $slg) {
													if (array_key_exists($slg, $plugins)) {
														$required_plugins[$slg] = $plugins[$slg];
													}
												}
											}
											if ($required_plugins) {
												foreach ($required_plugins as $plugin) {
													$plugin['type']             = isset($plugin['type']) ? $plugin['type'] : 'recommended';
													$plugin['sanitized_plugin'] = $plugin['name'];
													$plugin_action              = $tgmpa_list_table->kata_plus_actions_plugin($plugin);
													$is_plugin_active_class     = TGM_Plugin_Activation::$instance->is_plugin_active($plugin['slug']) ? ' active' : '';
											?>
													<div class="kata-required-plugin<?php echo esc_attr($is_plugin_active_class); ?>" data-plugin-name="<?php echo esc_attr($plugin['name']); ?>">
														<h4><?php echo esc_html($plugin['name']); ?></h4>
														<span class="kata-required-plugin-line"></span>
														<?php echo wp_kses_post($plugin_action); ?>
													</div>
											<?php
												}
											}
											?>
										</div>
									</div>
								</div>
							<?php } else { ?>
								<div class="steps step3" data-tab="step1">
									<div class="kata-col-import-demo-image">
										<img src="<?php echo esc_url($demo_data->data->images[0]); ?>" alt="<?php echo esc_attr($demo_data->data->name); ?>">
									</div>
									<div class="kata-col-import-demo">
										<div class="kata-install-plugins">
											<h3><?php echo esc_html__('Required plugins', 'kata-plus'); ?></h3>
											<a href="#" class="kata-btn kata-btn-install-plugins" data-action="<?php echo esc_attr__('install', 'kata-plus'); ?>"><?php echo esc_html__('Install & Activate All Plugins', 'kata-plus'); ?></a>
										</div>
										<div class="kata-required-plugins">
											<?php
											if ($required_plugins_arr) {
												foreach ($required_plugins_arr as $slg) {
													if (array_key_exists($slg, $plugins)) {
														$required_plugins[$slg] = $plugins[$slg];
													}
												}
											}
											if (isset($required_plugins)) {
												foreach ($required_plugins as $plugin) {
													$plugin['type']             = isset($plugin['type']) ? $plugin['type'] : 'recommended';
													$plugin['sanitized_plugin'] = $plugin['name'];
													$plugin_action              = $tgmpa_list_table->kata_plus_actions_plugin($plugin);
													$is_plugin_active_class     = TGM_Plugin_Activation::$instance->is_plugin_active($plugin['slug']) ? ' active' : '';
											?>
													<div class="kata-required-plugin<?php echo esc_attr($is_plugin_active_class); ?>" data-plugin-name="<?php echo esc_attr($plugin['name']); ?>">
														<h4><?php echo esc_html($plugin['name']); ?></h4>
														<span class="kata-required-plugin-line"></span>
														<?php echo wp_kses_post($plugin_action); ?>
													</div>
											<?php
												}
											}
											?>
										</div>
									</div>
								</div>
								<div class="steps step2" data-tab="step2">
									<div class="kata-col-import-demo-image">
										<img src="<?php echo esc_url($demo_data->data->images[0]); ?>" alt="<?php echo esc_attr($demo_data->data->name); ?>">
									</div>
									<div class="kata-col-import-demo">
										<div class="kata-import-content-wrap wp-clearfix">
											<div class="kata-checkbox-wrap all">
												<input type="checkbox" class="kata-checkbox-input all" id="all" name="import_content" value="all">
												<label for="all" class="kata-checkbox-label"></label>
												<span>All</span>
											</div>
											<?php
											foreach ($demo_deta_contents as $type => $content) {
											?>
												<div class="kata-checkbox-wrap <?php echo esc_attr($type); ?>">
													<input type="checkbox" class="kata-checkbox-input <?php echo esc_attr($type); ?>" data-type="<?php echo esc_attr($type); ?>" name="import_content" value="<?php echo esc_attr($content); ?>">
													<label for="<?php echo esc_attr($type); ?>" class="kata-checkbox-label"></label>
													<span>
														<?php echo str_replace('_', ' ', self::import_option_name($type)); ?>
													</span>
												</div>
											<?php
											}
											?>
										</div>
										<div class="kata-import-demo-btn-wrap">
											<a href="#" class="kata-import-demo-btn disabled" data-key="<?php echo esc_attr($key); ?>"><?php echo esc_html__('Import', 'kata-plus'); ?></a>
											<!-- <a href="#" class="kata-import-demo-reset" data-details="<?php // echo esc_attr( $key );
																											?>"><span class="updating-message dashicons dashicons-update-alt"></span><?php // echo esc_html__('Uninstall', 'kata-plus');
																																														?></a> -->
										</div>
									</div>
								</div>
								<div class="steps step1 active" data-tab="step3">
									<div class="kata-col-import-demo-image">
										<img src="<?php echo esc_url($demo_data->data->images[0]); ?>" alt="<?php echo esc_attr($demo_data->data->name); ?>">
									</div>
									<div class="kata-col-import-demo">
										<h3><?php echo esc_html__('Importing in Progress', 'kata-plus'); ?></h3>
										<div class="meter">
											<span style="width: 0;"></span>
										</div>
										<div class="kata-importer-tasks">
											<ul class="tasks">

											</ul>
										</div>
										<!-- end .kata-import-content-wrap -->
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php
			wp_die();
		}

		/**
		 * Displays a notice if the server resources do not meet the requirements for optimal performance during the demo import process.
		 *
		 * @throws None
		 * @return void
		 */
		public function requirements($settings)
		{
			$result         = Kata_Plus_Notices::EvaluateServerResources();
			$is_importer    = $settings['currnet_location'] == 'importer' ? true : false;
			$container      = \Elementor\Plugin::$instance->experiments->is_feature_active('container');
			$container_grid = \Elementor\Plugin::$instance->experiments->is_feature_active('container_grid');
		?>

			<?php if ((! $container || ! $container_grid) && $settings['e_con'] === 'true' && $is_importer) { ?>
				<div class="initial-notice">
					<h3><?php echo __('Notice', 'kata-plus'); ?></h3>
					<p><?php echo __('To successfully import this demo, you will need to activate the Elementor Flexbox Container and Grid Container. Without these containers, you may encounter a blank page after completing the import process. Please ensure that the Elementor containers are activated on your site for a smooth import experience.', 'kata-plus'); ?> </p>

					<div class="kata-checkbox-wrap importer-wraning warning-1">
						<input type="checkbox" class="kata-checkbox-input warning-2" value="warning-2">
						<label for="warning-2" class="kata-checkbox-label"><span><?php echo esc_html__('I consent to the activation of the Elementor container on my website.', 'kata-plus'); ?></span></label>
					</div>

					<div class="resume-import-progress-wrapper">
						<a href="#" class="resume-import-progress activation-e-con"><?php echo __('Activate & resume import process', 'kata-plus'); ?></a>
					</div>
				</div>
			<?php } ?>

			<?php if ($result && $is_importer) { ?>
				<div class="initial-notice">
					<h3><?php echo __('Attention', 'kata-plus'); ?></h3>
					<p><?php echo __('Your server configuration may not fully meet the Kata requirements for optimal performance during the demo import process. To ensure the best results, we suggest considering some enhancements to your server configuration. Your cooperation in this matter is greatly appreciated! ', 'kata-plus'); ?> </p>
					<?php echo wp_kses_post($result['html']); ?>
					<div class="kata-checkbox-wrap importer-wraning warning-1">
						<input type="checkbox" class="kata-checkbox-input warning-1" value="warning-1">
						<label for="warning-1" class="kata-checkbox-label"><span><?php echo esc_html__('I read the above notice. I want start import', 'kata-plus'); ?></span></label>
					</div>
					<div class="resume-import-progress-wrapper">
						<a href="#" class="resume-import-progress"><?php echo __('resume import process', 'kata-plus'); ?></a>
					</div>
				</div>
			<?php }
		}

		/**
		 * Displays a notice if the server resources do not meet the requirements for optimal performance during the demo import process.
		 *
		 * @throws None
		 * @return void
		 */
		public function activate_elementor_container()
		{
			check_ajax_referer('kata_importer_nonce', 'nonce');

			$container = \Elementor\Plugin::$instance->experiments->is_feature_active('container');
			$container_grid = \Elementor\Plugin::$instance->experiments->is_feature_active('container_grid');

			if (! $container) {
				update_option('elementor_experiment-container', 'active');
			}

			if (! $container) {
				update_option('elementor_experiment-container_grid', 'active');
			}

			wp_die();
		}


		/**
		 * Reset site content.
		 *
		 * @since   1.0.0
		 */
		public function reset_site()
		{
			check_ajax_referer('kata_importer_nonce', 'nonce');
			$action = sanitize_text_field($_POST['reset']);
			if ($action !== 'yes') {
				return wp_send_json(
					array(
						'message' => __('Sorry, You are not allowed to remove the site contents.', 'kata-plus'),
						'status'  => 'not_allowed',
					),
					200,
					1
				);
				wp_die();
			} else {
				// Clear Builders
				$Builders = get_posts(
					array(
						'post_type'   => 'kata_plus_builder',
						'numberposts' => -1,
					)
				);
				foreach ($Builders as $eachBuilder) {
					if (isset($eachBuilder->ID)) {
						wp_delete_post($eachBuilder->ID, true);
					}
				}
				// Clear Pages
				$Pages = get_posts(
					array(
						'post_type'   => 'page',
						'numberposts' => -1,
					)
				);
				foreach ($Pages as $eachPage) {
					if (isset($eachPage->ID)) {
						wp_delete_post($eachPage->ID, true);
					}
				}
				// Clear Posts
				$Posts = get_posts(
					array(
						'post_type'   => 'post',
						'numberposts' => -1,
					)
				);
				foreach ($Posts as $eachPost) {
					if (isset($eachPost->ID)) {
						wp_delete_post($eachPost->ID, true);
					}
				}
				// Clear contact form7
				$contact_form7 = get_posts(
					array(
						'post_type'   => 'wpcf7_contact_form',
						'numberposts' => -1,
					)
				);
				foreach ($contact_form7 as $eachcontact_form7) {
					if (isset($eachcontact_form7->ID)) {
						wp_delete_post($eachcontact_form7->ID, true);
					}
				}
				// Clear media
				$media = get_posts(
					array(
						'post_type'   => 'attachment',
						'numberposts' => -1,
					)
				);
				foreach ($media as $eachmedia) {
					if (isset($eachmedia->ID)) {
						wp_delete_post($eachmedia->ID, true);
					}
				}
				// Clean Menus
				$menus = wp_get_nav_menus();
				foreach ($menus as $menu) {
					if (isset($menu->term_id)) {
						wp_delete_nav_menu($menu->term_id);
					}
				}
				return wp_send_json(
					array(
						'message' => __('Remove site content task has been finished.', 'kata-plus'),
						'status'  => 'allowed',
					),
					200,
					1
				);
				wp_die();
			}
		}

		/**
		 * Importer Option name.
		 *
		 * @access public
		 * @return void
		 */
		public static function import_option_name($name)
		{
			if ($name == 'builders') {
				$name = 'header, footer...';
			}
			if ($name == 'woo_pages') {
				$name = 'Shop Pages';
			}
			if ($name == 'data_configuration') {
				$name = 'Essentials';
			}
			if ($name == 'woo_my_account_pages') {
				$name = 'My account';
			}
			if ($name == 'woo_product_loops') {
				$name = 'Product Loops';
			}
			if ($name == 'customizer') {
				$name = 'options';
			}
			return $name;
		}

		/**
		 * Access Denied Step.
		 *
		 * @access public
		 * @return void
		 */
		public function access_denied_step($demo_name, $screenshot, $message = false)
		{
			?>
			<i class="ti-close"></i>
			<div class="kata-lightbox-contents">
				<div class="kata-lightbox">
					<?php
					$demo_name   = isset($demo_data->data->name) ? $demo_data->data->name : '';
					$modal_title = (isset($_POST['currnet_location']) && $_POST['currnet_location'] == 'fastmode') ? __('Import Demo', 'kata-plus') : $demo_name;
					self::HeaderModal($modal_title);
					?>
					<div class="kata-lightbox-content kata-importer-settings">
						<div class="steps step2" data-tab="step2">
							<div class="kata-col-import-demo-image"></div>
						</div>
						<div class="steps step2" data-tab="step2">
							<div class="kata-col-import-demo-image"><img src="<?php echo esc_url($screenshot); ?>"></div>

							<div class="kata-col-import-demo">
								<div class="kata-import-demo-failed">
									<?php echo __('Demo Import Failed', 'kata-plus'); ?>
								</div>
								<div class="kata-imp-fail-tx">
									<?php
									if ($message) {
										echo wp_kses_post($message);
									} else {
										echo __('Please check', 'kata-plus') . ' .... <a href="' . admin_url('admin.php?page=kata-plus-help') . '" target="_blank">' . __('System Status', 'kata-plus') . '</a> ' . __('and try again', 'kata-plus') . '.';
									}
									?>
								</div>
							</div><!-- end .kata-col-import-demo -->
						</div> <!-- end .lightbox-content -->
						<div class="steps step2" data-tab="step2">
							<div class="kata-col-import-demo-image"></div>
						</div>
						<!-- end .kata-suc-imp-content-wrap -->
					</div> <!-- end .kata-lightbox -->
				</div> <!-- end .kata-lightbox -->
			</div> <!-- end .kata-lightbox-contents -->
			<?php
			wp_die();
		}

		/**
		 * Build Importer Data.
		 *
		 * @access public
		 * @return void
		 */
		public function BuildImporter()
		{
			check_ajax_referer('kata_importer_nonce', 'nonce');
			$settings   = $_POST;
			$key        = $settings['key'];
			$contents   = Kata_Plus_API::get_instance()->demo('Contents', $key)->contents;
			$upload_dir = Kata_Plus::$upload_dir . '/importer';
			if (! file_exists($upload_dir)) {
				mkdir($upload_dir, 0777);
			}
			$upload_dir .= '/' . $key;
			if (! file_exists($upload_dir)) {
				mkdir($upload_dir, 0777);
			}

			if (isset($settings['currnet_location']) && $settings['currnet_location'] != 'fastmode') {
			?>
				<li class="kata-importer-task-package kata-import-done" data-action="download_files"><?php echo esc_html__('Download Files', 'kata-plus'); ?></li>
				<?php
			}

			set_time_limit(300);
			$demo_data = $settings['demo_data'];
			foreach ($demo_data as $value) {
				if (! $value) {
					continue;
				}

				// Make request to get download URL
				$url_to_request = $contents->$value;
				$response = wp_remote_post($url_to_request);

				// Check if request failed or returned error status
				if (is_wp_error($response)) {
					// Skip this item and continue to next
					continue;
				}

				// Check response status code
				$response_code = wp_remote_retrieve_response_code($response);
				if ($response_code !== 200) {
					// Skip this item if not successful (404, 500, etc.)
					continue;
				}

				$response_body = wp_remote_retrieve_body($response);

				// Check if response body is empty
				if (empty($response_body)) {
					// Skip this item if no content
					continue;
				}

				$response_data = json_decode($response_body, true);

				// Check if JSON decode was successful
				if (json_last_error() !== JSON_ERROR_NONE) {
					// Skip this item if JSON is invalid
					continue;
				}

				if (isset($response_data['download_url'])) {
					$file_to_download = $response_data['download_url'];
				} else {
					// Fallback to original URL if no download_url found
					$file_to_download = $url_to_request;
				}

				$tmp_file = self::download_file($file_to_download);

				if (is_wp_error($tmp_file)) {
					// Skip this item if download failed
					continue;
				}

				// Sets file final destination.
				$filepath = $upload_dir . '/' . $value . '.zip';

				// Copies the file to the final destination and deletes temporary file.
				copy(strval($tmp_file), $filepath);
				@unlink($tmp_file);

				if (isset($settings['currnet_location']) && $settings['currnet_location'] != 'fastmode') {
				?>
					<li class="kata-importer-task-menus" data-action="<?php echo esc_attr($value); ?>" data-status="pending"><?php echo str_replace('_', ' ', self::import_option_name($value)); ?></li>
					<!-- kata-import-active -->
			<?php
				}
			}

			if (file_exists(WP_PLUGIN_DIR . '/' . 'kata-plus-pro/kata-plus-pro.php')) {
				deactivate_plugins('advanced-custom-fields-pro/acf.php');
			}

			wp_die();
		}

		/**
		 * Download file using WordPress download_url or fallback to cURL
		 *
		 * @param string $url URL to download
		 * @return string|WP_Error Path to downloaded file or WP_Error on failure
		 */
		private static function download_file($url)
		{
			// First try WordPress download_url
			$tmp_file = download_url($url);

			// If download_url failed, try cURL
			if (is_wp_error($tmp_file)) {
				$tmp_file = wp_tempnam(); // Create temporary file

				$ch = curl_init($url);
				$fp = fopen($tmp_file, 'wb');

				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 300);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

				$success = curl_exec($ch);

				if (curl_errno($ch)) {
					fclose($fp);
					curl_close($ch);
					@unlink($tmp_file);
					return new \WP_Error('download_error', curl_error($ch));
				}

				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if ($http_code !== 200) {
					fclose($fp);
					curl_close($ch);
					@unlink($tmp_file);
					return new \WP_Error('download_error', 'HTTP Error: ' . $http_code);
				}

				fclose($fp);
				curl_close($ch);
			}

			return $tmp_file;
		}

		/**
		 * Import Data.
		 *
		 * @access public
		 * @return void
		 */
		public function Importer()
		{
			check_ajax_referer('kata_importer_nonce', 'nonce');
			$settings = $_POST;
			$key      = $settings['key'];
			if (! class_exists('\OCDI\CustomizerImporter')) {
				Kata_Plus_Autoloader::load(self::$dir . 'core/libraries', 'one-click-demo-import');
			}
			if (! class_exists('Zip')) {
				require_once ABSPATH . 'wp-admin/includes/class-pclzip.php';
			}

			$upload_dir    = Kata_Plus::$upload_dir . '/importer/' . $key . '/';
			$zip_file_path = $upload_dir . $settings['import_item'] . '.zip';

			if (! file_exists($zip_file_path)) {
				static::print_output(
					array(
						'status'  => 'error',
						'message' => __('Can not find the source file', 'kata-plus'),
					)
				);
			}
			$import_item = $settings['import_item'];
			if ($import_item != 'sliders' && $import_item != 'fonts') {
				if (class_exists('PclZip')) {
					$zip = new PclZip($zip_file_path);
					$zip->extract(PCLZIP_OPT_PATH, $upload_dir);
				} else {
					$zip = new Zip();
					$zip->unzip_file($zip_file_path, $upload_dir);
				}
			}

			// replace climaxthemes.com with katademos.com
			foreach (glob($upload_dir . '*.xml') as $files) {
				$content = file_get_contents($files);
				$content = str_replace(
					array('climaxthemes.com\/kata\/', 'climaxthemes.com/kata/', 'climaxthemes.com/kata-blog/', 'climaxthemes.com\/kata-blog\/'),
					array('katademos.com\/', 'katademos.com/', 'katademos.com/kata-blog/', 'katademos.com\/kata-blog\/'),
					$content
				);
				file_put_contents($files, $content);
			}

			switch ($import_item) {
				case 'customizer':
					$file_path = $upload_dir . $import_item . '.dat';
					$results   = \CEI_Core::_custom_import($file_path);

					set_theme_mod('kata_show_page_title', 0);

					if (class_exists('\Styler\Utils\DataHandler')) {
						$customizeData = file_get_contents($file_path);

						// Use safe unserialize function
						if (function_exists('wp_unslash')) {
							$customizeData = wp_unslash($customizeData);
						}

						$customizeData = @maybe_unserialize($customizeData);

						if (isset($customizeData['styler_options'])) {
							$class = new \Styler\Utils\DataHandler();
							$class->Import($customizeData['styler_options'], 'kirki');
						}
					} else {
						static::print_output(
							array(
								'status'  => 'error',
								'message' => 'Styler Not Found!',
							)
						);
					}

					if (is_wp_error($results)) {
						$error_message = $results->get_error_message();
						static::print_output(
							array(
								'status'  => 'error',
								'message' => $error_message,
							)
						);
					} else {
						static::print_output(
							array(
								'status' => 'success',
							)
						);
					}

					break;
				case 'media':
					$file_path = $upload_dir . $import_item . '.xml';
					$content   = file_get_contents($file_path);
					preg_match('/<wp:base_site_url>(.*?)<\/wp:base_site_url>/', $content, $base_site_url);
					if ($base_site_url[1] != get_site_url()) {
						$content = str_replace(
							$base_site_url[1] . '/wp-content/uploads/',
							Kata_Plus::$upload_dir_url . '/importer/' . $key . '/uploads/',
							$content
						);
						file_put_contents($file_path, $content);
					}

					$logger = new OCDI\Logger();
					$import = new OCDI\Importer(
						array(
							'fetch_attachments' => true,
						),
						$logger
					);
					$import->import_content($file_path);
					if (! empty($logger->error_output)) {
						static::print_output(
							array(
								'status'  => 'error',
								'message' => $logger->error_output,
							)
						);
					} else {
						static::print_output(
							array(
								'status' => 'success',
							)
						);
					}
					break;
				case 'pages':
				case 'posts':
				case 'grids':
				case 'contact_form':
				case 'builders':
				case 'woo_pages':
				case 'woo_my_account_pages':
				case 'woo_product_loops':
				case 'size_chart':
				case 'menu':
				case 'product_tab':
				case 'product_badges':
				case 'templates':
				case 'mega_menu':
				case 'products':
				case 'product_variations':
				case 'recipes':
				case 'courses':
				case 'lessons':
				case 'quizzes':
				case 'question_bank':
				case 'crypto':
				case 'events':
				case 'event_shortcodes':
					$kata_options = get_option('kata_options');

					$file_path = $upload_dir . $settings['import_item'] . '.xml';

					$content = file_get_contents($file_path);
					preg_match('/<wp:base_site_url>(.*?)<\/wp:base_site_url>/', $content, $base_site_url);
					if ($base_site_url[1] != get_site_url()) {
						$content = str_replace($base_site_url[1], get_site_url(), $content);
						file_put_contents($file_path, $content);
					}

					$this->filter_post_content($import_item, $file_path, $settings);

					$logger = new OCDI\Logger();
					$import = new OCDI\Importer(
						array(
							'update_attachment_guids' => true,
							'fetch_attachments'       => true,
						),
						$logger
					);

					// Delete unnecessary posts
					$this->delete_unnecessary_post($settings['import_item']);

					$import->import_content($file_path);

					// Set Featured term for product
					$this->featured_product($settings['import_item']);

					if (! empty($logger->error_output)) {
						static::print_output(
							array(
								'status'  => 'error',
								'message' => $logger->error_output,
							)
						);
					} else {
						static::print_output(
							array(
								'status' => 'success',
								'others' => $logger,
							)
						);
					}
					// Utils::replace_urls( 'https://climaxthemes.com/kata-blog/' . $settings['demo'], get_site_url() );
					// Utils::replace_urls( 'https://climaxthemes.com/kata/' . $settings['demo'], get_site_url() );

					if (class_exists('Elementor\Plugin')) {
						\Elementor\Plugin::$instance->files_manager->clear_cache();
					}
					break;
				case 'widgets':
					$file_path = $upload_dir . $settings['import_item'] . '.wie';
					$results   = OCDI\WidgetImporter::import($file_path);
					if (is_wp_error($results)) {
						$error_message = $results->get_error_message();
						static::print_output(
							array(
								'status'  => 'error',
								'message' => $error_message,
							)
						);
					} else {
						static::print_output(
							array(
								'status' => 'success',
							)
						);
					}
					break;
				case 'ess_grid':
					$file_path = $upload_dir . $settings['import_item'] . '.json';
					self::import_essential_grid($file_path);
					break;
				case 'data_configuration':
					$file_path = $upload_dir . $settings['import_item'] . '.json';
					self::data_configuration($file_path);
					break;
				case 'sliders':
					if (class_exists('RevSlider')) {
						$slider = new \RevSlider();
						$status = $slider->importSliderFromPost(true, true, $zip_file_path);
						if ($status['success'] == true) {
							static::print_output(
								array(
									'status' => 'success',
								)
							);
						} else {
							static::print_output(
								array(
									'status'  => 'error',
									'message' => $status['error'],
								)
							);
						}
					} else {
						static::print_output(
							array(
								'status'  => 'error',
								'message' => __('The RevSlider is not installed', 'kata-plus'),
							)
						);
					}
					break;
				case 'fonts':
					global $wpdb;

					$sql  = 'SELECT * FROM ' . $wpdb->prefix . Kata_Plus::$fonts_table_name;
					$sql .= ' WHERE source<>"upload-icon" limit=1';
					$wpdb->hide_errors();
					if (strpos($wpdb->last_error, "doesn't exist")) {
						$sql = 'SELECT * FROM ' . $wpdb->prefix . 'kata_plus_typography_fonts';
						$wpdb->hide_errors();
						if (strpos($wpdb->last_error, "doesn't exist")) {
							$charset_collate = $wpdb->get_charset_collate();
							$sql             = 'CREATE TABLE ' . $wpdb->prefix . \Kata_Plus::$fonts_table_name . " (
								ID int(9) NOT NULL AUTO_INCREMENT,
								name text NOT NULL,
								source varchar(200) NOT NULL,
								selectors text NOT NULL,
								subsets text NOT NULL,
								variants text NOT NULL,
								url text DEFAULT '' NOT NULL,
								place varchar(50) NOT NULL DEFAULT 'before_head_end',
								status varchar(50) NOT NULL DEFAULT 'publish',
								created_at int(12) NOT NULL,
								updated_at int(12) NOT NULL,
								PRIMARY KEY  (ID)
							) $charset_collate;";

							require_once ABSPATH . 'wp-admin/includes/upgrade.php';
							dbDelta($sql);
						}
					}
					$upload_dir = Kata_Plus::$upload_dir . '/fonts/';
					if (! file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					if (class_exists('PclZip')) {
						$zip = new PclZip($zip_file_path);
						$zip->extract(PCLZIP_OPT_PATH, $upload_dir);
					} else {
						$zip = new Zip();
						$zip->unzip_file($zip_file_path, $upload_dir);
					}
					$json_file = realpath($upload_dir . '/fonts.json');
					if (! $json_file) {
						static::print_output(
							array(
								'status'  => 'error',
								'message' => __("Can't find the fonts.json file", 'kata-plus'),
							)
						);
					}
					$importData = json_decode(file_get_contents($json_file), true);
					@unlink($json_file);
					if (! $importData) {
						static::print_output(
							array(
								'status'  => 'error',
								'message' => __("Can't recognize the fonts.json file content", 'kata-plus'),
							)
						);
					}
					foreach ($importData as $font) {
						if (is_array($font['url'])) {
							$font['url'] = json_encode($font['url']);
						}
						$font['url'] = str_replace('{{siteUrl}}', site_url(), $font['url']);
						$wpdb->insert(
							$wpdb->prefix . Kata_Plus::$fonts_table_name,
							array(
								'name'       => $font['name'],
								'source'     => $font['source'],
								'selectors'  => $font['selectors'],
								'subsets'    => $font['subsets'],
								'variants'   => $font['variants'],
								'url'        => $font['url'],
								'place'      => $font['place'],
								'status'     => $font['status'],
								'created_at' => $font['created_at'],
								'updated_at' => $font['updated_at'],
							)
						);
					}
					static::print_output(
						array(
							'status' => 'success',
						)
					);
					break;
			}
			wp_die();
		}

		/**
		 * Update Elementor kit settings for different viewports.
		 */
		public function update_elementor_kit()
		{

			$kata_options = get_option('kata_options');

			if (! class_exists('\Elementor\Plugin')) {
				return;
			}

			if (isset($kata_options['updates']['elementor_breakpoints'])) {
				return;
			}

			$active_breakpoints = Plugin::$instance->kits_manager->get_active_kit();
			$is_valid_kit = $active_breakpoints && $active_breakpoints->get_main_id();

			if (! $is_valid_kit) {
				return;
			}

			$active_breakpoints = array(
				'viewport_mobile',
				'viewport_mobile_extra',
				'viewport_tablet',
				'viewport_tablet_extra',
				'viewport_laptop',
			);

			Plugin::$instance->kits_manager->update_kit_settings_based_on_option('active_breakpoints', $active_breakpoints);
			Plugin::$instance->kits_manager->update_kit_settings_based_on_option('viewport_md', 481);
			Plugin::$instance->kits_manager->update_kit_settings_based_on_option('viewport_lg', 993);
			Plugin::$instance->kits_manager->update_kit_settings_based_on_option('viewport_mobile', 480);
			Plugin::$instance->kits_manager->update_kit_settings_based_on_option('viewport_mobile_extra', 768);
			Plugin::$instance->kits_manager->update_kit_settings_based_on_option('viewport_tablet', 992);
			Plugin::$instance->kits_manager->update_kit_settings_based_on_option('viewport_tablet_extra', 1024);
			Plugin::$instance->kits_manager->update_kit_settings_based_on_option('viewport_laptop', 1366);


			$kit = Plugin::$instance->kits_manager->get_active_kit_for_frontend();
			$page_settings = $kit->get_settings();

			if (
				in_array('viewport_mobile_extra', $page_settings['active_breakpoints']) &&
				in_array('viewport_tablet_extra', $page_settings['active_breakpoints']) &&
				in_array('viewport_laptop', $page_settings['active_breakpoints'])
			) {
				$kata_options['updates']['elementor_breakpoints'] = true;
				update_option('kata_options', $kata_options);
			}
		}

		/**
		 * Import Done.
		 *
		 * @since   1.0.0
		 */
		public function ImportDone()
		{
			check_ajax_referer('kata_importer_nonce', 'nonce');
			do_action('customize_save_after');

			$settings    = $_POST;
			$demo_data   = Kata_Plus_API::get_instance()->demo('Information', $settings['key']);

			if (! isset($settings['key'])) {
				return;
			}

			$status  = false;
			$reports = $settings['reports'];
			foreach ($reports as $key => $report) {
				if ($report === 'true') {
					$status = true;
					break;
				}
			}

			if (isset($settings['reports']['pages']) && $status === true) {

				do_action('customize_save_after');

				$front_page_id = Kata_Plus_Helpers::get_post_by_title('page', 'Home');
				$blog_page_id  = Kata_Plus_Helpers::get_post_by_title('page', 'Blog');

				if ($front_page_id || $blog_page_id) {
					update_option('show_on_front', 'page');
				}

				if ($front_page_id) {
					update_option('page_on_front', $front_page_id);
				}

				if ($blog_page_id) {
					update_option('page_for_posts', $blog_page_id);
				}
			}

			if ($status === true) {
				Kata_Plus_API::get_instance()->ImportDone($settings['key']);
				$kata_options                     = get_option('kata_options');
				$kata_options['imported_demos'][] = $settings['key'];
				update_option('kata_options', $kata_options);
			}

			$demo_slug = $settings['demo'];
			$demo_slug = str_replace(' - fast', '', $demo_slug);
			$demo_slug = str_replace(' - free', '', $demo_slug);
			$demo_slug = str_replace(' ', '-', $demo_slug);

			Utils::replace_urls('https://katademos.com/fast/' . $demo_slug, get_site_url());
			Utils::replace_urls('https://katademos.com/free/' . $demo_slug, get_site_url());
			Utils::replace_urls('https://katademos.com/' . $demo_slug, get_site_url());
			Utils::replace_urls('https://climaxthemes.com/kata/' . $demo_slug, get_site_url());
			Utils::replace_urls('https://climaxthemes.com/kata/fast' . $demo_slug, get_site_url());
			Utils::replace_urls('https://climaxthemes.com/kata/free' . $demo_slug, get_site_url());
			Utils::replace_urls('https://climaxthemes.com/kata-blog/' . $demo_slug, get_site_url());
			Utils::replace_urls('https://climaxthemes.com/kata-pro/' . $demo_slug, get_site_url());

			if (class_exists('Elementor\Plugin')) {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
			}

			?>
			<?php if ($status === true) : ?>
				<i class="ti-close"></i>
				<div class="kata-lightbox-contents">
					<div class="kata-lightbox">
						<?php
						$modal_title = (isset($_POST['currnet_location']) && $_POST['currnet_location']) == 'fastmode' ? __('Import Demo', 'kata-plus') : $demo_data->data->name;
						self::HeaderModal($modal_title);
						?>
						<div class="kata-lightbox-content kata-importer-settings">
							<div class="kata-col-import-demo-image">
								<img src="<?php echo esc_url($demo_data->data->images[0]); ?>" alt="<?php echo esc_attr($demo_data->data->name); ?>">
							</div>
							<div class="kata-col-import-demo" style="width:19% !important;">
								<div class="kata-import-demo-done-100">
									<?php echo __('The Demo is Imported Successfully', 'kata-plus'); ?>
								</div>
								<div class="kata-suc-imp-links">
									<a href="<?php echo site_url(); ?>"><?php echo __('View Website', 'kata-plus'); ?></a>
								</div>
							</div><!-- end .kata-col-import-demo -->
						</div> <!-- end .lightbox-content -->
						<!-- end .kata-suc-imp-content-wrap -->
					</div> <!-- end .kata-lightbox -->
				</div> <!-- end .kata-lightbox-contents -->
			<?php else : ?>
				<i class="ti-close"></i>
				<div class="kata-lightbox-contents">
					<div class="kata-lightbox">
						<?php
						$modal_title = (isset($_POST['currnet_location']) && $_POST['currnet_location']) == 'fastmode' ? __('Import Demo', 'kata-plus') : $demo_data->data->name;
						self::HeaderModal($modal_title);
						?>
						<div class="kata-lightbox-content kata-importer-settings">
							<div class="kata-col-import-demo-image">
								<img src="<?php echo esc_url($demo_data->data->images[0]); ?>" alt="<?php echo esc_attr($demo_data->data->name); ?>">
							</div>
							<div class="kata-col-import-demo" style="width:19% !important;">
								<div class="kata-import-demo-failed">
									<?php echo __('Demo Import Failed', 'kata-plus'); ?>
								</div>
								<div class="kata-imp-fail-tx">
									<?php echo __('Please check', 'kata-plus'); ?> .... <a href="<?php echo admin_url('admin.php?page=kata-plus-help'); ?>" target="_blank"><?php echo __('System Status', 'kata-plus'); ?></a> <?php echo __('and try again', 'kata-plus'); ?>.
								</div>
							</div><!-- end .kata-col-import-demo -->
						</div> <!-- end .lightbox-content -->
						<!-- end .kata-suc-imp-content-wrap -->
					</div> <!-- end .kata-lightbox -->
				</div> <!-- end .kata-lightbox-contents -->
			<?php endif; ?>
<?php
			wp_die();
		}

		/**
		 * Filters the post content.
		 *
		 * @param mixed   $import_item The import item.
		 * @param string  $file_path   The file path.
		 *
		 * @version 1.3.0
		 * @return void
		 */
		public function filter_post_content($import_item, $file_path, $settings)
		{
			$xml_content = file_get_contents($file_path);

			// replace URL
			if (strpos($settings['demo'], '- fast')) {
				$settings['demo'] = str_replace(' - fast', '', $settings['demo']);
				$settings['demo'] = 'fast\\\\/' . $settings['demo'];
			}

			$xml_content = str_replace('https:\/\/katademos.com\/' . $settings['demo'], get_site_url(), $xml_content);
			$xml_content = str_replace('\/\/katademos.com\/' . $settings['demo'], get_site_url(), $xml_content);
			$xml_content = str_replace('\\\\/\\\\/katademos.com\\\\/' . $settings['demo'], get_site_url(), $xml_content);

			file_put_contents($file_path, $xml_content);

			// preg_match_all( '/<content:encoded><!\[CDATA\[(.*?)\]\]><\/content:encoded>/s', $xml_content, $content_matches );
			// if ( ! empty( $content_matches ) ) {
			// foreach ( $content_matches[0] as $key => $value ) {
			// 	if (
			// 		'pages' == $import_item ||
			// 		'builders' == $import_item ||
			// 		'woo_pages' == $import_item ||
			// 		'woo_my_account_pages' == $import_item ||
			// 		'woo_product_loops' == $import_item ||
			// 		'templates' == $import_item
			// 	) {
			// 		$xml_content = str_replace( $value, '<content:encoded><![CDATA[]]></content:encoded>', $xml_content );
			// 	}
			// }
			// }

			return false;
		}

		/**
		 * featured products
		 *
		 * @param $settings['import_item']
		 * @return void
		 */
		public function featured_product($import_item)
		{
			if ($import_item == 'products') {

				$products = get_posts(
					array(
						'post_type'   => 'product',
						'numberposts' => 5,
					)
				);

				foreach ($products as $product) {
					wp_set_post_terms($product->ID, array('featured'), 'product_visibility');
				}
			}
		}

		/**
		 * Delete unnecessary posts
		 *
		 * @param $settings['import_item']
		 * @return void
		 */
		public function delete_unnecessary_post($settings)
		{

			// Clear Builders
			if ($settings == 'builders') {

				$Builders = get_posts(
					array(
						'post_type'   => 'kata_plus_builder',
						'numberposts' => -1,
					)
				);

				foreach ($Builders as $eachBuilder) {
					wp_delete_post($eachBuilder->ID, true);
				}

				delete_option('kata_multi_language_builder');
				$kata_options = get_option('kata_options');
				unset($kata_options['multilanguage']['polylang']);
				unset($kata_options['multilanguage']['wpml']);
				update_option('kata_options', $kata_options);
			}

			// Clear woo_pages
			if ($settings == 'woo_pages') {

				$woo_pagess = get_posts(
					array(
						'post_type'   => 'shoppress_pages',
						'numberposts' => -1,
					)
				);

				foreach ($woo_pagess as $eachWoo_pages) {
					wp_delete_post($eachWoo_pages->ID, true);
				}
			}
			// Clear woo_my_account_pages
			if ($settings == 'woo_my_account_pages') {

				$woo_my_account_pagess = get_posts(
					array(
						'post_type'   => 'shoppress_myaccount',
						'numberposts' => -1,
					)
				);

				foreach ($woo_my_account_pagess as $eachWoo_my_account_pages) {
					wp_delete_post($eachWoo_my_account_pages->ID, true);
				}
			}
			// Clear woo_product_loops
			if ($settings == 'woo_product_loops') {

				$woo_product_loops = get_posts(
					array(
						'post_type'   => 'shoppress_loop',
						'numberposts' => -1,
					)
				);

				foreach ($woo_product_loops as $eachWoo_product_loops) {
					wp_delete_post($eachWoo_product_loops->ID, true);
				}
			}
			// Clear size_chart
			if ($settings == 'size_chart') {

				$size_chart = get_posts(
					array(
						'post_type'   => 'size_chart',
						'numberposts' => -1,
					)
				);

				foreach ($size_chart as $eachSize_chart) {
					wp_delete_post($eachSize_chart->ID, true);
				}
			}

			// Clear Posts
			if ($settings == 'posts') {
				$post = get_posts(
					array(
						'post_type'   => 'post',
						'title'       => 'Hello world!',
						'post_status' => 'all',
						'numberposts' => 1,
					)
				);
				if (isset($post[0]->ID)) {
					wp_delete_post($post[0]->ID, true);
				}
			}

			// Clear Pages
			if ($settings == 'pages') {
				$pages = get_posts(
					array(
						'post_type'   => 'page',
						'title'       => 'Sample Page',
						'post_status' => 'all',
						'numberposts' => 1,
					)
				);
				if (isset($pages[0]->ID)) {
					wp_delete_post($pages[0]->ID, true);
				}

				if (function_exists('shoppress')) {

					$wishlist = get_posts(
						array(
							'post_type'      => 'page',
							's'              => '[shoppress-wishlist-page]',
							'posts_per_page' => -1
						)
					);
					if (isset($wishlist[0]->ID)) {
						wp_delete_post($wishlist[0]->ID, true);
					}

					$compare = get_posts(
						array(
							'post_type'      => 'page',
							's'              => '[shoppress-compare-page]',
							'posts_per_page' => -1
						)
					);
					if (isset($compare[0]->ID)) {
						wp_delete_post($compare[0]->ID, true);
					}
				}
			}
		}

		/**
		 * Data Configuration
		 *
		 * @param [Array] $data
		 * @return void
		 */
		public static function data_configuration($src)
		{
			$configs = file_get_contents($src);
			$configs = json_decode($configs, true)[0];

			foreach ($configs as $key => $values) {
				if ($key == 'options') {
					foreach ($values as $opt_name => $opt_value) {
						if ($configs['unslash']) {
							$opt_value = wp_unslash($opt_value);
						}
						if ($configs['maybe_unserialize']) {
							// Security check: Validate that this is a safe serialized string
							if (is_string($opt_value) && preg_match('/[CO]:[+\-]?[0-9]+:"/', $opt_value)) {
								static::print_output(
									array(
										'status'  => 'error',
										'message' => 'Security validation failed: Unsafe data detected',
									)
								);
								return;
							}

							$opt_value = maybe_unserialize($opt_value);
						}

						if ($opt_name == 'sp_admin') {

							$templates = get_posts(
								array(
									'post_type'      => array(
										'shoppress_pages',
										'shoppress_myaccount',
										'shoppress_loop',
									),
									'posts_per_page' => -1,
									'post_status'    => 'any',
								)
							);

							$opt_value = json_decode($opt_value, true);

							if (is_array($templates) && ! empty($templates)) {
								foreach ($templates as $template) {
									$template_id = $template->ID;
									$custom_type = get_post_meta($template_id, 'custom_type', true);

									if (isset($opt_value['templates'][$custom_type]['page_builder'])) {
										$opt_value['templates'][$custom_type]['page_builder'] = $template->ID;
									}
								}

								$opt_value = json_encode($opt_value);
							}
						}

						$results = update_option($opt_name, $opt_value);
					}
				} elseif ($key == 'sql') {
					global $wpdb;
					$messages = '';
					foreach ($values as $table_name => $sql_statement) {
						if (isset($table_name)) {
							$tablename = $wpdb->prefix . $table_name;
							$sql       = $wpdb->prepare($sql_statement, $tablename);
							$sql       = str_replace(["`'", "'`"], ['`', '`'], $sql);
							$results   = $wpdb->query($sql);

							if ($wpdb->last_error) {
								$messages .= $wpdb->last_error;
							}
						}
					}
					if (! empty($messages)) {
						static::print_output(
							[
								'status'  => 'error',
								'message' => $messages,
							]
						);
					} else {
						static::print_output(
							[
								'status' => 'success',
							]
						);
					}
				}
			}
			if (is_wp_error($results)) {
				$error_message = $results->get_error_message();
				static::print_output(
					[
						'status'  => 'error',
						'message' => $error_message,
					]
				);
			} else {
				static::print_output(
					[
						'status' => 'success',
					]
				);
			}
		}

		/**
		 * Copy Target Directory to Destiny Directory
		 *
		 * @param [Array] $data
		 * @return void
		 */
		private static function copy_dir($src, $dest)
		{
			foreach (scandir($src) as $file) {
				if (! is_readable($src . '/' . $file)) {
					continue;
				}
				if (is_dir($src . '/' . $file) && ($file != '.') && ($file != '..')) {
					if (! realpath($dest . '/' . $file)) {
						mkdir($dest . '/' . $file, 0777, true);
					}
					static::copy_dir($src . '/' . $file, $dest . '/' . $file);
					unlink($src . '/' . $file);
				} elseif ($file != '.' && $file != '..') {
					if (file_exists($dest . '/' . $file)) {
						unlink($dest . '/' . $file);
					}
					copy($src . '/' . $file, $dest . '/' . $file);
					unlink($src . '/' . $file);
				}
			}
		}


		/**
		 * Print Data Array as Json Output
		 *
		 * @param [Array] $data
		 * @return void
		 */
		public static function import_essential_grid($essential_grid_file_path)
		{
			$data    = array();
			$content = file_get_contents($essential_grid_file_path);
			$content = json_decode($content);
			foreach ($content as $key => $item) {
				if ('grids' == $key) {
					if (! isset($data['imports']['data-grids'])) {
						foreach ($item as $grid) {
							$data['imports']['data-grids'][] = json_encode($grid);
						}
					}
				}
				if ('skins' == $key) {
					if (! isset($data['imports']['data-skins'])) {
						foreach ($item as $grid) {
							$data['imports']['data-skins'][] = json_encode($grid);
						}
					}
					if (! isset($data['imports']['import-skins-id'])) {
						foreach ($item as $k => $v) {
							$data['imports']['import-skins-id'][] = $v->id;
						}
					}
				}
				if ('global-css' == $key) {
					$data['imports']['data-global-css'] = json_encode($item);
				}
				if (! isset($data['imports']['import-grids'])) {
					$data['imports']['import-grids'] = 'true';
				}
				if (! isset($data['imports']['import-grids-id'])) {
					foreach ($item as $k => $v) {
						$data['imports']['import-grids-id'][] = $v->id;
					}
				}
				if (! isset($data['imports']['import-skins'])) {
					$data['imports']['import-skins'] = 'true';
				}
				if (! isset($data['imports']['import-global-styles'])) {
					$data['imports']['import-global-styles'] = 'on';
				}
				if (! isset($data['imports']['global-styles-overwrite'])) {
					$data['imports']['global-styles-overwrite'] = 'append';
				}
			}
			if (class_exists('Essential_Grid')) {
				if (! isset($data['imports']) || empty($data['imports'])) {
					Essential_Grid::ajaxResponseError(__('No data for import selected', 'kata-plus'), false);
					exit();
				}
				try {
					$im = new Essential_Grid_Import();

					$temp_d = @$data['imports'];
					unset($temp_d['data-grids']);
					unset($temp_d['data-skins']);
					unset($temp_d['data-elements']);
					unset($temp_d['data-navigation-skins']);
					unset($temp_d['data-global-css']);

					$im->set_overwrite_data($temp_d); // set overwrite data global to class

					$skins = @$data['imports']['data-skins'];
					if (! empty($skins) && is_array($skins)) {
						foreach ($skins as $key => $skin) {
							$tskin = json_decode(stripslashes($skin), true);
							if (empty($tskin)) {
								$tskin = json_decode($skin, true);
							}

							if (class_exists('Essential_Grid_Plugin_Update')) {
								$tskin = Essential_Grid_Plugin_Update::process_update_216($tskin, true);
							}

							$skins[$key] = $tskin;
						}
						if (! empty($skins)) {
							$skins_ids      = @$data['imports']['import-skins-id'];
							$skins_imported = $im->import_skins($skins, $skins_ids);
						}
					}

					$navigation_skins = @$data['imports']['data-navigation-skins'];
					if (! empty($navigation_skins) && is_array($navigation_skins)) {
						foreach ($navigation_skins as $key => $navigation_skin) {
							$tnavigation_skin = json_decode($navigation_skin, true);
							if (empty($tnavigation_skin)) {
								$tnavigation_skin = json_decode($navigation_skin, true);
							}

							$navigation_skins[$key] = $tnavigation_skin;
						}
						if (! empty($navigation_skins)) {
							$navigation_skins_ids      = @$data['imports']['import-navigation-skins-id'];
							$navigation_skins_imported = $im->import_navigation_skins(@$navigation_skins, $navigation_skins_ids);
						}
					}

					$grids = @$data['imports']['data-grids'];
					if (! empty($grids) && is_array($grids)) {
						foreach ($grids as $key => $grid) {
							$tgrid = json_decode(stripslashes($grid), true);
							if (empty($tgrid)) {
								$tgrid = json_decode($grid, true);
							}

							$grids[$key] = $tgrid;
						}
						if (! empty($grids)) {
							$grids_ids      = @$data['imports']['import-grids-id'];
							$grids_imported = $im->import_grids($grids, $grids_ids);
						}
					}

					$elements = @$data['imports']['data-elements'];
					if (! empty($elements) && is_array($elements)) {
						foreach ($elements as $key => $element) {
							$telement = json_decode(stripslashes($element), true);
							if (empty($telement)) {
								$telement = json_decode($element, true);
							}

							$elements[$key] = $telement;
						}
						if (! empty($elements)) {
							$elements_ids      = @$data['imports']['import-elements-id'];
							$elements_imported = $im->import_elements(@$elements, $elements_ids);
						}
					}

					$custom_metas = @$data['imports']['data-custom-meta'];
					if (! empty($custom_metas) && is_array($custom_metas)) {
						foreach ($custom_metas as $key => $custom_meta) {
							$tcustom_meta = json_decode(stripslashes($custom_meta), true);
							if (empty($tcustom_meta)) {
								$tcustom_meta = json_decode($custom_meta, true);
							}

							$custom_metas[$key] = $tcustom_meta;
						}
						if (! empty($custom_metas)) {
							$custom_metas_handle   = @$data['imports']['import-custom-meta-handle'];
							$custom_metas_imported = $im->import_custom_meta($custom_metas, $custom_metas_handle);
						}
					}

					$custom_fonts = @$data['imports']['data-punch-fonts'];
					if (! empty($custom_fonts) && is_array($custom_fonts)) {
						foreach ($custom_fonts as $key => $custom_font) {
							$tcustom_font = json_decode(stripslashes($custom_font), true);
							if (empty($tcustom_font)) {
								$tcustom_font = json_decode($custom_font, true);
							}

							$custom_fonts[$key] = $tcustom_font;
						}
						if (! empty($custom_fonts)) {
							$custom_fonts_handle   = @$data['imports']['import-punch-fonts-handle'];
							$custom_fonts_imported = $im->import_punch_fonts($custom_fonts, $custom_fonts_handle);
						}
					}

					if (@$data['imports']['import-global-styles'] == 'on') {
						$global_css = @$data['imports']['data-global-css'];

						$global_styles_imported = $im->import_global_styles($global_css);
					}

					Essential_Grid::ajaxResponseSuccess(
						__('Successfully imported data', 'kata-plus'),
						array(
							'is_redirect'  => true,
							'redirect_url' => Essential_Grid_Base::getViewUrl('', '', 'essential-' . Essential_Grid_Admin::VIEW_START),
						)
					);
				} catch (Exception $d) {
					$error = __('Something went wrong, please contact the developer', 'kata-plus');
				}
			}
		}

		/**
		 * Print Data Array as Json Output
		 *
		 * @param [Array] $data
		 * @return void
		 */
		public static function print_output($data)
		{
			if (! is_array($data) && ! is_object($data)) {
				$data = array($data);
			}

			header('Content-Type: application/json');
			echo json_encode($data);
			exit();
		}

		/**
		 * Enqueue Styles.
		 *
		 * @access public
		 * @return void
		 */
		public function enqueue_styles()
		{
			wp_enqueue_style('kata-plus-importer', Kata_Plus::$assets . 'css/backend/importer.css');
			if (is_rtl()) {
				wp_enqueue_style('kata-plus-importer-rtl', Kata_Plus::$assets . 'css/backend/importer-rtl.css');
			}
			wp_enqueue_style('nice-select', Kata_Plus::$assets . 'css/libraries/nice-select.css', array(), Kata_Plus::$version);
		}

		/**
		 * Enqueue Javascripts.
		 *
		 * @access public
		 * @return void
		 */
		public function enqueue_scripts()
		{
			wp_enqueue_script('lozad', Kata_Plus::$assets . 'js/libraries/lozad.min.js', array('jquery'), Kata_Plus::$version, false);
			wp_enqueue_script('nice-select', Kata_Plus::$assets . 'js/libraries/jquery.nice-select.js', array('jquery'), Kata_Plus::$version, true);
			wp_enqueue_script('kata-plus-importer', Kata_Plus::$assets . 'js/backend/importer.js', array('jquery', 'nice-select'), Kata_plus::$version, true);
			wp_localize_script(
				'kata-plus-importer',
				'importer_localize',
				array(
					'ajax' => array(
						'url'               => admin_url('admin-ajax.php'),
						'nonce'             => wp_create_nonce('kata_importer_nonce'),
						'reset_message'     => __('Are you sure about removing all your site contents like Pages, Posts, Builders, Media, Contact Form7, Menu, etc?'),
						'restarted_message' => __('All site contents like Pages, Posts, Builders, Media, Contact Form7, Menu, etc has been removed.'),
					),
				)
			);

			wp_localize_script(
				'kata-plus-importer',
				'kata_install_plugins',
				array(
					'translation' => array(
						'activate'           => esc_html__('Activate', 'kata-plus'),
						'deactivate'         => esc_html__('Deactivate', 'kata-plus'),
						'fail-plugin-action' => esc_html__('There was a problem with your action. Please try again or reload the page.', 'kata-plus'),
					),
				)
			);
		}
	} // class

	Kata_Plus_Importer::get_instance();
} // if
