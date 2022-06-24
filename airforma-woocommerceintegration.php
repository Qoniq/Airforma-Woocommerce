<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://qoniq.it/airforma/
 * @since             1.0.0
 * @package           Airforma_Woocommerceintegration
 *
 * @wordpress-plugin
 * Plugin Name:       Airforma-WPintegration
 * Plugin URI:        https://qoniq.it/airforma/
 * Requires at least  5.9
 * Description:       Integration developed by Alessandro Di Lillo for Qoniq
 * Version:           1.0.0
 * Author:            Qoniq - Edupuntozero srl
 * Author URI:        https://qoniq.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       airforma-woocommerceintegration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AIRFORMA_WOOCOMMERCEINTEGRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-airforma-woocommerceintegration-activator.php
 */
function activate_airforma_woocommerceintegration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-airforma-woocommerceintegration-activator.php';
	Airforma_Woocommerceintegration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-airforma-woocommerceintegration-deactivator.php
 */
function deactivate_airforma_woocommerceintegration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-airforma-woocommerceintegration-deactivator.php';
	Airforma_Woocommerceintegration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_airforma_woocommerceintegration' );
register_deactivation_hook( __FILE__, 'deactivate_airforma_woocommerceintegration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-airforma-woocommerceintegration.php';


if ( ! class_exists( 'WC_airformaWoocommerceIntegration' ) ) :
	class WC_airformaWoocommerceIntegration {
	  /**
	  * Construct the plugin.
	  */
	  public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	  }
	  /**
	  * Initialize the plugin.
	  */
	  public function init() {
		// Checks if WooCommerce is installed.
		if ( class_exists( 'WC_Integration' ) ) {
		  // Include our integration class.
		  include_once 'class-airformaWoocommerceIntegration.php';
		  // Register the integration.
		  add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
		}
	  }
	  /**
	   * Add a new integration to WooCommerce.
	   */
	  public function add_integration( $integrations ) {
		$integrations[] = 'WC_My_plugin_Integration';
		return $integrations;
	  }
	}
	$WC_airformaWoocommerceIntegration = new WC_airformaWoocommerceIntegration( __FILE__ );
	endif;

	if (!function_exists('write_log')) {

		function write_log($log) {
			if (true === WP_DEBUG) {
				if (is_array($log) || is_object($log)) {
					error_log(print_r($log, true));
				} else {
					error_log($log);
				}
			}
		}
	
	}






	//AGGIUNGI DATI A BILLING
	add_filter( 'woocommerce_checkout_fields' , 'sandro_override_checkout_fields' );	
	function sandro_override_checkout_fields( $fields ) {
		$domain = 'woocommerce';
		$checkout = WC()->checkout;

			$fields['billing']['billing_applicant'] = array(
				'type'          => 'select',
				'label'         => __('Intestatario fattura', $domain ),
				'options' 		=> array('Privato'=> 'Privato','Azienda'=> 'Azienda' ),
				'placeholder'   => __('Ordine profossionale del partecipante', $domain ),
				'class'         => array('form-row-first flexDiv'),
				'required'      => true, // or false
			);
			$fields['billing']['billing_applicant']['priority'] = 7;
			$fields['billing']['billing_first_name'] = array(
			'label'     => __('Nome e Cognome o Ragione Sociale', 'woocommerce'),
			'placeholder'   => _x('E.g. Azienda srl/ Piero Rossi', 'placeholder', 'woocommerce'),
			'required'  => true,
			'class'     => array('form-row-wide'),
			'clear'     => true,
				);

			$fields['billing']['billing_first_name']['priority'] = 10;
			unset($fields['billing']['billing_last_name']);
			unset($fields['billing']['billing_company']);

			$fields['billing']['billing_country']['priority'] = 20;
			$fields['billing']['billing_country']['label'] = __('Stato', 'woocommerce');

			$fields['billing']['billing_state']['priority'] = 50;
			$fields['billing']['billing_state']['label'] = __('Provincia', 'woocommerce');

			$fields['billing']['billing_address_1']['priority'] = 30;
			$fields['billing']['billing_address_1']['label'] = __('Via e numero', 'woocommerce');
			$fields['billing']['billing_address_1']['placeholder'] = 'Via/Piazza e Numero civico';

			unset($fields['billing']['billing_address_2']);
			unset($fields['billing']['billing_phone']);
			$fields['billing']['billing_city']['priority'] = 40;
			$fields['billing']['billing_city']['label'] = __('Città', 'woocommerce');
			$fields['billing']['billing_city']['placeholder'] = 'E.g. Bari';

			$fields['billing']['billing_postcode']['priority'] = 60;
			$fields['billing']['billing_postcode']['label'] = __('CAP', 'woocommerce');
			$fields['billing']['billing_postcode']['placeholder'] = '70124.';
			$fields['billing']['billing_postcode']['required'] = false;

			
			$fields['billing']['billing_email']['priority'] = 70;
			$fields['billing']['billing_email']['label'] = __('Indirizzo Email', 'woocommerce');
			$fields['billing']['billing_email']['placeholder'] = 'E.g. rossi@exampl.it';
			
			
			
			$fields['billing']['billing_CF_fatturazione'] = array(
				'label'     => __('Codice fiscale', 'woocommerce'),
				'placeholder'   => _x('E.g. FRALRT07A56G273T', 'placeholder', 'woocommerce'),
				'required'  => false,
				'class'     => array('form-row-wide hiddenCustomFied'),
				'clear'     => true
			);
			$fields['billing']['billing_CF_fatturazione']['priority'] = 80;
			$fields['billing']['billing_VAT_fatturazione'] = array(
				'type' 	=> 	"number",
				'label'     => __('Partita IVA (VAT number)', 'woocommerce'),
			'placeholder'   => _x('Scrivere il numero di partita IVA', 'placeholder', 'woocommerce'),
			'required'  => false,
			'class'     => array('form-row-wide'),
			'clear'     => true
				);
			$fields['billing']['billing_VAT_fatturazione']['priority'] = 90;

			$fields['billing']['billing_PEC_fatturazione'] = array(
				'label'     => __('Email PEC', 'woocommerce'),
			'placeholder'   => _x('Per la fattura elettronica', 'placeholder', 'woocommerce'),
			'required'  => false,
			'class'     => array('form-row-wide'),
			'clear'     => true
				);
			$fields['billing']['billing_PEC_fatturazione']['priority'] = 100;

			$fields['billing']['billing_COD_Dest_fatturazione'] = array(
				'label'     => __('Codice Destinatario', 'woocommerce'),
			'placeholder'   => _x('Per la fattura elettronica', 'placeholder', 'woocommerce'),
			'required'  => false,
			'class'     => array('form-row-wide'),
			'clear'     => true
				);
			$fields['billing']['billing_COD_Dest_fatturazione']['priority'] = 110;
		
				return $fields;
		}

  
  
   add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );
   
   function change_default_checkout_country() {
	 return 'IT'; // country code
   }
   add_filter( 'default_checkout_billing_applicant', 'change_default_checkout_applicant' );
   
   function change_default_checkout_applicant() {
	 return 'Azienda'; // country code
   }
   add_filter( 'woocommerce_default_address_fields', 'addr_custom_override_default_locale_fields' );
   function addr_custom_override_default_locale_fields( $fields ) {
		$fields['applicant']['priority'] = 7;
		$fields['country']['priority'] = 20;
		$fields['address_1']['priority'] = 30;
		$fields['city']['priority'] = 40;
		$fields['address_1']['label'] = __('Via e numero', 'woocommerce');
		$fields['address_1']['placeholder'] = 'Via/Piazza e Numero civico';
		$fields['postcode']['label'] = __('CAP', 'woocommerce');
		$fields['postcode']['placeholder'] = '70124.';
		$fields['postcode']['priority'] = 60;
		$fields['city']['label'] = __('Città', 'woocommerce');
		$fields['city']['placeholder'] = 'E.g. Bari';
		$fields['state']['priority'] = 50;
		$fields['state']['label'] = __('Provincia', 'woocommerce');
		$fields['CF_fatturazione']['priority'] = 80;
		$fields['VAT_fatturazione']['priority'] = 90;
		$fields['PEC_fatturazione']['priority'] = 100;
		$fields['COD_Dest_fatturazione']['priority'] = 110;
	


	   return $fields;
   }
   add_filter( 'woocommerce_default_billing_fields', 'bill_custom_override_default_locale_fields' );
   function bill_custom_override_default_locale_fields( $fields ) {
	
		$fields['state']['label'] = __('Provincia', 'woocommerce');
	


	   return $fields;
   }
   /**
	* Process the checkout
	*/
	add_action( 'woocommerce_checkout_process', 'custom_checkout_field_process_fatturazione' );
	function custom_checkout_field_process_fatturazione() {
		
		if ( $_POST['billing_applicant'] === 'Privato' && isset($_POST['billing_CF_fatturazione']) && empty($_POST['billing_CF_fatturazione']) )
			wc_add_notice( __( 'Il profilo scelto è:'." ".$_POST['billing_applicant']." ".'Per questo profilo è necessario il <strong>codice</strong> fiscale'), 'error' );
		if ( $_POST['billing_applicant'] === 'Privato' && isset($_POST['billing_CF_fatturazione']) && !empty($_POST['billing_CF_fatturazione']) && (strlen($_POST['billing_CF_fatturazione']) < 16 ) )
			wc_add_notice( __( 'Inserire un <strong>Codice</strong> fiscale valido'), 'error' );	
		if ( ($_POST['billing_applicant'] === 'Azienda')  && isset($_POST['billing_VAT_fatturazione']) && empty($_POST['billing_VAT_fatturazione']) )
			wc_add_notice( __( 'Inserire <strong>Partita Iva</strong> in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if(($_POST['billing_applicant'] === 'Azienda')  && isset($_POST['billing_VAT_fatturazione']) && !empty($_POST['billing_VAT_fatturazione']) && !(strlen($_POST['billing_VAT_fatturazione']) >= 11))
			wc_add_notice( __( 'inserire una <strong>Partita Iva</strong> valida' ), 'error' );
		if ( ($_POST['billing_applicant'] === 'Azienda') && isset($_POST['billing_PEC_fatturazione']) && (empty($_POST['billing_PEC_fatturazione']) && empty($_POST['billing_COD_Dest_fatturazione']) ) )
			wc_add_notice( __( 'Inserire almeno uno tra <strong>PEC</strong> o <strong>SDI</strong>  in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if ( ($_POST['billing_applicant'] === 'Azienda') && isset($_POST['billing_COD_Dest_fatturazione']) && (empty($_POST['billing_PEC_fatturazione']) && empty($_POST['billing_COD_Dest_fatturazione']) ) )
			wc_add_notice( __( 'Inserire almeno uno tra <strong>PEC</strong> o <strong>SDI</strong> in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if (isset($_POST['billing_postcode']) && empty($_POST['billing_postcode'] ) )
			wc_add_notice( __( 'Inserire <strong>CAP</strong> in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if (isset($_POST['billing_email']) && empty($_POST['billing_email'] ) )
			wc_add_notice( __( 'Inserire <strong>Email</strong> in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if (isset($_POST['billing_city']) && empty($_POST['billing_city'] ) )
			wc_add_notice( __( 'Inserire <strong>Città</strong> in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if (isset($_POST['billing_state']) && empty($_POST['billing_state'] ) )
			wc_add_notice( __( 'Inserire <strong>Provincia</strong> in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if (isset($_POST['billing_country']) && empty($_POST['billing_country'] ) )
			wc_add_notice( __( 'Inserire <strong>Stato</strong>  in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if (isset($_POST['billing_address_1']) && empty($_POST['billing_address_1'] ) )
			wc_add_notice( __( 'Inserire <strong>Indirizzo</strong> in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'] ) )
			wc_add_notice( __( 'Inserire <strong>Nome</strong> e <strong>cognome</strong> in <strong class="strong2">Dati fatturazione </strong>' ), 'error' );
		
		
		
	}
   
   
   /**
	* Update the order meta with field value
	*/
   add_action( 'woocommerce_checkout_update_order_meta', 'sandro_checkout_field_update_order_meta' );
   
   function sandro_checkout_field_update_order_meta( $order_id ) {
	   if ( ! empty( $_POST['billing_CF_fatturazione'] ) ) {
		   update_post_meta( $order_id, 'billing_CF_fatturazione', sanitize_text_field( $_POST['billing_CF_fatturazione'] ) );
	   }
	   if ( ! empty( $_POST['billing_VAT_fatturazione'] ) ) {
		   update_post_meta( $order_id, 'billing_VAT_fatturazione', sanitize_text_field( $_POST['billing_VAT_fatturazione'] ) );
	   }
	   if ( ! empty( $_POST['billing_PEC_fatturazione'] ) ) {
		   update_post_meta( $order_id, 'billing_PEC_fatturazione', sanitize_text_field( $_POST['billing_PEC_fatturazione'] ) );
	   }
	   if ( ! empty( $_POST['billing_COD_Dest_fatturazione'] ) ) {
		   update_post_meta( $order_id, 'billing_COD_Dest_fatturazione', sanitize_text_field( $_POST['billing_COD_Dest_fatturazione'] ) );
	   }
   }
   
   
   /**
	* Display field value on the order edit page
	*/
   add_action( 'woocommerce_admin_order_data_after_billing_address', 'sandro_checkout_field_display_admin_order_meta', 10, 1 );
   
   function sandro_checkout_field_display_admin_order_meta($order){
	if ( ! empty( get_post_meta( $order->get_id(), '_billing_CF_fatturazione', true ) ) ) {
	 echo '<p><strong>'.__('CF:').'</strong><br>'." ". get_post_meta( $order->get_id(), '_billing_CF_fatturazione', true ) . '</p>';
	}
	if ( ! empty( get_post_meta( $order->get_id(), '_billing_VAT_fatturazione', true ) ) ) {
	 echo '<p><strong>'.__('VAT:').'</strong><br>'." ". get_post_meta( $order->get_id(), '_billing_VAT_fatturazione', true ) . '</p>';
	}
	if ( ! empty( get_post_meta( $order->get_id(), '_billing_PEC_fatturazione', true ) ) ) {
	 echo '<p><strong>'.__('PEC:').'</strong><br>'." ". get_post_meta( $order->get_id(), '_billing_PEC_fatturazione', true ) . '</p>';
	}
	if ( ! empty( get_post_meta( $order->get_id(), '_billing_COD_Dest_fatturazione', true ) ) ) {
	 echo '<p><strong>'.__('CODICE DESTINATARIO:').'</strong><br>'." ". get_post_meta( $order->get_id(), '_billing_COD_Dest_fatturazione', true ) . '</p>';
	}
	 // echo '<p>'.__('ciao:'). get_post_meta( $order->ID, 'billing_nuovo_campo', true ) . '</p>';
   }






	
	// AGGIUNGI CHECKOUT DATI PERSONALI 
	
	
	// Set the plugin slug
	define( 'MY_PLUGIN_SLUG', 'AirForma' );
	
	// Setting action for plugin
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'WC_my_custom_plugin_action_links' );
	
	function WC_my_custom_plugin_action_links( $links ) {
	
		$links[] = '<a href="'. menu_page_url( MY_PLUGIN_SLUG, false ).'">Settings</a>';
		return $links;
	  }

	//Change the 'Billing details' checkout label to 'Dettagli di fatturazione'
	add_filter( 'gettext', 'wc_billing_field_changeLabel', 20, 3 );

		function wc_billing_field_changeLabel( $translated_text, $text, $domain ) {
		switch ( $translated_text ) {
		case 'Billing details' :
		$translated_text = __( 'Dettagli di fatturazione', 'woocommerce' );
		break;
		}
		return $translated_text;
		}

	add_action( 'woocommerce_checkout_before_customer_details', 'custom_plugin_checkout_fields_before_billing_details', 20 );
		function custom_plugin_checkout_fields_before_billing_details(){
			$domain = 'woocommerce';
			$checkout = WC()->checkout;
			$array_json_option = plugin_dir_path( __FILE__ ).'array.json';
			echo '<div id="my_custom_checkout_field">';

			echo '<h3>' . __('Dati partecipante') . '</h3>';
		
			woocommerce_form_field( '_name_partecipante', array(
				'type'          => 'text',
				'label'         => __('Nome del partecipante', $domain ),
				'placeholder'   => __('Nome del partecipante', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_name' ) );
			woocommerce_form_field( '_surname_partecipante', array(
				'type'          => 'text',
				'label'         => __('Cognome del partecipante', $domain ),
				'placeholder'   => __('Cognome del partecipante', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_surname' ) );
			woocommerce_form_field( '_email_partecipante', array(
				'type'          => 'email',
				'label'         => __('Email del partecipante', $domain ),
				'placeholder'   => __('E.g. example@example.com', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_email_partecipante' ) );
			woocommerce_form_field( '_birthPlace_partecipante', array(
				'type'          => 'text',
				'label'         => __('Luogo di nascita del partecipante', $domain ),
				'placeholder'   => __('Luogo di nascita del partecipante', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_birthPlace_partecipante' ) );
			woocommerce_form_field( '_birthDay_partecipante', array(
				'type'          => 'date',
				'label'         => __('Data di nascita del partecipante', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_birthDay_partecipante' ) );
			woocommerce_form_field( '_CF_partecipante', array(
				'type'          => 'text',
				'label'         => __('Codice fiscale del partecipante', $domain ),
				'placeholder'   => __('Codice fiscale del partecipante', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_CF_partecipante' ) );
			woocommerce_form_field( '_city_of_residence_partecipante', array(
				'type'          => 'text',
				'label'         => __('Città di residenza del partecipante', $domain ),
				'placeholder'   => __('Citta di residenza del partecipante', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_city_of_residence_partecipante' ) );
			woocommerce_form_field( '_phone_partecipante', array(
				'type'          => 'tel',
				'label'         => __('Numero di telefono del partecipante', $domain ),
				'placeholder'   => __('Numero di telefono del partecipante', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_phone_partecipante' ) );
			woocommerce_form_field( '_profession_partecipante', array(
				'type'          => 'text',
				'label'         => __('Professione del partecipante', $domain ),
				'placeholder'   => __('E.g. Architetto', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_profession_partecipante' ) );
			woocommerce_form_field( '_professional_order_partecipante', array(
				'type'          => 'select',
				'label'         => __('Ordine professionale del partecipante', $domain ),
				'options' 		=> json_decode(file_get_contents($array_json_option)),
				'placeholder'   => __('Ordine profossionale del partecipante', $domain ),
				'class'         => array('my-field-class form-row-wide'),
				'required'      => true, // or false
			), $checkout->get_value( '_professional_order_partecipante' ) );
					
			woocommerce_form_field( '_number_subs_per_order_partecipante', array(
				'type'          => 'text',
				'label'         => __('Numero di iscrizioni all\'ordine', $domain ),
				'placeholder'   => __('Numero iscritti per ordine professionale', $domain ),
				'class'         => array('my-field-class form-row-wide hiddenCustomFied'),
				'required'      => false, // or false
			), $checkout->get_value( '_number_subs_per_order_partecipante' ) );

			echo '</div>';
			?>
			<script>
				let selectInput = document.getElementById('_professional_order_partecipante');
				let hiddenInput = document.getElementById('_number_subs_per_order_partecipante_field');
				let hiddenInputInput = document.getElementById('_number_subs_per_order_partecipante');
				selectInput.addEventListener('change', () => {
					if( selectInput.value !== 'Nessuno/Altro') {
						hiddenInput.classList.remove('hiddenCustomFied');
						hiddenInputInput.required = true;

					} else {
						if(!hiddenInput.classList.contains('hiddenCustomFied')){
						hiddenInput.classList.add('hiddenCustomFied')
						hiddenInputInput.required = false;
						console.log(hiddenInputInput)
						}
					}
				});
		
			
			</script>
			<?php
		}

	
	// Custom checkout fields validation
	add_action( 'woocommerce_checkout_process', 'CF_custom_checkout_field_process' );
		function CF_custom_checkout_field_process() {
			if ( isset($_POST['_name_partecipante']) && empty($_POST['_name_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Nome</strong> del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_surname_partecipante']) && empty($_POST['_surname_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Cognome</strong> del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_email_partecipante']) && empty($_POST['_email_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Email</strong> del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_birthPlace_partecipante']) && empty($_POST['_birthPlace_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Luogo di nascita</strong> del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_birthDay_partecipante']) && empty($_POST['_birthDay_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Data di nascita</strong> del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_CF_partecipante']) && empty($_POST['_CF_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Codice fiscale</strong> del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_city_of_residence_partecipante']) && empty($_POST['_city_of_residence_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Città di residenza</strong>  del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_phone_partecipante']) && empty($_POST['_phone_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Numero di telefono</strong>  del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_profession_partecipante']) && empty($_POST['_profession_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Professione</strong>  del partecipante in <strong class="strong2"> Dati partecipante</strong>.' ), 'error' );
			if ( isset($_POST['_professional_order_partecipante']) && empty($_POST['_professional_order_partecipante']) )
				wc_add_notice( __( 'Inserire <strong>Ordine professionale</strong>  di residenza del partecipante nelle informazioni contatto.' ), 'error' );
			
		}

	// Save custom checkout fields the data to the order
	add_action( 'woocommerce_checkout_create_order', 'CF_custom_checkout_field_update_meta', 10, 2 );
		function CF_custom_checkout_field_update_meta( $order, $data ){
			if( isset($_POST['_name_partecipante']) && ! empty($_POST['_name_partecipante']) )
				$order->update_meta_data( '_name_partecipante', sanitize_text_field( $_POST['_name_partecipante'] ) );
			if( isset($_POST['_surname_partecipante']) && ! empty($_POST['_surname_partecipante']) )
				$order->update_meta_data( '_surname_partecipante', sanitize_text_field( $_POST['_surname_partecipante'] ) );
			if( isset($_POST['_email_partecipante']) && ! empty($_POST['_email_partecipante']) )
				$order->update_meta_data( '_email_partecipante', sanitize_text_field( $_POST['_email_partecipante'] ) );
			if( isset($_POST['_birthPlace_partecipante']) && ! empty($_POST['_birthPlace_partecipante']) )
				$order->update_meta_data( '_birthPlace_partecipante', sanitize_text_field( $_POST['_birthPlace_partecipante'] ) );
			if( isset($_POST['_birthDay_partecipante']) && ! empty($_POST['_birthDay_partecipante']) )
				$order->update_meta_data( '_birthDay_partecipante', sanitize_text_field( $_POST['_birthDay_partecipante'] ) );
			if( isset($_POST['_CF_partecipante']) && ! empty($_POST['_CF_partecipante']) )
				$order->update_meta_data( '_CF_partecipante', sanitize_text_field( $_POST['_CF_partecipante'] ) );
			if( isset($_POST['_city_of_residence_partecipante']) && ! empty($_POST['_city_of_residence_partecipante']) )
				$order->update_meta_data( '_city_of_residence_partecipante', sanitize_text_field( $_POST['_city_of_residence_partecipante'] ) );
			if( isset($_POST['_phone_partecipante']) && ! empty($_POST['_phone_partecipante']) )
				$order->update_meta_data( '_phone_partecipante', sanitize_text_field( $_POST['_phone_partecipante'] ) );
			if( isset($_POST['_profession_partecipante']) && ! empty($_POST['_profession_partecipante']) )
				$order->update_meta_data( '_profession_partecipante', sanitize_text_field( $_POST['_profession_partecipante'] ) );
			if( isset($_POST['_professional_order_partecipante']) && ! empty($_POST['_professional_order_partecipante']) )
				$order->update_meta_data( '_professional_order_partecipante', sanitize_text_field( $_POST['_professional_order_partecipante'] ) );
			if( isset($_POST['_number_subs_per_order_partecipante']) && ! empty($_POST['_number_subs_per_order_partecipante']) )
				$order->update_meta_data( '_number_subs_per_order_partecipante', sanitize_text_field( $_POST['_number_subs_per_order_partecipante'] ) );
		}

	/* Display field value on the order edit page
	*/
	add_action( 'woocommerce_admin_order_data_after_billing_address', 'sandro2_checkout_field_display_admin_order_meta', 10, 1 );

		function sandro2_checkout_field_display_admin_order_meta($order){
		$emailPartecipante = get_post_meta( $order->get_id(), '_email_partecipante', true );
		$phonePartecipante = get_post_meta( $order->get_id(), '_phone_partecipante', true );
			
		echo '<h3><strong><font color="black">'.__('Infomazioni cliente').':</font></strong></h3> '; 
		echo '<p><strong>'.__('Nome del partecipante:').'</strong><br>'." ". get_post_meta( $order->get_id(), '_name_partecipante', true ) . '</p>';
		echo '<p><strong>'.__('Cognome del partecipante:').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_surname_partecipante', true ) . '</p>';
		echo '<p><strong>'.__('Email del partecipante:').'</strong> <br>'." ".'<a href='.'"mailto:'."{$emailPartecipante}".'"'.'>'. get_post_meta( $order->get_id(), '_email_partecipante', true ) . '</a></p>';
		echo '<p><strong>'.__('Luogo di nascita del partecipante:').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_birthPlace_partecipante', true ) . '</p>';
		echo '<p><strong>'.__('Data di nascita del partecipante:').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_birthDay_partecipante', true ) . '</p>';
		echo '<p><strong>'.__('Codice fiscale di nascita del partecipante:').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_CF_partecipante', true ) . '</p>';
		echo '<p><strong>'.__('Città di residenza di nascita del partecipante:').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_city_of_residence_partecipante', true ) . '</p>';
		echo '<p><strong>'.__('Numero di telefono del partecipante:').'</strong> <br>'." ".'<a href='.'"tel:'."{$phonePartecipante}".'"'.'>'.  get_post_meta( $order->get_id(), '_phone_partecipante', true ) . '</a></p>';
		echo '<p><strong>'.__('Professione del partecipante:').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_profession_partecipante', true ) . '</p>';
		echo '<p><strong>'.__('Ordine professionale del partecipante:').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_professional_order_partecipante', true ) . '</p>';
		echo '<p><strong>'.__('Message:').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_meta_message_order_test', true ) . '</p>';
		if( !empty(get_post_meta( $order->get_id(), '_number_subs_per_order_partecipante', true )) )
		echo '<p><strong>'.__('Numero di iscritti per ordine professionale').'</strong> <br>'." ". get_post_meta( $order->get_id(), '_number_subs_per_order_partecipante', true ) . '</p>';
		};


		
		function add_menu()
		{
			add_menu_page(
				'AirForma Settings',// page title
				  'AirForma',// menu title
				  'manage_options',// capability
				  'AirForma',// menu slug
				  'CF_render_settings_page',
				  'dashicons-admin-plugins',
					3
			);
			add_submenu_page(
				'AirForma',
				'Modifica checkout', //page title
				'Modifica checkout', //menu title
				'manage_options', //capability,
				'AirformaAddOrder',//menu slug
				'CF_render_home_page' //callback function
			);
	
		}
		add_action('admin_menu', 'add_menu');


		function CF_render_home_page()
		{
			// $option = esc_attr( stripslashes( get_option( $this->option_name ) ) );
			$redirect = urlencode( remove_query_arg( 'msg', $_SERVER['REQUEST_URI'] ) );
			$redirect = urlencode( $_SERVER['REQUEST_URI'] );
	
			?>
			<div class="containerCustomF">
				<div class="containerCustomF50">	
						<h1><?php echo $GLOBALS['title']; ?></h1>
						<?php
				if(isset($_GET['msg'])) {
					$msg = $_GET['msg'] ;
					if($msg === 'updated'){
						echo "<div class='notice  notice-success'><p>Ordine professionale inserito con successo<p></div>";
					} 
					elseif($msg === 'deleted'){
						echo "<div class='notice  notice-success'><p>C'è stato un problema con l'inserimento dell'ordine professionale, riprova! <p></div>";
					} 
					elseif($msg === 'removed'){
						echo "<div class='notice  notice-success'><p>Ordine professionale eliminato con successo <p></div>";
					} 
					else {
						echo "<div class='notice  notice-error'><p>Problema con l'eliminazione, riprova <p></div>";
					}

				}
				?>
					<div class="rowCustomF ">
							<h2>Aggiungi ordine professionale</h2>
							<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
								<input type="hidden" name="action" value="add_order_admin_post">
								<input type="hidden" name="_wp_http_referer" value="<?php echo $redirect; ?>">
								<?php wp_nonce_field( 'add_order_admin_post', 'add_order_prof_nonce', FALSE ); ?>
								<div class="flexDiv cstmPLA">
									<input type="text" name="add_order_prof" id="add_order_prof" value="" placeholder="E.g. Architetto">
									<?php submit_button( __('Aggiungi', 'woocommerce'), 'primary', '', true ); ?>
								</div>
							</form>
					</div>
					<div class="rowCustomF">
							<h2>Elenco ordini professionali</h2>
							<?php 
							$array_json_option = plugin_dir_path( __FILE__ ).'array.json';
								$inp = file_get_contents($array_json_option);
								$tempArray = json_decode($inp, true);
								foreach($tempArray as $order) {  
								?>	
							<div class=" flexDiv100 containerOrderCusAdmin">  	
								<?php 
								if($order !== 'Nessuno/Altro') {
								echo '<div class=""><h2 class="strong2containerOrderCusAdmin">'."   ".$order." "." ".'</h2></div>';
								
								?>
								<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
									<input type="hidden" name="action" value="remove_order_admin_post">
									<input type="hidden" name="_wp_http_referer" value="<?php echo $redirect; ?>">
									<?php wp_nonce_field( 'remove_order_admin_post', 'remove_order_admin_nonce', FALSE ); ?>
									<input type="hidden" name="remove_order_prof" id="remove_order_prof" value="<?php echo $order; ?>" >
									<?php submit_button( __( 'Elimina', 'woocommerce' ), 'cf-button-cusdelete', 'cf-button-cusdelete', true ); ?>
								</form>
							<?php  
							};
							?>
					
							</div>
						<hr>
						<?php 	
							};
						?>
					</div>
				</div>
			</div>
			<?php
		}

		add_action( 'admin_post_add_order_admin_post', 'add_order_admin_post_function' );
	
		function add_order_admin_post_function()
		{
			if ( ! wp_verify_nonce( $_POST[ 'add_order_prof_nonce' ], 'add_order_admin_post' ) )
				die( 'Invalid nonce.' . var_export( $_POST, true ) );
			
	
			if ( isset ( $_POST[ 'add_order_prof' ] ) && !empty($_POST[ 'add_order_prof' ] ))
			{
				update_option( 'add_order_prof', $_POST[ 'add_order_prof' ] );
				$msg = 'updated';
				$data = $_POST['add_order_prof'];
				$array_json_option = plugin_dir_path( __FILE__ ).'array.json';
				$inp = file_get_contents($array_json_option);
				$tempArray = json_decode($inp, true);
				$tempArray[$data] = $data;
				$jsonData = json_encode($tempArray);
				file_put_contents(plugin_dir_path( __FILE__ ).'array.json', $jsonData);
			}
			else
			{
				delete_option( 'add_order_prof' );
				$msg = 'deleted';
				
			}
	
			if ( ! isset ( $_POST['_wp_http_referer'] ) )
				die( 'Missing target.' );
	
			$url = add_query_arg( 'msg', $msg, urldecode( $_POST['_wp_http_referer'] ) );
	
			wp_safe_redirect( $url );
			exit;
		}
		add_action( 'admin_post_remove_order_admin_post', 'remove_order_admin_post_function' );
		function remove_order_admin_post_function()
		{
			
			if ( ! wp_verify_nonce( $_POST[ 'remove_order_admin_nonce' ], 'remove_order_admin_post' ) )
				die( 'Invalid nonce.' . var_export( $_POST, true ) );
	
			if(isset ( $_POST[ 'remove_order_prof' ] ))
			{
				$data = $_POST['remove_order_prof'];
				$array_json_option = plugin_dir_path( __FILE__ ).'array.json';
				$inp = file_get_contents($array_json_option);
				$tempArray = json_decode($inp, true);
				unset($tempArray[$data]);
				$jsonData = json_encode($tempArray);
				file_put_contents(plugin_dir_path( __FILE__ ).'array.json', $jsonData);
				$msg = 'removed';
			}
			else
			{
				delete_option( 'remove_order_prof' );
				$msg = 'removeproblem';
				
			}
	
			if ( ! isset ( $_POST['_wp_http_referer'] ) )
				die( 'Missing target.' );
	
			$url = add_query_arg( 'msg', $msg, urldecode( $_POST['_wp_http_referer'] ) );
	
			wp_safe_redirect( $url );
			exit;
		}
		



		function CF_render_settings_page()
		{
			$redirect = urlencode( remove_query_arg( 'msg', $_SERVER['REQUEST_URI'] ) );
			$redirect = urlencode( $_SERVER['REQUEST_URI'] );
		
			?>
			<div class="containerCustomF ">
				<h1><?php echo $GLOBALS['title']; ?></h1>
				<div class="containerCustomF50">
				<?php
				if(isset($_GET['msg'])) {
					$msg = $_GET['msg'] ;
					write_log(esc_attr(  get_option( 'add_URLCust' )  ) );
					if($msg === 'test superato'){
						echo "<div class='notice  notice-success'><p>Test superato, puoi procedere con il salvataggio delle tue credenziali<p></div>";
					} 
					elseif($msg === 'success') 
					{
						echo "<div class='notice  notice-success'><p> Complimenti! Hai aggiornato le tue credenziali con successo <p></div>";
					}
					elseif($msg === 'missUrl')  
					{
						echo "<div class='notice  notice-error'> Il campo <strong> URL </strong> è obbligatorio<p><p></div>";
					}
					elseif($msg === 'missToken')  
					{
						echo "<div class='notice  notice-error'><p> Il campo <strong> Token </strong> è obbligatorio <p></div>";
					}
					elseif($msg === 'missUrlToken')  
					{
						echo "<div class='notice  notice-error'><p> Compilare i campi URL e Token <p></div>";
					}
					elseif($msg === 'Url non valido')  
					{
						echo "<div class='notice  notice-error'><p> Inserire <strong> URL </strong> valido <p></div>";
					}
					elseif($msg === 'deleted')  
					{
						echo "<div class='notice  notice-error'><p> C'è stato un problema con il salvataggio dei tuoi dati <p></div>";
					}
					else {
						echo "<div class='notice  notice-error'><p>Non hai superato il test, sicuro che le credenziali di accesso siano corrette? <p></div>";
					}

				}
				?>
					<div class="rowCustomF bgCustmFMain">
						<h2>Inserire credenziali*</h2>
							<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST" class="tokurl">
								<input type="hidden" name="action" value="add_URLtokenCust_post">
								<input type="hidden" name="_wp_http_referer" value="<?php echo $redirect; ?>">
								<?php wp_nonce_field( 'add_URLtokenCust_post', 'add_URLtokenCust_nonce', FALSE ); ?>
								<div class="textCstmCF">
									<div class=" flexDiv100 cstmMtA">
									<span class="">Url:</span> <input type="text" name="add_URLCust" id="add_URLCust" value="<?php echo esc_attr(  get_option( 'add_URLCust' )  ) ?>" class="cstmMLA" placeholder="Inserisci qui l'URL">
									</div>
									<div class=" flexDiv100 cstmMtA">
									<span class="">Token:</span> <input type="text" name="add_tokenCust" id="add_tokenCust" value="<?php echo esc_attr( get_option( 'add_tokenCust' )) ?>" class="cstmMLA" placeholder="Inserisci qui il TOKEN">
									</div>
								</div>
								<div class="rowCustomF textCstmCF">
								<div class="flexDivCenter ">
									
							
								<?php if(isset($_GET['msg']) ) {
										$msg = $_GET['msg'] ;
										if($msg === 'test superato'){
											submit_button(  __( 'Salva', 'woocommerce' ), 'primary', 'primary', true ); 
											}
										}
									?>
								<?php if(isset($_GET['msg']) ) {
										$msg = $_GET['msg'];
										if($msg !== 'test superato'){
											submit_button( __( 'Testa', 'woocommerce' ), 'secondary', 'secondary', true );
											}
										}
										else {
											submit_button( __( 'Testa', 'woocommerce' ), 'secondary', 'secondary', true );
										}

										 ?>
								</div>
								</div>
								<small class="small-warn"> * L'url e il token puoi recuperarli dalla tua area personale AirForma. Ti basta accedere, andare su "Sistema" e poi su "Impostazioni". </small>  
							</form>
					</div>
				</div>
				
			
			</div>
			<?php
		}


		// encrypt decrypt

		function encrypt_decrypt($action, $string)
		{
			/* =================================================
			 * ENCRYPTION-DECRYPTION
			 * =================================================
			 * ENCRYPTION: encrypt_decrypt('encrypt', $string);
			 * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
			 */
			$output = false;
			$encrypt_method = "AES-256-CBC";
			$secret_key = 'CS-SERVICE-KEY';
			$secret_iv = 'CS-SERVICE-VALUE';
			// hash
			$key = hash('sha256', $secret_key);
			// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
			$iv = substr(hash('sha256', $secret_iv), 0, 16);
			if ($action == 'encrypt') {
				$output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
			} else {
				if ($action == 'decrypt') {
					$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
				}
			}
			return $output;
		}


		add_action( 'admin_post_add_URLtokenCust_post', 'add_URLtokenCust_post_function' );
		function add_URLtokenCust_post_function()
		{
			update_option( 'add_tokenCust', $_POST[ 'add_tokenCust' ] );
			update_option( 'add_URLCust', $_POST[ 'add_URLCust' ] );
			if ( ! wp_verify_nonce( $_POST[ 'add_URLtokenCust_nonce' ], 'add_URLtokenCust_post' ) )
				die( 'Invalid nonce.' . var_export( $_POST, true ) );
	
			if( isset ($_POST['primary']) && $_POST['primary'] === 'Salva') {
				
				if (isset ( $_POST[ 'add_URLCust' ] ) && !empty($_POST[ 'add_URLCust' ] ) && isset ( $_POST[ 'add_tokenCust' ] ) && !empty($_POST[ 'add_tokenCust' ] ))
					{
						$URL = $_POST['add_URLCust'];
						$TOKEN = $_POST['add_tokenCust'];
						$credentials_json_option = plugin_dir_path( __FILE__ ).'credentials.json';
						$inp = file_get_contents($credentials_json_option);
						$tempArray = json_decode($inp, true);
						$encryptTOKEN = encrypt_decrypt('encrypt', $TOKEN);
						$tempArray["URL"] = $URL;
						$tempArray["TOKEN"] = $encryptTOKEN;
						$jsonData = json_encode($tempArray);
						$msg = 'success';
						file_put_contents(plugin_dir_path( __FILE__ ).'credentials.json', $jsonData);
						delete_option( 'add_URLCust' );
						delete_option( 'add_tokenCust' );
					}
				elseif(isset ( $_POST[ 'add_URLCust' ] ) && empty($_POST[ 'add_URLCust' ] ) && isset ( $_POST[ 'add_tokenCust' ] ) && !empty($_POST[ 'add_tokenCust' ] ))
				{
					$msg = 'missUrl';
					update_option( 'add_URLCust', $_POST[ 'add_URLCust' ] );
						update_option( 'add_tokenCust', $_POST[ 'add_tokenCust' ] );
					
				}
				elseif(isset ( $_POST[ 'add_URLCust' ] ) && !empty($_POST[ 'add_URLCust' ] ) && isset ( $_POST[ 'add_tokenCust' ] ) && empty($_POST[ 'add_tokenCust' ] ))
				{
					$msg = 'missToken';
					update_option( 'add_URLCust', $_POST[ 'add_URLCust' ] );
						update_option( 'add_tokenCust', $_POST[ 'add_tokenCust' ] );
				}
				elseif(isset ( $_POST[ 'add_URLCust' ] ) && empty($_POST[ 'add_URLCust' ] ) && isset ( $_POST[ 'add_tokenCust' ] ) && empty($_POST[ 'add_tokenCust' ] ))
				{
					$msg = 'missUrlToken';
					update_option( 'add_tokenCust', $_POST[ 'add_tokenCust' ] );
					update_option( 'add_URLCust', $_POST[ 'add_URLCust' ] );
				}
			} elseif ( isset($_POST['secondary']) && $_POST['secondary'] === 'Testa') {
				if(isset ( $_POST[ 'add_URLCust' ] ) && !empty($_POST[ 'add_URLCust' ] ) && isset ( $_POST[ 'add_tokenCust' ] ) && !empty($_POST[ 'add_tokenCust' ] )) 
					{
						$URL = $_POST['add_URLCust'];
						$TOKEN = $_POST['add_tokenCust'];
						$url = $URL."/api/test";
						$args = array(
							'headers'   => array(
								"token" => $TOKEN,
								),
							'timeout'     => 60,
							'redirection' => 5,
							); 
						
						$response = wp_remote_get($url, $args);
						
						if (!is_wp_error($response)) {
							$msg = json_decode($response['body'], true);
							if (isset($msg->status)) {
								$msg = $response->status;
								}
						} else {
							$msg = 'Url non valido';
						}
					
						write_log($response);
					}
				elseif(isset ( $_POST[ 'add_URLCust' ] ) && empty($_POST[ 'add_URLCust' ] ) && isset ( $_POST[ 'add_tokenCust' ] ) && !empty($_POST[ 'add_tokenCust' ] ))
					{
						$msg = 'missUrl';
						update_option( 'add_URLCust', $_POST[ 'add_URLCust' ] );
						update_option( 'add_tokenCust', $_POST[ 'add_tokenCust' ] );
					}
				elseif(isset ( $_POST[ 'add_URLCust' ] ) && !empty($_POST[ 'add_URLCust' ] ) && isset ( $_POST[ 'add_tokenCust' ] ) && empty($_POST[ 'add_tokenCust' ] ))
					{
						$msg = 'missToken';
						update_option( 'add_URLCust', $_POST[ 'add_URLCust' ] );
						update_option( 'add_tokenCust', $_POST[ 'add_tokenCust' ] );
						write_log(esc_attr( get_option('add_URLCust')));
					}
				elseif(isset ( $_POST[ 'add_URLCust' ] ) && empty($_POST[ 'add_URLCust' ] ) && isset ( $_POST[ 'add_tokenCust' ] ) && empty($_POST[ 'add_tokenCust' ] ))
					{
						$msg = 'missUrlToken';
						update_option( 'add_URLCust', $_POST[ 'add_URLCust' ] );
						update_option( 'add_tokenCust', $_POST[ 'add_tokenCust' ] );
					}
			} else
			{
				delete_option( 'add_URLCust' );
				delete_option( 'add_tokenCust' );
				$msg = 'deleted';
				
			}
			if ( ! isset ( $_POST['_wp_http_referer'] ) )
				die( 'Missing target.' );
	
			$url = add_query_arg( 'msg', $msg, urldecode( $_POST['_wp_http_referer'] ) );
	
			wp_safe_redirect( $url );
			exit;
		}



		add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
			function custom_woocommerce_auto_complete_order( $order_id ) { 
				if ( ! $order_id ) {
					return;
				}

				$order = wc_get_order( $order_id );
				$order->update_status( 'completed' );
			}
				
		add_action( 'woocommerce_order_status_completed', 'qoniq_api_call_iscrizione');
			function qoniq_api_call_iscrizione( $order_id ){

				// Order Setup Via WooCommerce

				$order = new WC_Order( $order_id );
			
				// Iterate Through Items
				// dd($order_id);
				$items = $order->get_items(); 
				write_log($order_id);
				foreach ( $items as $item ) {	
					
					// write_log($item);
					// Store Product ID
					$totalNoTax =  $item->get_total();
					$tax = $item->get_total_tax();
					$total = $totalNoTax + $tax;
					$product_id = $item['product_id'];
					$product = new WC_Product($item['product_id']);
					$product_name = $item->get_name();
					// Check for "API" Category and Run
					write_log($product_id);
					write_log($product_name);
					
						//partecipante
						$namePart		= get_post_meta( $order->get_id(), '_name_partecipante', true );
						write_log($namePart);
						$surnamePart	= get_post_meta( $order->get_id(), '_surname_partecipante', true );
						write_log($surnamePart);
						$emailPart		= get_post_meta( $order->get_id(), '_email_partecipante', true );
						$bithPlacePart	= get_post_meta( $order->get_id(), '_birthPlace_partecipante', true );
						$bithDayPart	= get_post_meta( $order->get_id(), '_birthDay_partecipante', true );
						$CFPart			= get_post_meta( $order->get_id(), '_CF_partecipante', true );
						$cityResPart	= get_post_meta( $order->get_id(), '_city_of_residence_partecipante', true );
						$phonePart		= get_post_meta( $order->get_id(), '_phone_partecipante', true );
						$professPart	= get_post_meta( $order->get_id(), '_profession_partecipante', true ); 
						$professOrdPart	= get_post_meta( $order->get_id(), '_professional_order_partecipante', true );
						$numSubsOrPart	= get_post_meta( $order->get_id(), '_number_subs_per_order_partecipante', true );
						//fatturazione
						$nameBill		= $order->get_billing_first_name();
						$applicantBill	= get_post_meta( $order->get_id(), '_billing_applicant', true );
						$SDIbill		= get_post_meta( $order->get_id(), '_billing_SDI_fatturazione', true );
						$PECbill     =	 get_post_meta( $order->get_id(), '_billing_PEC_fatturazione', true );
						$EMAILbill 	= $order->get_billing_email();
						$PIVAbill 	= get_post_meta( $order->get_id(), 'billing_PIVA_fatturazione', true );
						$CFbill 	= get_post_meta( $order->get_id(), 'billing_CF_fatturazione', true ); 
						$CODESTbill 	= get_post_meta( $order->get_id(), 'billing_COD_Dest_fatturazione', true );  
						$cityBill 	= $order->get_billing_city();
						$statoBill 	= $order->get_billing_country();
						$capBill 	= $order->get_billing_postcode();
						$provinciaBill 	= $order->get_billing_state();
						$addressBill 	= $order->get_billing_address_1();
						$paymentMet = $order->get_payment_method();

						// API Callout to URL
						$credentials_json_option = plugin_dir_path( __FILE__ ).'credentials.json';
						$credentials = file_get_contents($credentials_json_option);
						$credentialsDec = json_decode($credentials, true);
						$apiUrl = $credentialsDec['URL'];
						write_log($credentialsDec);
						write_log($apiUrl);
						write_log($credentialsDec['TOKEN']);
						$decryptTOKEN = encrypt_decrypt('decrypt', $credentialsDec['TOKEN']);
						$url = $apiUrl."/api/iscrizione";
						write_log($decryptTOKEN);

						$body = array(
							"idordine" => $order_id,
							"nome" => $namePart,
							"cognome" =>$surnamePart,
							"mail" =>$emailPart,
							"cf" => $CFPart,
							"tel" => $phonePart,
							"datanascita" => $bithDayPart,
							"luogonascita" => $bithPlacePart,
							"indirizzo" => $cityResPart,
							"ordineprof" => $professOrdPart,
							"ordineprofN" => $numSubsOrPart,
							"professione" => $professPart,
							"metodopagamento" => $paymentMet,
							"idprodotto" => $product_id,
							"nomeprodotto" => $product_name,
							"total" => $total,
							"fatturazione_sdi" => $SDIbill,
							"fatturazione_cf" => $CFbill,
							"fatturazione_piva" => $PIVAbill,
							"fatturazione_pec" => $PECbill | $CODESTbill,
							"fatturazione_nome" => $nameBill,
							"fatturazione_indirizzo" => $addressBill,
							"fatturazione_citta" => $cityBill,
							"fatturazione_provincia" => $provinciaBill,
							"fatturazione_cap" => $capBill,
							"fatturazione_paese" => $statoBill,
							// "token" => $decryptTOKEN,
						);

						$body = wp_json_encode( $body );



						$args = array(
							'headers'   => array(
								'Content-Type' => 'application/json',
								'Accept' => 'application/json',
								"token" => $decryptTOKEN,
								),
							'method'    => 'POST',
							'timeout'     => 60,
							'redirection' => 5,
							'blocking'    => true,
							'data_format' => 'body',		    
							'body'		=> $body,
							'cookies' => array()
							); 
						$response = wp_remote_post( $url, $args);
					
						$responceData = json_decode(wp_remote_retrieve_body( $response ), TRUE );
						write_log($responceData);
						if (!is_wp_error($response)) {
							$vars = json_decode($response['body'], true);
							if (isset($vars->status)) {
								$vars = $response->status;
								}
						}
						write_log($response);
								

				}
			}

			/**
			 * Add a custom field (in an order) to the emails
			 */
			add_filter( 'woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 10, 3 );
		
			function custom_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
				$fields['_name_partecipante'] = array(
					'label' => __( 'Nome del partecipante' ),
					'value' => get_post_meta( $order->get_id(), '_name_partecipante', true ),
				);
				$fields['_surname_partecipante'] = array(
					'label' => __( 'Cognome del partecipante' ),
					'value' => get_post_meta( $order->get_id(), '_surname_partecipante', true ),
				);
				$fields['_email_partecipante'] = array(
					'label' => __( 'Email del partecipante' ),
					'value' => get_post_meta( $order->get_id(), '_email_partecipante', true ),
				);
				$fields['_birthPlace_partecipante'] = array(
					'label' => __( 'Luogo di nascita del partecipante' ),
					'value' => get_post_meta( $order->get_id(), '_birthPlace_partecipante', true ) ,
				);
				$fields['_birthDay_partecipante'] = array(
					'label' => __( 'Giorno di nascita' ),
					'value' =>get_post_meta( $order->get_id(), '_birthDay_partecipante', true ) ,
				);
				$fields['_city_of_residence_partecipante'] = array(
					'label' => __( 'Città di residenza del partecipante' ),
					'value' =>get_post_meta( $order->get_id(), '_city_of_residence_partecipante', true ),
				);
				$fields['_phone_partecipante'] = array(
					'label' => __( 'Numero di telefono del partecipante' ),
					'value' =>get_post_meta( $order->get_id(), '_phone_partecipante', true ),
				);
				$fields['_profession_partecipante'] = array(
					'label' => __( 'Professione del partecipante' ),
					'value' =>get_post_meta( $order->get_id(), '_profession_partecipante', true ),
				);
				$fields['_professional_order_partecipante'] = array(
					'label' => __( 'Ordine professionale del partecipante' ),
					'value' =>get_post_meta( $order->get_id(), '_professional_order_partecipante', true ),
				);
				$fields['_professional_order_partecipante'] = array(
					'label' => __( 'Ordine professionale del partecipante' ),
					'value' =>get_post_meta( $order->get_id(), '_professional_order_partecipante', true ),
				);
				if( !empty(get_post_meta( $order->get_id(), '_number_subs_per_order_partecipante', true )) ) {
					$fields['_number_subs_per_order_partecipante'] = array(
						'label' => __( 'Numero iscritti per ordine professionale' ),
						'value' => get_post_meta( $order->get_id(), '_number_subs_per_order_partecipante', true ),
					);
				}
				return $fields;
			}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_airforma_woocommerceintegration() {

	$plugin = new Airforma_Woocommerceintegration();
	$plugin->run();

}
run_airforma_woocommerceintegration();
