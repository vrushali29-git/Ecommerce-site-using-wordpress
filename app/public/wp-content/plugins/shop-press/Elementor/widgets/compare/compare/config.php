<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressStyler;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class ProductsCompare extends ShopPressStyler {
	public function get_name() {
		return 'sp-compare';
	}

	public function get_style_depends() {
		if ( is_rtl() ) {
			return array( 'sp-compare', 'sp-compare-rtl' );
		} else {
			return array( 'sp-compare' );
		}
	}

	public function get_script_depends() {

		return array( 'sp-compare' );
	}

	public function get_title() {
		return __( 'Products Compare', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-products-compare';
	}

	public function get_categories() {
		return array( 'sp_compare' );
	}

	public function setup_styling_options() {
		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'table',
			__( 'Table', 'shop-press' ),
			array(
				'table' => array(
					'label'    => esc_html__( 'table', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare table',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tr'    => array(
					'label'    => esc_html__( 'tr', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare tr',
					'wrapper'  => '{{WRAPPER}}',
				),
				'td'    => array(
					'label'    => esc_html__( 'td', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare td',
					'wrapper'  => '{{WRAPPER}}',
				),
				'tbody' => array(
					'label'    => esc_html__( 'tbody', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare tbody',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'remove',
			__( 'Remove', 'shop-press' ),
			array(
				'remove_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare-button',
					'wrapper'  => '{{WRAPPER}}',
				),
				'remove_icon'    => array(
					'label'    => esc_html__( 'Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-compare-button svg',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'thumbnail',
			__( 'Thumbnail', 'shop-press' ),
			array(
				'thumbnail_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.thumbnail td:last-child',
					'wrapper'  => '{{WRAPPER}}',
				),
				'thumbnail'         => array(
					'label'    => esc_html__( 'Thumbnail', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.thumbnail img',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'title',
			__( 'Title', 'shop-press' ),
			array(
				'title_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.title',
					'wrapper'  => '{{WRAPPER}}',
				),
				'title'         => array(
					'label'    => esc_html__( 'Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.title td:last-child',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'categories',
			__( 'Categories', 'shop-press' ),
			array(
				'categories_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.categories',
					'wrapper'  => '{{WRAPPER}}',
				),
				'categories_link'    => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.categories a',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'price',
			__( 'Price', 'shop-press' ),
			array(
				'price_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.price',
					'wrapper'  => '{{WRAPPER}}',
				),
				'price'         => array(
					'label'    => esc_html__( 'Price', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.woocommerce-Price-amount.amount',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);

		$this->register_group_styler(
			'attributes',
			__( 'Attributes', 'shop-press' ),
			array(
				'attribute_wrapper'     => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.attribute',
					'wrapper'  => '{{WRAPPER}}',
				),
				'attribute_label'       => array(
					'label'    => esc_html__( 'Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.attribute-label',
					'wrapper'  => '{{WRAPPER}} tr.attribute',
				),
				'attribute_value'       => array(
					'label'    => esc_html__( 'Value', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.attribute-value',
					'wrapper'  => '{{WRAPPER}} tr.attribute',
				),
				'attribute_value_names' => array(
					'label'    => esc_html__( 'Value Names', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p',
					'wrapper'  => '{{WRAPPER}} tr.attribute .attribute-value',
				),
				'rating_stars'          => array(
					'label'    => esc_html__( 'Stars', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.star-rating',
					'wrapper'  => '{{WRAPPER}} tr.attribute .attribute-value.ratings',
				),
				'rating_full_stars'     => array(
					'label'    => esc_html__( 'Full Stars', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} tr.attribute .attribute-value.ratings .star-rating',
				),

			)
		);

		$this->register_group_styler(
			'add_to_cart',
			__( 'Add To Cart', 'shop-press' ),
			array(
				'add_to_cart_wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.add_to_cart',
					'wrapper'  => '{{WRAPPER}}',
				),
				'add_to_cart'         => array(
					'label'    => esc_html__( 'Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.add_to_cart .button',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'shop-press' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'raw'       => '<strong>' . __( 'The Products Compare is displayed by this widget.', 'shop-press' ) . '</strong>',
				'type'      => Controls_Manager::RAW_HTML,
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		if ( $this->is_editor() ) {
			?>
			<script>
				(function($){
					shoppress_compare_init($);
				})(jQuery);
			</script>
			<?php
		}
		sp_load_builder_template( 'compare/compare-template' );
	}
}
