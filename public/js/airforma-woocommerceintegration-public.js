(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * */
	$( window ).load(function() {
		if(document.querySelector('.woocommerce-checkout') && !document.querySelector('.woocommerce-order-details')) {
		let billingStateLabel = document.querySelector('#billing_state_field label');
		billingStateLabel.innerHTML = 'Provincia&nbsp <span class="required">*</span>';
			let PIVAInput = document.getElementById("billing_VAT_fatturazione_field");
			let PIVAInputInput = document.getElementById("billing_VAT_fatturazione");
				if(document.querySelector("#billing_CF_fatturazione_field label span.optional")){
					let spanOption2 = document.querySelector("#billing_VAT_fatturazione_field label span.optional");
					spanOption2.innerHTML = "*";
					spanOption2.classList.remove("optional");
					spanOption2.classList.add("required");
					}
				PIVAInput.required = true;
			let CFInput = document.getElementById("billing_CF_fatturazione_field");
			let PECInput = document.getElementById("billing_PEC_fatturazione_field");
			let CODESTInput = document.getElementById("billing_COD_Dest_fatturazione_field");
			let CFInputInput = document.getElementById("billing_CF_fatturazione");
			let billingApplicant = document.querySelector('select[name="billing_applicant"]');
			billingApplicant.addEventListener("change", ()=> {
				if(billingApplicant.value === "Privato") {
					if(document.querySelector("#billing_CF_fatturazione_field label span.optional")){
					let spanOption = document.querySelector("#billing_CF_fatturazione_field label span.optional");
					spanOption.innerHTML = "*";
					spanOption.classList.remove("optional");
					spanOption.classList.add("required");
					}
					CFInput.classList.remove("hiddenCustomFied");
					PIVAInput.classList.add("hiddenCustomFied");
					CODESTInput.classList.add("hiddenCustomFied");
					PIVAInputInput.required = false;
				CFInputInput.required = true;
			} else {
				CFInput.classList.add("hiddenCustomFied");
				CFInputInput.required = false;
				PIVAInput.classList.remove("hiddenCustomFied");
				CODESTInput.classList.remove("hiddenCustomFied");
				if(document.querySelector("#billing_CF_fatturazione_field label span.required")){
				let spanOption = document.querySelector("#billing_CF_fatturazione_field label span.required");
					spanOption.innerHTML = "(optional)";
					spanOption.classList.remove("required");
					spanOption.classList.add("optional");
				}
				
				
				PIVAInputInput.required = true;

	  }

	})
}
	});
	/**
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
