(function( $ ) {
	'use strict';

	jQuery(document).ready(function(){

		jQuery("#crm_pro_update_chat_settings").on("click", function(e){
			e.preventDefault();
			jQuery(".spinner").addClass("is-active"); 

			let nonce = jQuery(this).attr("data-nonce");
		
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: crm_pro.ajaxurl,
				data: {action: "crn_pro_chat_update", nonce: nonce },
				success: function(response){
					jQuery(".spinner").removeClass("is-active"); 
					alert(response.message);
				}
			})
		})
	})

})( jQuery );
