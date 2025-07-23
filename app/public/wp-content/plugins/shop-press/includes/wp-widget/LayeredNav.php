<?php
/**
 * Layered nav widget
 *
 * @package WooCommerce\Widgets
 * @version 2.6.0
 */


defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer;


class ShopPress_Widget_Layered_Nav extends \WC_Widget_Layered_Nav {

	/**
	 * Show dropdown layered nav.
	 *
	 * @param  array  $terms Terms.
	 * @param  string $taxonomy Taxonomy.
	 * @param  string $query_type Query Type.
	 * @return bool Will nav display?
	 */
	protected function layered_nav_dropdown( $terms, $taxonomy, $query_type ) {
		global $wp;
		$found = false;

		if ( $taxonomy !== $this->get_current_taxonomy() ) {
			$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes   = WC_Query::get_layered_nav_chosen_attributes();
			$taxonomy_filter_name = wc_attribute_taxonomy_slug( $taxonomy );
			$taxonomy_label       = wc_attribute_label( $taxonomy );

			/* translators: %s: taxonomy name */
			$any_label      = apply_filters( 'woocommerce_layered_nav_any_label', sprintf( __( 'Any %s', 'shop-press' ), $taxonomy_label ), $taxonomy_label, $taxonomy );
			$multiple       = 'or' === $query_type;
			$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();

			if ( '' === get_option( 'permalink_structure' ) ) {
				$form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
			} else {
				$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( user_trailingslashit( $wp->request ) ) );
			}

			echo '<form method="get" action="' . esc_url( $form_action ) . '" class="woocommerce-widget-layered-nav-dropdown">';
			echo '<select class="woocommerce-widget-layered-nav-dropdown dropdown_layered_nav_' . esc_attr( $taxonomy_filter_name ) . '"' . ( $multiple ? 'multiple="multiple"' : '' ) . '>';
			echo '<option value="">' . esc_html( $any_label ) . '</option>';

			foreach ( $terms as $term ) {

				// If on a term page, skip that term in widget list.
				if ( $term->term_id === $this->get_current_term_id() ) {
					continue;
				}

				// Get count based on current view.
				$option_is_set = in_array( $term->slug, $current_values, true );
				$count         = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// Only show options with count > 0.
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				echo '<option value="' . esc_attr( urldecode( $term->slug ) ) . '" ' . selected( $option_is_set, true, false ) . '>' . esc_html( $term->name ) . '</option>';
			}

			echo '</select>';

			if ( $multiple ) {
				echo '<button class="woocommerce-widget-layered-nav-dropdown__submit" type="submit" value="' . esc_attr__( 'Apply', 'shop-press' ) . '">' . esc_html__( 'Apply', 'shop-press' ) . '</button>';
			}

			if ( 'or' === $query_type ) {
				echo '<input type="hidden" name="query_type_' . esc_attr( $taxonomy_filter_name ) . '" value="or" />';
			}

			echo '<input type="hidden" name="filter_' . esc_attr( $taxonomy_filter_name ) . '" value="' . esc_attr( implode( ',', $current_values ) ) . '" />';
			echo wc_query_string_form_fields( null, array( 'filter_' . $taxonomy_filter_name, 'query_type_' . $taxonomy_filter_name ), '', true ); // @codingStandardsIgnoreLine
			echo '</form>';

			wc_enqueue_js(
				"
				// Update value on change.
				jQuery( document ).on( 'change', '.dropdown_layered_nav_" . esc_js( $taxonomy_filter_name ) . "', function() {
					var slug = jQuery( this ).val();
					jQuery( ':input[name=\"filter_" . esc_js( $taxonomy_filter_name ) . "\"]' ).val( slug );

					// Submit form on change if standard dropdown.
					if ( ! jQuery( this ).attr( 'multiple' ) ) {
						jQuery( this ).closest( 'form' ).trigger( 'submit' );
					}
				});

				// Use Select2 enhancement if possible
				if ( jQuery().selectWoo ) {
					var wc_layered_nav_select = function() {
						jQuery( '.dropdown_layered_nav_" . esc_js( $taxonomy_filter_name ) . "' ).selectWoo( {
							placeholder: decodeURITemplate('" . rawurlencode( (string) wp_specialchars_decode( $any_label ) ) . "'),
							minimumResultsForSearch: 5,
							width: '100%',
							allowClear: " . ( $multiple ? 'false' : 'true' ) . ",
							language: {
								noResults: function() {
									return '" . esc_js( _x( 'No matches found', 'enhanced select', 'shop-press' ) ) . "';
								}
							}
						} );
					};
					wc_layered_nav_select();
				}
			"
			);
		}

