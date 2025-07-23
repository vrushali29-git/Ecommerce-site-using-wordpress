<?php

/**
 * System Status
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

<div class="kata-card">
	<div class="kata-card-header">
		<h4><?php esc_html_e( 'System Status', 'kata-plus' ); ?></h4>
	</div>
	<div class="kata-card-body">
		<p class="kata-card-text"><?php esc_html_e( 'Please fix the following status for a successful import.', 'kata-plus' ); ?></p>
		<div class="system-status">
			<table class="table table-striped">
				<tbody>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Elementor:', 'kata-plus' ); ?></th>
						<td><?php echo did_action( 'elementor/loaded' ) != '0' ? '<span style="color: #3dbf2c;">' . esc_html__( 'INSTALLED' ) . '</span>' : '<span style="color: #d60e0e;">' . esc_html__( 'NOT INSTALLED' ) . '</span>'; ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Operating System:', 'kata-plus' ); ?></th>
						<td><?php echo esc_html( Kata_Plus_System_Status::get_os()['value'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Software:', 'kata-plus' ); ?></th>
						<td><?php echo esc_html( Kata_Plus_System_Status::get_software()['value'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'MySQL Version:', 'kata-plus' ); ?></th>
						<td><?php echo esc_html( Kata_Plus_System_Status::get_mysql_version()['value'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'ZIP Installed:', 'kata-plus' ); ?></th>
						<td>
							<?php
							if ( Kata_Plus_System_Status::get_zip_installed()['value'] == 'Yes' ) {
								echo '<span style="color: #3dbf2c;">';
								echo esc_html( Kata_Plus_System_Status::get_zip_installed()['value'] );
								echo '</span>';
								echo '<span style="color: #3dbf2c; margin-left: 10px;"><i class="ti-check"></i></span>';
							} else {
								echo '<span style="color: red;">';
								echo esc_html__( 'We recommend to activate the zip archive php extension', 'kata-plus' );
								echo '</span>';
								echo '<span style="color: red; margin-left: 10px;"><i class="ti-close"></i></span>';
							}
							?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'PHP Version:', 'kata-plus' ); ?></th>
						<td>
						<?php
						if ( version_compare( phpversion(), '5.6', '<' ) ) {
							echo '<span style="color: red;">';
							echo esc_html__( 'We recommend to use php 5.6 or higher. Contact to your host provider', 'kata-plus' );
							echo '</span>';
							echo '<span style="color: red; margin-left: 10px;"><i class="ti-close"></i></span>';
						} else {
							echo '<span style="color: #3dbf2c;">';
							echo esc_html( Kata_Plus_System_Status::get_php_version()['value'] );
							echo '</span>';
							echo '<span style="color: #3dbf2c; margin-left: 10px;"><i class="ti-check"></i></span>';
							if ( version_compare( phpversion(), '7.4', '<' ) ) {
								echo '<br><span style="color: red;">';
								echo esc_html__( 'We highly recommend using PHP 7.4 or higher. ', 'kata-plus' );
								echo '</span>';
							}
						}
						?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'PHP Max File Size :', 'kata-plus' ); ?></th>
						<td>
						<?php
							$max_post_size = 40;
						if ( Kata_Plus_System_Status::get_php_max_filesize()['value'] < $max_post_size ) {
							echo '<span style="color: red;">';
							echo '<span class="current-status-icon"><i class="ti-close"></i></span>';
							echo '<span class="current-value">' . esc_html__( 'Current is ', 'kata-plus' ) . esc_html( Kata_Plus_System_Status::get_php_max_filesize()['value'] ) . ' </span>';
							echo '<span class="need-value">' . esc_html__( 'Change to ', 'kata-plus' ) . esc_html( $max_post_size ) . 'M </span>';
							echo '<a href="https://climaxthemes.com/kata/documentation/kata-requirements/" class="solution" target="_blank">' . esc_html__( 'How to Fix', 'kata-plus' ) . '</a>';
							echo '</span>';
						} else {
							echo '<span style="color: #3dbf2c;">';
							echo esc_html( Kata_Plus_System_Status::get_php_max_filesize()['value'] );
							echo '</span>';
							echo '<span style="color: #3dbf2c; margin-left: 10px;"><i class="ti-check"></i></span>';
						}
						?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Memory limit:', 'kata-plus' ); ?></th>
						<td>
						<?php
							$min_recommended_memory = '512M';
							$memory_limit_bytes     = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
							$min_recommended_bytes  = wp_convert_hr_to_bytes( $min_recommended_memory );
						if ( $memory_limit_bytes < $min_recommended_bytes ) {
							echo '<span style="color: red;">';
							echo '<span class="current-status-icon"><i class="ti-close"></i></span>';
							echo '<span class="current-value">' . esc_html__( 'Current is ', 'kata-plus' ) . esc_html( Kata_Plus_System_Status::get_memory_limit()['value'] ) . ' </span>';
							echo '<span class="need-value">' . esc_html__( 'Change to ', 'kata-plus' ) . esc_html( $min_recommended_memory ) . '</span>';
							echo '<a href="https://climaxthemes.com/kata/documentation/kata-requirements/" class="solution" target="_blank"> ' . esc_html__( 'How to Fix', 'kata-plus' ) . '</a>';
							echo '</span>';
						} else {
							echo '<span style="color: #3dbf2c;">';
							echo esc_html( Kata_Plus_System_Status::get_memory_limit()['value'] );
							echo '</span>';
							echo '<span style="color: #3dbf2c; margin-left: 10px;"><i class="ti-check"></i></span>';
						}
						?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'PHP Max Input Time:', 'kata-plus' ); ?></th>
						<td>
						<?php
							$get_php_max_input_time = 300;
						if ( Kata_Plus_System_Status::get_php_max_input_time()['value'] < $get_php_max_input_time ) {
							echo '<span style="color: red;">';
							echo '<span class="current-status-icon"><i class="ti-close"></i></span>';
							echo '<span class="current-value">' . esc_html__( 'Current is ', 'kata-plus' ) . esc_html( Kata_Plus_System_Status::get_php_max_input_time()['value'] ) . ' </span>';
							echo '<span class="need-value">' . esc_html__( 'Change to ', 'kata-plus' ) . esc_html( $get_php_max_input_time ) . ' </span>';
							echo '<a href="https://climaxthemes.com/kata/documentation/kata-requirements/" class="solution" target="_blank">' . esc_html__( 'How to Fix', 'kata-plus' ) . '</a>';
							echo '</span>';
						} else {
							echo '<span style="color: #3dbf2c;">';
							echo esc_html( Kata_Plus_System_Status::get_php_max_input_time()['value'] );
							echo '</span>';
							echo '<span style="color: #3dbf2c; margin-left: 10px;"><i class="ti-check"></i></span>';
						}
						?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'PHP Maximum Execution Time:', 'kata-plus' ); ?></th>
						<td>
						<?php
							$max_execution_time = 300;
						if ( Kata_Plus_System_Status::get_php_max_execution_time()['value'] < $max_execution_time ) {
							echo '<span style="color: red;">';
							echo '<span class="current-status-icon"><i class="ti-close"></i></span>';
							echo '<span class="current-value">' . esc_html__( 'Current is ', 'kata-plus' ) . esc_html( Kata_Plus_System_Status::get_php_max_execution_time()['value'] ) . ' </span>';
							echo '<span class="need-value">' . esc_html__( 'Change to ', 'kata-plus' ) . esc_html( $max_execution_time ) . ' </span>';
							echo '<a href="https://climaxthemes.com/kata/documentation/kata-requirements/" class="solution" target="_blank">' . esc_html__( 'How to Fix', 'kata-plus' ) . '</a>';
							echo '</span>';
						} else {
							echo '<span style="color: #3dbf2c;">';
							echo esc_html( Kata_Plus_System_Status::get_php_max_execution_time()['value'] );
							echo '</span>';
							echo '<span style="color: #3dbf2c; margin-left: 10px;"><i class="ti-check"></i></span>';
						}
						?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'PHP Post Max Size:', 'kata-plus' ); ?></th>
						<td>
						<?php
							$post_max_size = 128;
						if ( Kata_Plus_System_Status::get_php_post_max_size()['value'] < $post_max_size ) {
							echo '<span style="color: red;">';
							echo '<span class="current-status-icon"><i class="ti-close"></i></span>';
							echo '<span class="current-value">' . esc_html__( 'Current is ', 'kata-plus' ) . esc_html( Kata_Plus_System_Status::get_php_post_max_size()['value'] ) . ' </span>';
							echo '<span class="need-value">' . esc_html__( 'Change to ', 'kata-plus' ) . esc_html( $post_max_size ) . 'M </span>';
							echo '<a href="https://climaxthemes.com/kata/documentation/kata-requirements/" class="solution" target="_blank">' . esc_html__( 'How to Fix', 'kata-plus' ) . '</a>';
							echo '</span>';
						} else {
							echo '<span style="color: #3dbf2c;">';
							echo esc_html( Kata_Plus_System_Status::get_php_post_max_size()['value'] );
							echo '</span>';
							echo '<span style="color: #3dbf2c; margin-left: 10px;"><i class="ti-check"></i></span>';
						}
						?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Max Upload Size:', 'kata-plus' ); ?></th>
						<td>
							<?php
							$max_upload_size = 32;
							if ( wp_max_upload_size() < $max_upload_size ) {
								echo '<span style="color: red;">';
								echo '<span class="current-status-icon"><i class="ti-close"></i></span>';
								echo '<span class="current-value">' . esc_html__( 'Current is ', 'kata-plus' ) . esc_html( Kata_Plus_System_Status::get_max_upload_size()['value'] ) . ' </span>';
								echo '<span class="need-value">' . esc_html__( 'Change to ', 'kata-plus' ) . esc_html( $max_upload_size ) . ' MB </span>';
								echo '<a href="https://climaxthemes.com/kata/documentation/kata-requirements/" class="solution" target="_blank">' . esc_html__( 'How to Fix', 'kata-plus' ) . '</a>';
								echo '</span>';
							} else {
								echo '<span style="color: #3dbf2c;">';
								echo esc_html( Kata_Plus_System_Status::get_max_upload_size()['value'] );
								echo '</span>';
								echo '<span style="color: #3dbf2c; margin-left: 10px;"><i class="ti-check"></i></span>';
							}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
