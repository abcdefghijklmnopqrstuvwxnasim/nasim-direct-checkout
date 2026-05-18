<?php
/**
 * Plugin Name: Nasim Direct Checkout Button for Elementor
 * Description: কাস্টম Elementor Buy Button যা প্রোডাক্ট কার্টে অ্যাড করে সরাসরি WooCommerce Checkout-এ নিয়ে যায়।
 * Plugin URI: https://nasimwebpro.com
 * Author: Bee IT Agency
 * Author URI: https://beeitagency.com
 * Version: 1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

final class Nasim_Direct_Checkout_Extension {

    public function __construct() {
        // Register widget using the standard Elementor hook
        add_action( 'elementor/widgets/register', [ $this, 'register_buy_button_widget' ] );
    }

    public function register_buy_button_widget( $widgets_manager ) {
        // Include the widget file
        require_once( __DIR__ . '/widgets/direct-buy-widget.php' );

        // Register the widget
        $widgets_manager->register( new \Nasim_Direct_Buy_Widget() );
    }
}

// Include the Plugin Update Checker library
require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/abcdefghijklmnopqrstuvwxnasim/nasim-direct-checkout', // আপনার গিটহাব রিপোজিটরির লিংক
    __FILE__,
    'nasim-direct-checkout'
);

// Set the branch that contains the stable release. (Optional)
$myUpdateChecker->setBranch('main');

// Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('আপনার-গিটহাব-পার্সোনাল-অ্যাক্সেস-টোকেন');

// Instantiate Plugin Class
new Nasim_Direct_Checkout_Extension();
