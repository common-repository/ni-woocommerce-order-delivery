<?php
/*
Plugin Name: Ni WooCommerce Order Delivery
Description: Enable customers to choose their preferred delivery dates directly at checkout.
Author: anzia
Version: 1.2.9
Author URI: http://naziinfotech.com
Plugin URI:  https://wordpress.org/plugins/ni-woocommerce-order-delivery/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/agpl-3.0.html
Requires at least: 4.7
Tested up to: 6.5.3
WC requires at least: 3.0.0
WC tested up to: 8.8.3 
Last Updated Date: 11-May-2024
Requires PHP: 7.0
*/

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Ni_Order_Delivery')) {
    class Ni_Order_Delivery
    {
        public function __construct()
        {
            include_once(plugin_dir_path(__FILE__) . 'include/ni-order-delivery-init.php');
            $Order_delivery_init = new Ni_Order_Delivery_Init();
        }

        public static function niwoosc_activation_redirect($plugin)
        {
            if ($plugin == plugin_basename(__FILE__)) {
                exit(wp_redirect(admin_url('admin.php?page=ni-order-delivery-settings')));
            }
        }
    }

    // Instantiate the plugin class
    $obj = new Ni_Order_Delivery();

    // Redirect after activation
    register_activation_hook(__FILE__, array('Ni_Order_Delivery', 'niwoosc_activation_redirect'));
}
?>