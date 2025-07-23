<?php
/**
 * Template Library.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor\TemplateLibrary;

use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Base;

/**
 * Custom source.
 */
class TemplateSourceContent extends Source_Base {
	/**
	 * Get remote template ID.
	 *
	 * Retrieve the remote template ID.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string The remote template ID.
	 */
	public function get_id() {
		return 'product_loop';
	}

	/**
	 * Get remote template title.
	 *
	 * Retrieve the remote template title.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string The remote template title.
	 */
	public function get_title() {
		return 'Content';
	}

	/**
	 * Register remote template data.
	 *
	 * Used to register custom template data like a post type, a taxonomy or any
	 * other data.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_data() {}

	/**
	 * Get remote templates.
	 *
	 * Retrieve remote templates from climaxthemes servers.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param array $args Optional. Nou used in remote source.
	 *
	 * @return array Remote templates.
	 */
	public function get_items( $args = array() ) {
		$library_data = TemplatesLib::get_library_data();

		$templates = array();

		if ( ! empty( $library_data ) ) {
			foreach ( $library_data as $template_data ) {
				$templates[] = $this->prepare_template( $template_data );
			}
		}

		return $templates;
	}

	/**
	 * Get remote template.
	 *
	 * Retrieve a single remote template from climaxthemes servers.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return array Remote template.
	 */
	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	/**
	 * Save remote template.
	 *
	 * Remote template from climaxthemes servers cannot be saved on the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param array $template_data Remote template data.
	 *
	 * @return \WP_Error
	 */
	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a remote source' );
	}

	/**
	 * Update remote template.
	 *
	 * Remote template from climaxthemes servers cannot be updated on the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param array $new_data New template data.
	 *
	 * @return \WP_Error
	 */
	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a remote source' );
	}

	/**
	 * Delete remote template.
	 *
	 * Remote template from climaxthemes servers cannot be deleted from the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Error
	 */
	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a remote source' );
	}

	/**
	 * Export remote template.
	 *
	 * Remote template from climaxthemes servers cannot be exported from the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return \WP_Error
	 */
	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a remote source' );
	}

	/**
	 * Get remote template data.
	 *
	 * Retrieve the data of a single remote template from climaxthemes servers.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param array  $args    Custom template arguments.
	 * @param string $context Optional. The context. Default is `display`.
	 *
	 * @return array|\WP_Error Remote Template data.
	 */
	public function get_data( array $args, $context = 'display' ) {
		$data = TemplatesLib::shoppress_get_template_data( $args['template_id'] );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$data = (array) $data;

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id  = $args['editor_post_id'];
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}

	/**
	 * Prepare template.
	 *
	 * Prepare template data.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $template_data Collection of template data.
	 * @return array Collection of template data.
	 */
	private function prepare_template( array $template_data ) {
		$favorite_templates = $this->get_user_meta( 'favorites' );

		return array(
			'template_id' => $template_data['id'],
			'source'      => $this->get_id(),
			'type'        => $template_data['type'],
			'subtype'     => $template_data['subtype'],
			'title'       => $template_data['title'],
			'thumbnail'   => $template_data['thumbnail'],
			'date'        => $template_data['tmpl_created'],
			'author'      => $template_data['author'],
			'tags'        => $template_data['tags'],
			'isPro'       => $template_data['is_pro'],
			'url'         => $template_data['url'],
			'favorite'    => ! empty( $favorite_templates[ $template_data['id'] ] ),
		);
	}
}
