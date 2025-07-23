<?php
namespace ShopPress\Elementor\Widgets;

use Elementor\Controls_Manager;
use ShopPress\Elementor\ShopPressWidgets;

defined( 'ABSPATH' ) || exit;

class Tabs extends ShopPressWidgets {

	public function get_name() {
		return 'sp-tabs';
	}

	public function get_title() {
		return __( 'Product Tabs', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-tabs';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper' => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-tabs',
					'wrapper'  => '{{WRAPPER}}',
				),
			)
		);
		$this->register_group_styler(
			'tabs_title',
			__( 'Tabs', 'shop-press' ),
			array(
				'tabs'              => array(
					'label'    => esc_html__( 'Tabs Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'ul.tabs.wc-tabs',
					'wrapper'  => '{{WRAPPER}} .sp-tabs.woocommerce-tabs.wc-tabs-wrapper',
				),
				'tab_item'          => array(
					'label'    => esc_html__( 'Tabs Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'li',
					'wrapper'  => '{{WRAPPER}} .sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs',
				),
				'tab_title'         => array(
					'label'    => esc_html__( 'Tabs Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs li',
				),
				'tab_item_active'   => array(
					'label'    => esc_html__( 'Active Tab Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'li.active',
					'wrapper'  => '{{WRAPPER}} .sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs',
				),
				'tab_item_a_active' => array(
					'label'    => esc_html__( 'Active Tab Item link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} .sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs li.active',
				),
			),
			array(
				'style' => 'normal',
			)
		);

		$this->register_group_styler(
			'accordion',
			__( 'Accordion', 'shop-press' ),
			array(
				'accordions'                  => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-accordions-container',
					'wrapper'  => '{{WRAPPER}}',
				),
				'accordion_item'              => array(
					'label'    => esc_html__( 'Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-accordion-item',
					'wrapper'  => '{{WRAPPER}} .sp-accordions-container',
				),
				'accordion_title'             => array(
					'label'    => esc_html__( 'Item Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-accordion-item-header',
					'wrapper'  => '{{WRAPPER}} .sp-accordions-container .sp-accordion-item',
				),
				'accordion_title_icon'        => array(
					'label'    => esc_html__( 'Item Title Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'img',
					'wrapper'  => '{{WRAPPER}} .sp-accordions-container .sp-accordion-item .sp-accordion-item-header',
				),
				'accordion_title_toggle_icon' => array(
					'label'    => esc_html__( 'Item Title Toggle Icon', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-accordion-item-header-icon svg',
					'wrapper'  => '{{WRAPPER}} .sp-accordions-container .sp-accordion-item .sp-accordion-item-header ',
				),
				'accordion_item_active'       => array(
					'label'    => esc_html__( 'Active Item', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-accordion-item.open',
					'wrapper'  => '{{WRAPPER}} .sp-accordions-container',
				),
				'accordion_item_a_active'     => array(
					'label'    => esc_html__( 'Active Item Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-accordion-item-header',
					'wrapper'  => '{{WRAPPER}} .sp-accordions-container .sp-accordion-item.open',
				),
				'accordion_item_content'      => array(
					'label'    => esc_html__( 'Item Content Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-accordion-item-content',
					'wrapper'  => '{{WRAPPER}} .sp-accordions-container .sp-accordion-item',
				),
				'accordion_item_content_p'    => array(
					'label'    => esc_html__( 'Item Content', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p',
					'wrapper'  => '{{WRAPPER}} .sp-accordions-container .sp-accordion-item .sp-accordion-item-content ',
				),
			),
			array(
				'style' => 'accordion',
			)
		);

		$this->register_group_styler(
			'desc_tab',
			__( 'Description', 'shop-press' ),
			array(
				'tab_item_panel'   => array(
					'label'    => esc_html__( 'Tabs Panel', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-Tabs-panel',
					'wrapper'  => '{{WRAPPER}} .sp-tabs.woocommerce-tabs.wc-tabs-wrapper',
				),
				'tab_desc_content' => array(
					'label'    => esc_html__( 'Description Content', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p',
					'wrapper'  => '{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel.woocommerce-Tabs-panel--description',
				),
			)
		);
		$this->register_group_styler(
			'attributes',
			__( 'Attributes', 'shop-press' ),
			array(
				'table'      => array(
					'label'    => esc_html__( 'Attributes Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.woocommerce-product-attributes',
					'wrapper'  => '{{WRAPPER}} .woocommerce-Tabs-panel.woocommerce-Tabs-panel--additional_information',
				),
				'tbody'      => array(
					'label'    => esc_html__( 'Table Body', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tbody',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-attributes',
				),
				'tr'         => array(
					'label'    => esc_html__( 'Table Row', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'tr.woocommerce-product-attributes-item',
					'wrapper'  => '{{WRAPPER}} .woocommerce-product-attributes tbody',
				),
				'th'         => array(
					'label'    => esc_html__( 'Table Header', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'th.woocommerce-product-attributes-item__label',
					'wrapper'  => '{{WRAPPER}} tr.woocommerce-product-attributes-item',
				),
				'td'         => array(
					'label'    => esc_html__( 'Value Cell', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'td.woocommerce-product-attributes-item__value',
					'wrapper'  => '{{WRAPPER}} tr.woocommerce-product-attributes-item',
				),
				'td_content' => array(
					'label'    => esc_html__( 'Value Content', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p',
					'wrapper'  => '{{WRAPPER}} td.woocommerce-product-attributes-item__value',
				),
				'td_link'    => array(
					'label'    => esc_html__( 'Link', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'a',
					'wrapper'  => '{{WRAPPER}} td.woocommerce-product-attributes-item__value p',
				),
			)
		);
		$this->register_group_styler(
			'reviews',
			__( 'Review', 'shop-press' ),
			array(
				'reviews_wrapper'                  => array(
					'label'    => esc_html__( 'Reviews Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#reviews.woocommerce-Reviews',
					'wrapper'  => '{{WRAPPER}} .woocommerce-Tabs-panel.woocommerce-Tabs-panel--reviews',
				),
				// 'review_summary'                   => array(
				// 'label'    => esc_html__( 'Comments Summary Wrapper', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => '.kata-review-summery',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews',
				// ),
				// 'overall_rating_wrap'              => array(
				// 'label'    => esc_html__( 'Overall Rating Wrapper', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => '.overall-rating-wrap',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery',
				// ),
				// 'average_rating'                   => array(
				// 'label'    => esc_html__( 'Average Rating', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => 'div.average-rating',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap',
				// ),
				// 'avg_stars_wrap'                   => array(
				// 'label'    => esc_html__( 'Average Stars Wrapper', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => '.star-rating',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap .average-rating-stars',
				// ),
				// 'avg_star'                         => array(
				// 'label'    => esc_html__( 'Average Star', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => 'span',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap .average-rating-stars .star-rating',
				// ),
				// 'reviews_count'                    => array(
				// 'label'    => esc_html__( 'Reviews Count', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => '.reviews-count',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap',
				// ),
				// 'comment_write_wrap'               => array(
				// 'label'    => esc_html__( 'Review Button Wrapper', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => '.write-a-review',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap',
				// ),
				// 'comment_write'                    => array(
				// 'label'    => esc_html__( 'Review Button', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => '.button',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .overall-rating-wrap .write-a-review',
				// ),
				// 'rating-summary-wrap'              => array(
				// 'label'    => esc_html__( 'Rating Summary Wrapper', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => '.rating-summary-wrap',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery',
				// ),
				// 'rating-summary-item'              => array(
				// 'label'    => esc_html__( 'Rating Summary Item', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => 'div.item',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .rating-summary-wrap',
				// ),
				// 'item_rate_count'                  => array(
				// 'label'    => esc_html__( 'Item Rate Count', 'shop-press' ),
				// 'type'     => 'styler',
				// 'selector' => '.rate-count',
				// 'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews .kata-review-summery .rating-summary-wrap div.item',
				// ),
				'comments_container'               => array(
					'label'    => esc_html__( 'Comments Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#comments',
					'wrapper'  => '{{WRAPPER}} #reviews',
				),
				'review_title'                     => array(
					'label'     => esc_html__( 'Review Title', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => 'h2.woocommerce-Reviews-title',
					'wrapper'   => '{{WRAPPER}} #comments',
					'separator' => 'before',
				),
				'product_review_title'             => array(
					'label'    => esc_html__( 'Product Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span',
					'wrapper'  => '{{WRAPPER}} h2.woocommerce-Reviews-title',
				),
				'comments_list_wrapper'            => array(
					'label'     => esc_html__( 'Comments List Wrapper', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => 'ol.commentlist',
					'wrapper'   => '{{WRAPPER}} #reviews #comments',
					'separator' => 'before',
				),
				'comment_item'                     => array(
					'label'    => esc_html__( 'Comment', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'li',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist',
				),
				'comment_item_container'           => array(
					'label'    => esc_html__( 'Comment Item Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.comment_container',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist li',
				),
				'comment_item_img'                 => array(
					'label'    => esc_html__( 'Comment Item Image', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'img.avatar',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container',
				),
				'comment_item_text'                => array(
					'label'    => esc_html__( 'Comment Item Text', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.comment-text',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container',
				),
				'comment_item_meta_wrapper'        => array(
					'label'    => esc_html__( 'Comment Item Meta Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.meta',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container .comment-text',
				),
				'comment_item_author'              => array(
					'label'    => esc_html__( 'Comment Item Author', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'strong.woocommerce-review__author',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container .comment-text .meta',
				),
				'comment_item_date'                => array(
					'label'    => esc_html__( 'Comment Item Date', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'time.woocommerce-review__published-date',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container .comment-text .meta',
				),
				'comment_item_star_rating_wrapper' => array(
					'label'    => esc_html__( 'Comment Item Stars Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.star-rating',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container .comment-text',
				),
				'comment_item_empty_stars'         => array(
					'label'    => esc_html__( 'Comment Item Empty Stars', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.star-rating:before',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container .comment-text',
				),
				'comment_item_full_stars'          => array(
					'label'    => esc_html__( 'Comment Item Full Stars', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.star-rating span:before',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container .comment-text',
				),
				'comment_item_desc'                => array(
					'label'    => esc_html__( 'Comment Item Description', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p',
					'wrapper'  => '{{WRAPPER}} #reviews #comments ol.commentlist .comment_container .comment-text .description',
				),
				'form_wrapper'                     => array(
					'label'     => esc_html__( 'Form Wrapper', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => '#review_form_wrapper',
					'wrapper'   => '{{WRAPPER}} #reviews.woocommerce-Reviews',
					'separator' => 'before',
				),
				'form_container'                   => array(
					'label'    => esc_html__( 'Reviews Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#review_form',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper',
				),
				'form_respond'                     => array(
					'label'    => esc_html__( 'Form', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#respond.comment-respond',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form',
				),
				'reply_title'                      => array(
					'label'    => esc_html__( 'Reply Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.comment-reply-title',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond',
				),
				'comment_form'                     => array(
					'label'     => esc_html__( 'Comment Form', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => 'form.comment-form',
					'wrapper'   => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond',
					'separator' => 'before',
				),
				'comment_form_rating'              => array(
					'label'    => esc_html__( 'Comment Form Rating', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.rating',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form .comment-form-rating',
				),
				'comment_form_rating_label'        => array(
					'label'    => esc_html__( 'Comment Form Rating Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'label',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form .comment-form-rating',
				),
				'comment_text_wrap'                => array(
					'label'    => esc_html__( 'Form Text Area Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'p.comment-form-comment',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form',
				),
				'comment_text_label'               => array(
					'label'    => esc_html__( 'Comment Form Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'label',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form p.comment-form-comment',
				),
				'comment_text'                     => array(
					'label'    => esc_html__( 'Comment Textarea', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'textarea',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form p.comment-form-comment',
				),
				'form_submit'                      => array(
					'label'     => esc_html__( 'Submit Button Wrapper', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => 'p.form-submit',
					'wrapper'   => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form',
					'separator' => 'before',
				),
				'submit_button'                    => array(
					'label'    => esc_html__( 'Submit Button', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'input[type="submit"]',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form p.form-submit',
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
			'style',
			array(
				'label'   => __( 'Style', 'shop-press' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => array(
					'normal'    => __( 'Normal', 'shop-press' ),
					'accordion' => __( 'Accordion', 'shop-press' ),
				),
			)
		);

		$this->add_control(
			'open_first_tab',
			array(
				'label'        => __( 'Open First Tab', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array(
					'style' => 'accordion',
				),
			)
		);

		$this->end_controls_section();
		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		do_action( 'shoppress/widget/before_render', $settings );

		$args = array();
		if ( $this->editor_preview() ) {
			if ( 'accordion' == $settings['style'] ) {

				$args['open_first_tab'] = 'yes' === ( $settings['open_first_tab'] ?? '' );
				sp_load_builder_template( 'single-product/product-tabs-accordions', $args );
			} else {

				sp_load_builder_template( 'single-product/product-tabs', $args );
			}
		}
	}
}
