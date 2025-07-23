<?php

/**
 * Install Plugins Output.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="kata-install-plugins-wrapper">
	<div class="kata-container">
		<div class="kata-row">
			<div class="plugins-content">
				<style>
					.plugins-content {
						width: 100%;
						display: -webkit-box;
						display: -ms-flexbox;
						display: flex;
						-ms-flex-wrap: wrap;
						flex-wrap: wrap;
					}
				</style>
				<?php
				$is_plugin_page = isset( $_GET['page'] ) && $_GET['page'] === 'kata-plus-plugins';
				$tgm_actions    = ! isset( $_GET['tgmpa-install'] ) && ! isset( $_GET['tgmpa-activate'] ) && ! isset( $_GET['tgmpa-update'] );

				if ( $is_plugin_page && $tgm_actions ) {
					$tgmpa_list_table = new TGM_Plugin_Activation();
					$tgmpa_list_table->install_plugins_page();
				} else {
					?>
					<style>
						#wpbody-content {
							max-width: 1100px;
							margin: 0px auto 10px;
							background: #fff;
							padding: 25px 45px;
							border-radius: 5px;
							float: none;
						}
						.kata-admin.about-wrap, .kata-admin.about-wrap p {
							padding-left: 0;
							padding-right: 0;
						}
					</style>
					<?php
				}
				?>
			</div> <!-- end .kata-row -->
		</div>
	</div> <!-- end .container -->
</div> <!-- .kata-importer -->
