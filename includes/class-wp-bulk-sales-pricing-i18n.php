<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_Bulk_Sales_Pricing
 * @subpackage Wp_Bulk_Sales_Pricing/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Bulk_Sales_Pricing
 * @subpackage Wp_Bulk_Sales_Pricing/includes
 * @author     Christian Nwachukwu <nwachukwu16@gmail.com>
 */
class Wp_Bulk_Sales_Pricing_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-bulk-sales-pricing',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