		return $found;
	}

	/**
	 * Show list based layered nav.
	 *
	 * @param  array  $terms Terms.
	 * @param  string $taxonomy Taxonomy.
	 * @param  string $query_type Query Type.
	 * @return bool   Will nav display?
	 */
	protected function layered_nav_list( $terms, $taxonomy, $query_type ) {
		echo '<ul class="woocommerce-widget-layered-nav-list">';

		$term_counts           = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
		$_chosen_attributes    = WC_Query::get_layered_nav_chosen_attributes();
		$found                 = false;
		$base_link             = $this->get_current_page_url();
		$is_variation_swatches = sp_is_module_active( 'variation_swatches' ) && class_exists( 'Kata_Plus' );

		foreach ( $terms as $term ) {
			$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
			$option_is_set  = in_array( $term->slug, $current_values, true );
			$count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

			// Skip the term for the current archive.
			if ( $this->get_current_term_id() === $term->term_id ) {
				continue;
			}

			// Only show options with count > 0.
			if ( 0 < $count ) {
				$found = true;
			} elseif ( 0 === $count && ! $option_is_set ) {
				continue;
			}

			$filter_name = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );

			$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current_filter = array_map( 'sanitize_title', $current_filter );

			if ( ! in_array( $term->slug, $current_filter, true ) ) {
				$current_filter[] = $term->slug;
			}

			$link = remove_query_arg( $filter_name, $base_link );

			// Add current filters to URL.
			foreach ( $current_filter as $key => $value ) {
				// Exclude query arg for current term archive term.
				if ( $value === $this->get_current_term_slug() ) {
					unset( $current_filter[ $key ] );
				}

				// Exclude self so filter can be unset on click.
				if ( $option_is_set && $value === $term->slug ) {
					unset( $current_filter[ $key ] );
				}
			}

			if ( ! empty( $current_filter ) ) {
				asort( $current_filter );
				$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

				// Add Query type Arg to URL.
				if ( 'or' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
					$link = add_query_arg( 'query_type_' . wc_attribute_taxonomy_slug( $taxonomy ), 'or', $link );
				}
				$link = str_replace( '%2C', ',', $link );
			}
			$attribute   = wc_get_attribute( wc_attribute_taxonomy_id_by_name( str_replace( 'pa_', '', $term->taxonomy ) ) );
			$no_checkbox = $is_variation_swatches && ( $attribute->type !== 'select' && $this->get_term( $term->term_id, $term->taxonomy ) || $attribute->type === 'label' );
			$term_html   = '';

			if ( $count > 0 || $option_is_set ) {
				$link       = apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy );
				$term_html .= '<a rel="nofollow"' . ( $no_checkbox ? 'class="no-checkbox"' : '' ) . 'href="' . esc_url( $link ) . '">';

				if ( $is_variation_swatches && class_exists( 'Kata_Plus' ) ) {

					if ( $attribute->type == 'color' && $this->get_term( $term->term_id, $term->taxonomy ) ) {

						$color_term = $this->get_term( $term->term_id, $term->taxonomy );
						$color      = is_array( $color_term ) ? $color_term : array( 0 => $color_term );
						$c1         = $color[0];
						$c2         = apply_filters( 'shoppress/variation_swatches/attribute/color/color2', $c1, $color, 0 );
						$c1         = "{$c1} 0%, {$c1} 50%";
						$c2         = "{$c2} 50%, {$c2} 100%";
						$deg        = '315deg';

						$term_html .= '<span class="sp-nav-filter color" style="background:linear-gradient(' . esc_attr( $deg ) . ',' . esc_attr( $c1 ) . ',' . esc_attr( $c2 ) . ');"></span>';
					} elseif ( $attribute->type == 'label' ) {
						$term_html .= '<span class="sp-nav-filter label">' . esc_html( $term->name ) . '</span>';
					} elseif ( ( $attribute->type == 'image' || $attribute->type == 'brand' ) && $this->get_term( $term->term_id, $term->taxonomy ) ) {
						$term_html .= '<img class="sp-nav-img" width="50" height="50" src="' . esc_url( wp_get_attachment_url( $this->get_term( $term->term_id, $term->taxonomy ) ) ) . '" />';
					}
				}

				$term_html .= '<span class="attr-name">' . esc_html( $term->name ) . '</span>';
				$term_html .= '</a>';
			} else {
				$link       = false;
				$term_html .= '<span>' . esc_html( $term->name ) . '</span>';
			}

			$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );

			$full = $no_checkbox ? '' : ' full-wattr';

			echo '<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term ' . ( $option_is_set ? 'woocommerce-widget-layered-nav-list__item--chosen chosen ' : '' ) . ( $no_checkbox ? 'no-checkbox' : '' ) . esc_attr( $full ) . '">';
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'woocommerce_layered_nav_term_html', $term_html, $term, $link, $count );
			echo '</li>';
		}

		echo '</ul>';

		return $found;
	}

	/**
	 * Get term by id and taxonomy name.
	 *
	 * @return String
	 */
	public function get_term( $term_id, $taxonomy_name ) {
		return get_term_meta( $term_id, 'product_' . $taxonomy_name, true );
	}
}
