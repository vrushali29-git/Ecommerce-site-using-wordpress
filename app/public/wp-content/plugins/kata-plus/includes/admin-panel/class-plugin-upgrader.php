<?php
/**
 * Plugin upgrader.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.3.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Kata_Plus_Plugin_Upgrader extends Plugin_Upgrader {

	public function rollback( $plugin, $args = array(), $plugin_version ) {

		$defaults    = array(
			'clear_update_cache' => true,
		);
		$parsed_args = wp_parse_args( $args, $defaults );

		$this->init();
		$this->upgrade_strings();

		$plugin_slug = $plugin;

		$download_endpoint = 'https://katademos.climaxthemes.org/rollback/files/';

		$url = $download_endpoint . $plugin_slug . '.' . $plugin_version . '.zip';

		add_filter( 'upgrader_pre_install', array( $this, 'deactivate_plugin_before_upgrade' ), 10, 2 );
		add_filter( 'upgrader_clear_destination', array( $this, 'delete_old_plugin' ), 10, 4 );

		$this->run(
			array(
				'package'           => $url,
				'destination'       => WP_PLUGIN_DIR,
				'clear_destination' => true,
				'clear_working'     => true,
				'hook_extra'        => array(
					'plugin' => $plugin,
					'type'   => 'plugin',
					'action' => 'update',
				),
			)
		);

		remove_filter( 'upgrader_pre_install', array( $this, 'deactivate_plugin_before_upgrade' ) );
		remove_filter( 'upgrader_clear_destination', array( $this, 'delete_old_plugin' ) );

		if ( ! $this->result || is_wp_error( $this->result ) ) {
			return $this->result;
		}

		wp_clean_plugins_cache( $parsed_args['clear_update_cache'] );

		return true;
	}
}
