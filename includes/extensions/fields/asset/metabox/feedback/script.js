/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */

(function ($) {
    $(document).ready(function () {
    	var buttonObj = $('#twojGalleryFeedbackButton');

    	var messageObj = $('#twoj_field_feedback_message');	
		var emailObj = $('#twoj_field_feedback_email');

		var twojGalleryValidateEmail = function(email) {
    		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    		return re.test(email);
		}

    	buttonObj.click(function(event){

			event.preventDefault();
			
			var emailVal = emailObj.val();
			var messageVal = messageObj.val();

			if(emailVal=='' || messageVal==''  ){
				alert(twojGalleryFeedbackTr.messageEmpty);
				return;
			}
			if( !twojGalleryValidateEmail(emailVal) ){
				alert(twojGalleryFeedbackTr.messageCorrectEmail);
				return;
			}

			buttonObj.addClass('loading');
			messageObj.attr( 'disabled', 'disabled');
			emailObj.attr( 'disabled', 'disabled');

			var dataGet = {
				'feedback': 1, 
				'message': messageVal, 
				'email': emailVal
			};

			jQuery.getJSON( 'http://2joomla.net/voting/action.php?callback=?', dataGet, function(response) {
				if(response.mess=='success'){
					jQuery.post(ajaxurl, { 'action': 'twoj_gallery_fields_saveoption', 'feedback': 1 }, function(response) {
						if(response=='ok'){
							$('#twoj_gallery_fields_feedback_message').html( twojGalleryFeedbackTr.messageOk );
							$('#field_feedback_form').remove();
						} else {
							$('#twoj_gallery_fields_feedback_message').html( twojGalleryFeedbackTr.messageError );
						}
					});
				} else {
					$('#twoj_gallery_fields_feedback_message').html( twojGalleryFeedbackTr.messageError );
					$('#field_feedback_form').remove();
				}
			});
		});
	});
})(jQuery);