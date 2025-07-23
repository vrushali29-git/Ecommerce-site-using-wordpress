<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Review extends ShopPressWidgets {
	public function get_name() {
		return 'sp-review';
	}

	public function get_title() {
		return __( 'Product Reviews', 'shop-press' );
	}

	public function get_icon() {
		return 'sp-widget sp-eicon-product-review';
	}

	public function get_categories() {
		return array( 'sp_woo_single' );
	}

	public function get_style_depends() {
		return array( 'sp-radio-button', 'wc-single-product' );
	}

	public function get_script_depends() {
		return array( 'sp-radio-button', 'wc-single-product' );
	}

	public function setup_styling_options() {

		$this->register_group_styler(
			'wrapper',
			__( 'Wrapper', 'shop-press' ),
			array(
				'wrapper'         => array(
					'label'    => esc_html__( 'Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-reviews-wrapper',
					'wrapper'  => '{{WRAPPER}}',
				),
				'reviews_wrapper' => array(
					'label'    => esc_html__( 'Reviews Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#reviews.woocommerce-Reviews',
					'wrapper'  => '{{WRAPPER}} .sp-reviews-wrapper',
				),
			)
		);

		$this->register_group_styler(
			'reviews_custom_heading',
			__( 'Heading', 'shop-press' ),
			array(
				'reviews_custom_heading' => array(
					'label'    => esc_html__( 'Custom Heading', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.sp-reviews-heading',
					'wrapper'  => '{{WRAPPER}}',
				),
			),
		);

		$this->register_group_styler(
			'comments',
			__( 'Comments', 'shop-press' ),
			array(
				'comments_container'               => array(
					'label'    => esc_html__( 'Comments Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#comments',
					'wrapper'  => '{{WRAPPER}} #reviews',
				),
				'review_title'                     => array(
					'label'    => esc_html__( 'Review Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'h2.woocommerce-Reviews-title',
					'wrapper'  => '{{WRAPPER}} #comments',
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
					'label'     => esc_html__( 'Comment Item Container', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => '.comment_container',
					'wrapper'   => '{{WRAPPER}} #reviews #comments ol.commentlist li',
					'separator' => 'before',
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
			)
		);

		$this->register_group_styler(
			'form',
			__( 'Form', 'shop-press' ),
			array(
				'form_wrapper'              => array(
					'label'    => esc_html__( 'Form Wrapper', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#review_form_wrapper',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews',
				),
				'form_container'            => array(
					'label'    => esc_html__( 'Reviews Container', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#review_form',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper',
				),
				'form_respond'              => array(
					'label'    => esc_html__( 'Form', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '#respond.comment-respond',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form',
				),
				'reply_title'               => array(
					'label'    => esc_html__( 'Reply Title', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'span.comment-reply-title',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond',
				),
				'comment_form'              => array(
					'label'     => esc_html__( 'Comment Form', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => 'form.comment-form',
					'wrapper'   => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond',
					'separator' => 'before',
				),
				'comment_form_rating'       => array(
					'label'    => esc_html__( 'Comment Form Rating', 'shop-press' ),
					'type'     => 'styler',
					'selector' => '.rating',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form .comment-form-rating',
				),
				'comment_form_rating_label' => array(
					'label'    => esc_html__( 'Comment Form Rating Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'label',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form .comment-form-rating',
				),
				'comment_text_wrap'         => array(
					'label'     => esc_html__( 'Form Text Area Wrapper', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => 'p.comment-form-comment',
					'wrapper'   => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form',
					'separator' => 'before',
				),
				'comment_text_label'        => array(
					'label'    => esc_html__( 'Comment Form Label', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'label',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form p.comment-form-comment',
				),
				'comment_text'              => array(
					'label'    => esc_html__( 'Comment Textarea', 'shop-press' ),
					'type'     => 'styler',
					'selector' => 'textarea',
					'wrapper'  => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form p.comment-form-comment',
				),
				'form_submit'               => array(
					'label'     => esc_html__( 'Submit Button Wrapper', 'shop-press' ),
					'type'      => 'styler',
					'selector'  => 'p.form-submit',
					'wrapper'   => '{{WRAPPER}} #reviews.woocommerce-Reviews #review_form_wrapper #review_form #respond.comment-respond form.comment-form',
					'separator' => 'before',
				),
				'submit_button'             => array(
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
			'custom_heading',
			array(
				'label'        => esc_html__( 'Custom Heading', 'shop-press' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Hide', 'shop-press' ),
				'label_on'     => esc_html__( 'Show', 'shop-press' ),
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'review_heading',
			array(
				'type'      => Controls_Manager::TEXT,
				'label'     => esc_html__( 'Heading', 'shop-press' ),
				'condition' => array(
					'custom_heading' => 'true',
				),
			)
		);

		$this->add_control(
			'heading_tag',
			array(
				'label'     => __( 'Heading HTML Tag', 'shop-press' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h4',
				'options'   => array(
					'h1'   => __( 'h1', 'shop-press' ),
					'h2'   => __( 'h2', 'shop-press' ),
					'h3'   => __( 'h3', 'shop-press' ),
					'h4'   => __( 'h4', 'shop-press' ),
					'h5'   => __( 'h5', 'shop-press' ),
					'h6'   => __( 'h6', 'shop-press' ),
					'div'  => __( 'div', 'shop-press' ),
					'span' => __( 'span', 'shop-press' ),
				),
				'condition' => array(
					'custom_heading' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->setup_styling_options();

		do_action( 'shoppress/elementor/widget/register_controls_init', $this );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( $this->is_editor() ) {
			\WC_Template_Loader::init();
		}

		do_action( 'shoppress/widget/before_render', $this->get_settings_for_display() );

		$args = array(
			'custom_heading' => $settings['custom_heading'],
			'review_heading' => $settings['review_heading'],
			'heading_tag'    => $settings['heading_tag'],
		);

		if ( $this->editor_preview() ) {
			sp_load_builder_template( 'single-product/product-review', $args );
		}
	}
}
