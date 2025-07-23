<?php
/**
 * Importer Page.
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
<div class="kata-admin kata-importer-page wrap about-wrap">
	<?php
		$this->header();
		Kata_Plus_Autoloader::load( Kata_Plus_Importer::$dir . 'core', 'importer-output' );
	?>
</div> <!-- end .kata-admin -->
<?php
do_action( 'kata_plus_control_panel' );
