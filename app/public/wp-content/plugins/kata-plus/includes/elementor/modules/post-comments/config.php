<?php
/**
 * Post Comments module config.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Kata_Plus_Post_Comments extends Widget_Base {
	public function get_name() {
		return 'kata-plus-post-comments';
	}

	public function get_title() {
		return esc_html__( 'Post Comments', 'kata-plus' );
	}

	public function get_script_depends() {
		return array( 'kata-plus-comments' );
	}

	public function get_icon() {
		return 'kata-widget kata-eicon-comments';
	}

	public function get_categories() {
		return array( 'kata_plus_elementor_blog_and_post' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'wrapper',
			array(
				'label' => esc_html__( 'Wrapper', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_container',
			array(
				'label'    => esc_html__( 'Container', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.comment-respond',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'styles_section',
			array(
				'label' => esc_html__( 'Form', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'styler_title',
			array(
				'label'    => esc_html__( 'Title', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area #reply-title',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_notes',
			array(
				'label'    => esc_html__( 'Note', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-notes',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_logged_textarea',
			array(
				'label'    => esc_html__( 'Post Comments textarea', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area textarea',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_name',
			array(
				'label'    => esc_html__( 'Name Field Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-author',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_name_label',
			array(
				'label'    => esc_html__( 'Name Field Label', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-author .label-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_name_input',
			array(
				'label'    => esc_html__( 'Name Field', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-author input[type="text"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_email',
			array(
				'label'    => esc_html__( 'Email Field Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-email',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_email_label',
			array(
				'label'    => esc_html__( 'Email Field Label', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-email .label-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_email_input',
			array(
				'label'    => esc_html__( 'Email Field', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-email input[type="text"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_website',
			array(
				'label'    => esc_html__( 'Website Field Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-url',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_website_label',
			array(
				'label'    => esc_html__( 'Website Field Label', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-url .label-wrap',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_website_input',
			array(
				'label'    => esc_html__( 'Website Field', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-url input[type="text"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_consent_wrapper',
			array(
				'label'    => esc_html__( 'Consent Checkbox Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-cookies-consent',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_consent_check_box',
			array(
				'label'    => esc_html__( 'Consent Checkbox', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-cookies-consent #wp-comment-cookies-consent',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_consent_check_box_label',
			array(
				'label'    => esc_html__( 'Consent Checkbox label', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-cookies-consent [for="wp-comment-cookies-consent"]',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_submit_btn_wrapper',
			array(
				'label'    => esc_html__( 'Submit Button Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .form-submit',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_submit_btn',
			array(
				'label'    => esc_html__( 'Submit Button', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .form-submit .submit',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_logged',
			array(
				'label'    => esc_html__( 'Post Comments logged in as', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .logged-in-as',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_logged_link',
			array(
				'label'    => esc_html__( 'Post Comments logged link', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .logged-in-as a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_logged_comment',
			array(
				'label'    => esc_html__( 'Post Comments Comment Title', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-form-comment label',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_logged_submit',
			array(
				'label'    => esc_html__( 'Post Comments Submit', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area input#submit',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'styler_posted_comments_number',
			array(
				'label'    => esc_html__( 'Number Of Comments Posted', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area h2.kata-comments-title',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'comments',
			array(
				'label' => esc_html__( 'Comments', 'kata-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'comments_wrapper',
			array(
				'label'    => esc_html__( 'Wrapper', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comment-list',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'comments_item',
			array(
				'label'    => esc_html__( 'Comment', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comment-list .comment',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'comments_avatar',
			array(
				'label'    => esc_html__( 'Avatar', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-author.vcard img',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'comments_user',
			array(
				'label'    => esc_html__( 'User', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comment-list .comment-author .fn a',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'comments_content',
			array(
				'label'    => esc_html__( 'Content', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.kata-comments-area .comment-content',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->add_control(
			'comments_reply',
			array(
				'label'    => esc_html__( 'Reply Button', 'kata-plus' ),
				'type'     => 'styler',
				'selector' => '.comment-reply-link',
				'isSVG'    => true,
				'isInput'  => false,
				'wrapper'  => '{{WRAPPER}}',
			)
		);
		$this->end_controls_section();

		// Common controls
		do_action( 'kata_plus_common_controls', $this );
	}

	protected function render() {
		require __DIR__ . '/view.php';
	}
}
