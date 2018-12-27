<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_Bulk_Sales_Pricing
 * @subpackage Wp_Bulk_Sales_Pricing/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Bulk_Sales_Pricing
 * @subpackage Wp_Bulk_Sales_Pricing/admin
 * @author     Christian Nwachukwu <nwachukwu16@gmail.com>
 */
class Wp_Bulk_Sales_Pricing_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bulk_Sales_Pricing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bulk_Sales_Pricing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-bulk-sales-pricing-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name."-chosen-css", "https://harvesthq.github.io/chosen/chosen.css", array(), '', 'all' );
 
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bulk_Sales_Pricing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bulk_Sales_Pricing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name."-chosen","//harvesthq.github.io/chosen/chosen.jquery.js", '', true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-bulk-sales-pricing-admin.js', array( 'jquery' ), $this->version, true );
  	}


	public function bsp_admin_menu(){
			
		add_menu_page(
						__('Sales Pricing', 'bsp'),#'Page title'
						__('Sales Pricing', 'bsp'),#'Top-level menu title'
						'manage_options', #'manage_options'
						'sales-pricing-settings',#'Slug'
						array($this, 'bsp_sales_pricing_page'), #call back function
						'dashicons-tickets-alt' #Icons
					 );
					 
		 add_submenu_page( 
							 'sales-pricing-settings',
							 __('Sales Pricing', 'bsp'),
							 __('Sales Pricing', 'bsp'),
							 'manage_options', 
							 'sales-pricing-settings', 
							 array($this, 'bsp_sales_pricing_page')
						 );

		add_submenu_page( 
						'sales-pricing-settings',
						__('Delete Sales Pricing', 'bsp'),
						__('Delete Sales Pricing', 'bsp'),
						'manage_options', 
						'delete-sales-pricing-settings', 
						array($this, 'bsp_delete_sales_pricing_page')
					);


	}


	public function bsp_sales_pricing_page(){
		 include_once('partials/wp-bulk-sales-pricing-admin-display.php');
	}

	public function bsp_delete_sales_pricing_page(){
		 include_once('partials/wp-delete-bulk-sales-pricing-display.php');
	}

}
