<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://qoniq.it/airforma/
 * @since      1.0.0
 *
 * @package    Airforma_Woocommerceintegration
 * @subpackage Airforma_Woocommerceintegration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Airforma_Woocommerceintegration
 * @subpackage Airforma_Woocommerceintegration/includes
 * @author     Qoniq Team < info@qoniq.it>
 */
class Airforma_Woocommerceintegration_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'airforma-woocommerceintegration',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
