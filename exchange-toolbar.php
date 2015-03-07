<?php
/**
 * Plugin Name: Exchange Toolbar
 * Plugin URI: https://github.com/NikV/exchange-toolbar/
 * Description: A simple toolbar plugin for the Exchange eCommerce plugin built by iThemes
 * Author: Nikhil Vimal
 * Author URI: http://nik.techvoltz.com
 * Version: 1.0
 * Plugin URI: n/a
 * License: GNU GPLv2+
 */

class Exchange_Admin_Bar {

	//The Exchange Toolbar Instance
	public static function init() {
		static $instance = false;
		if ( ! $instance ) {
			$instance = new Exchange_Admin_Bar();
		}
		return $instance;
	}

	public function __construct() {
		add_action('admin_bar_menu', array( $this, 'admin_bar_nodes'), 999);
	}

	/**
	 * The function that creates the menus (nodes) for the admin bar
	 *
	 * @param $wp_admin_bar The WordPress admin bar
	 */
	public function admin_bar_nodes( $wp_admin_bar ) {

			if ( ! is_admin() ) {

				$wp_admin_bar->add_node( array(
						'id'    => 'it_exchange_toolbar',
						'title' => 'Exchange',
					)
				);

				if( is_singular('it_exchange_prod')) {

					//# Of Sales
					$wp_admin_bar->add_node( array(
							'id'    => 'exchange_sales',
							'title' => count( it_exchange_get_transactions_for_product( get_the_ID(), 'ids' ) ) . __( " Sales", 'it_exchange' ),
						)
					);

						if( ! get_post_meta( get_the_ID(), '_it-exchange-product-inventory', true ) == "" ) {
						//Inventory
						$wp_admin_bar->add_node( array(
								'id'    => 'exchange_inventory',
								'title' => get_post_meta( get_the_ID(), '_it-exchange-product-inventory', true ) . __( " In Inventory", 'it_exchange' ),
							)
						);
					}
				}

				$wp_admin_bar->add_node( array(
						'id'     => 'exchange_all_products',
						'title'  => 'All Products',
						'parent' => 'it_exchange_toolbar',
						'href'   => admin_url( 'edit.php?post_type=it_exchange_prod' ),
					)
				);
				$wp_admin_bar->add_node( array(
						'id'     => 'exchange_payments',
						'title'  => 'Payments',
						'parent' => 'it_exchange_toolbar',
						'href'   => admin_url( 'edit.php?post_type=it_exchange_tran' ),
					)
				);

				$wp_admin_bar->add_node( array(
						'id'     => 'exchange_settings',
						'title'  => 'Settings',
						'parent' => 'it_exchange_toolbar',
						'href'   => admin_url( 'admin.php?page=it-exchange-settings' ),
					)
				);

				$wp_admin_bar->add_node( array(
						'id'     => 'exchange_help',
						'title'  => 'Help',
						'parent' => 'it_exchange_toolbar',
						'href'   => admin_url( 'admin.php?page=it-exchange-help' ),
					)
				);


		}
	}
}

/**
 * Load the class, and check if the main plugin file exists
 *
 * @since 1.0
 */
function load_Exchange_Admin_Bar() {

	if( !function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	//Check if the plugin's main file exists
	if( is_plugin_active( 'ithemes-exchange/init.php' ) ) {
		return Exchange_Admin_Bar::init();
	}

}
add_action('plugins_loaded', 'load_Exchange_Admin_Bar');
