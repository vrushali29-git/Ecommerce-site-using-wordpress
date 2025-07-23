<?php
namespace ShopPress\Elementor\Widgets;

use ShopPress\Elementor\ShopPressWidgets;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class QR extends ShopPressWidgets {

    public function get_name() {
        return 'sp-qr';
    }

    public function get_title() {
        return __( 'Product QR Code', 'shop-press' );
    }

    public function get_icon() {
        return 'sp-widget sp-eicon-product-qr';
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
                    'selector' => '.sp-qr-code',
                    'wrapper'  => '{{WRAPPER}}',
                ),
                'qr_img' => array(
                    'label'    => esc_html__( 'QR Image', 'shop-press' ),
                    'type'     => 'styler',
                    'selector' => 'img',
                    'wrapper'  => '{{WRAPPER}} .sp-qr-code',
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
            'qr_size',
            array(
                'label' => __( 'QR Code Size', 'shop-press' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range' => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 500,
                        'step' => 1,
                    ),
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 100,
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

		$args = array(
			'size'  => $settings['qr_size']['size'],
		);

		sp_load_builder_template( 'single-product/product-qr', $args );
    }

    protected function content_template() {
        $qr_size = 100; // Default size for editor
        $sample_qr_url = SHOPPRESS_URL . 'Elementor/widgets/single-product/qr/images/sample-qr.png';
        ?>
        <div class="sp-qr-code">
            <img 
                src="<?php echo esc_url( $sample_qr_url ); ?>" 
                alt="QR Code Placeholder" 
                width="<?php echo esc_attr( $qr_size ); ?>"
                height="<?php echo esc_attr( $qr_size ); ?>"
            />
        </div>
        <?php
    }
} 