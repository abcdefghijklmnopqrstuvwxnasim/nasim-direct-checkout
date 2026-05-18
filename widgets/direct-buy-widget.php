<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Nasim_Direct_Buy_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'nasim_direct_buy_button';
    }

    public function get_title() {
        return __( 'Direct Buy Button (Auto ID)', 'nasim-direct-checkout' );
    }

    public function get_icon() {
        return 'eicon-button';
    }

    public function get_categories() {
        return [ 'general' ]; 
    }

    protected function register_controls() {

        // ================= Content Tab =================
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Button Settings', 'nasim-direct-checkout' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'nasim-direct-checkout' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Buy Now', 'nasim-direct-checkout' ),
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'nasim-direct-checkout' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __( 'Left', 'nasim-direct-checkout' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => __( 'Center', 'nasim-direct-checkout' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => __( 'Right', 'nasim-direct-checkout' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'nasim-direct-checkout' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
                'default' => '',
            ]
        );

        $this->end_controls_section();


        // ================= Style Tab =================
        $this->start_controls_section(
            'style_section',
            [
                'label' => __( 'Button Style', 'nasim-direct-checkout' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .nasim-buy-button',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'nasim-direct-checkout' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nasim-buy-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __( 'Background Color', 'nasim-direct-checkout' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nasim-buy-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'hover_background_color',
            [
                'label' => __( 'Hover Background Color', 'nasim-direct-checkout' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nasim-buy-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .nasim-buy-button',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'nasim-direct-checkout' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .nasim-buy-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __( 'Padding', 'nasim-direct-checkout' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .nasim-buy-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $button_text = ! empty( $settings['button_text'] ) ? $settings['button_text'] : 'Buy Now';

        // Check if WooCommerce is installed and active
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<p style="color:red;">WooCommerce is not active!</p>';
            return;
        }

        global $product;

        // Try to get the product object dynamically
        if ( ! is_a( $product, 'WC_Product' ) ) {
            $product_id = get_the_ID();
            $current_product = wc_get_product( $product_id );
            
            if ( ! is_a( $current_product, 'WC_Product' ) ) {
                // If this widget is used outside a product page (e.g. standard page or editor without preview)
                echo '<a href="#" class="elementor-button elementor-size-md nasim-buy-button" style="opacity: 0.7; pointer-events: none;">Only works on Single Product Page</a>';
                return;
            } else {
                $product = $current_product;
            }
        }

        $product_id = $product->get_id();

        // Generate Direct Checkout URL
        $checkout_url = wc_get_checkout_url();
        $direct_checkout_url = add_query_arg( 'add-to-cart', $product_id, $checkout_url );

        ?>
        <div class="elementor-button-wrapper">
            <a href="<?php echo esc_url( $direct_checkout_url ); ?>" class="elementor-button elementor-size-md nasim-buy-button" style="transition: all 0.3s ease;">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text"><?php echo esc_html( $button_text ); ?></span>
                </span>
            </a>
        </div>
        <?php
    }
}