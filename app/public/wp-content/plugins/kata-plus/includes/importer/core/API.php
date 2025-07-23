<?php

/**
 * API Class
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if (! defined('ABSPATH')) {
	exit;
}

if (! class_exists('Kata_Plus_API')) {
	class Kata_Plus_API
	{

		/**
		 * The directory of this path
		 *
		 * @access  public
		 * @var     string
		 */
		public static $dir;

		/**
		 * The Demo Key
		 *
		 * @access  public
		 * @var     string
		 */
		public static $key;

		/**
		 * The URL protocol
		 *
		 * @access  public
		 * @var     string
		 */
		public static $protocol;

		/**
		 * The API URL
		 *
		 * @access  public
		 * @var     string
		 */
		public static $url;

		/**
		 * The Second Server API URL
		 *
		 * @access  public
		 * @var     string
		 */
		public static $second_url;

		/**
		 * The API Token
		 *
		 * @access  public
		 * @var     string
		 */
		public static $token;

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @var     Kata_Plus_API
		 */
		public static $instance;

		public static $licence_code;

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
		}

		/**
		 * Global definitions.
		 *
		 * @since   1.0.0
		 */
		public function definitions()
		{
			self::$url        = 'https://katademosbackup.katademos.com';
			self::$second_url = 'https://katademos.climaxthemes.org';
			self::$dir        = Kata_Plus::$dir . 'includes/importer/core/includes/';
			self::$token      = get_option('kata_plus_api_token');
			self::$key        = '';
		}

		/**
		 * Authenticate the website for using kata-plus importer features
		 *
		 * @since   1.0.0
		 */
		public function authenticate($key)
		{
			static::$key = $key;
			$licence     = true;
			$website     = 'climaxthemes.com';
			$email       = 'no-reply@climaxthemes.com';
			$wp_version  = get_bloginfo('version');

			$data        = array(
				'licence'    => $licence,
				'website'    => $website,
				'email'      => $email,
				'key'        => $key,
				'version'    => Kata_Plus::$version,
				'wp_version' => $wp_version,
			);

			$response = $this->get('Authentication', $data);

			if (! $response) {
				return false;
			}

			if (isset($response->message)) {
				return $response;
			}

			static::$token = $response->token;
			update_option('kata_plus_api_token', static::$token);

			return true;
		}

		/**
		 * Authenticate the website for using kata-plus importer features
		 *
		 * @since   1.0.0
		 */
		public function ImportDone($key)
		{
			static::$key = $key;
			$data        = array(
				'key'   => $key,
				'token' => static::$token,
			);

			$response = $this->get('Done', $data);

			if (! $response) {
				return false;
			}

			if (isset($response->message)) {
				return $response;
			}

			return true;
		}

		/**
		 * Get Demo Data From API.
		 *
		 * @since   1.0.0
		 */
		public function demo($action, $key = false)
		{

			static::$key = $key;
			$data        = false;
			switch ($action) {
				case 'List':
					$data = $this->get($action);
					$data = isset($data->list) ? $data->list : $data;
					break;
				case 'Contents':
					$data = $this->get($action);
					break;
				case 'Information':
					$data = $this->get($action);
					break;
				case 'Plugins':
					$data = $this->get($action);
					break;
				case 'Images':
					$data = $this->get($action);
					break;
				case 'Categories':
					$data = $this->get($action);
					break;
			}

			return $data;
		}

		/**
		 * Send request to WebService and return received data
		 *
		 * @param string $action
		 * @param array  $data
		 * @return object
		 */
		private function get($action, $data = array())
		{
			$data['token'] = static::$token ?? null;
			try {
				$url = $this->generate_url($action);
				$request = $this->make_request($url, $data);
				if (!$request) {
					throw new Exception('Connection closed!');
				}
				if ($request) {
					return $request;
				}
			} catch (Exception $e) {
				try {
					$url = $this->generate_url($action, static::$second_url);
					$request = $this->make_request($url, $data);
					if ($request) {
						return $request;
					}
				} catch (Exception $e) {
					$error_message = __('Connection closed!', 'kata-plus') . '
						<p style="line-height: 2;">
							' . __('Your WebHost configuration is not enough for Kata Theme', 'kata-plus') . ' <br/>
							' . __('To improve your WebHost configuration please contact your host provider.', 'kata-plus') . '<br/>
							' . 'Error: ' . $e->getMessage() . '<br/>
							' . __('For more information please see: ', 'kata-plus') . '<span><a href="' . admin_url('admin.php?page=kata-plus-help') . '">' . __('kata requirements') . '</a></span><br/>
						</p>';

					return '<span class="kata-plus-importer-error-message">' . $error_message . '</span>';
				}
			}
		}

		/**
		 * Make API request with consistent format
		 *
		 * @param string $url
		 * @param array $data
		 * @return object|false
		 */
		private function make_request($url, $data)
		{
			$headers = array(
				'apikey'     => 'trusted',
				'accept'     => 'application/json',
				'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36',
				'Content-Type' => 'application/json',
				'X-Demo-Token' => static::$token
			);

			$args = array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => $headers,
				'body' => json_encode($data),
				'cookies' => array()
			);

			$request = wp_remote_post($url, $args);

			if (is_wp_error($request)) {
				throw new Exception($request->get_error_message());
			}

			if ($request['response']['code'] === 200) {
				return json_decode($request['body']);
			}

			return false;
		}

		/**
		 * Generate URL for API request
		 *
		 * @param string $action
		 * @param string $base_url
		 * @return string|false
		 */
		private function generate_url($action, $base_url = null)
		{
			if ($base_url === null) {
				$base_url = static::$url;
			}

			$base_url = $base_url . '/wp-json/kata-demo/v1';
			$url = false;

			switch ($action) {
				case 'Authentication':
					$url = $base_url . '/demo/authentication';
					break;
				case 'List':
					$url = $base_url . '/demo/list';
					break;
				case 'Contents':
					$url = $base_url . '/demo/contents/' . static::$key;
					break;
				case 'Information':
					$url = $base_url . '/demo/information/' . static::$key;
					break;
				case 'Plugins':
					$url = $base_url . '/demo/plugins/' . static::$key;
					break;
				case 'Images':
					$url = $base_url . '/demo/images/' . static::$key;
					break;
				case 'Done':
					$url = $base_url . '/demo/done/' . static::$key;
					break;
			}

			return $url;
		}
	}

	Kata_Plus_API::get_instance();
}
