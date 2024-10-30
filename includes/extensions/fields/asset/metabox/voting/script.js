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
    	$('#twoj_gallery_fields_voting_buttons .twojGalleryVotingButton').each(function() {
			var button = this;
			var buttonObj = $(button);

			buttonObj.click(function(event){
				event.preventDefault();

				$('#twoj_gallery_fields_voting_message').html( '<div class="votingLoading"></div>' );
				$('#twoj_gallery_fields_voting_buttons').hide();

				jQuery.getJSON( 'http://2joomla.net/voting/action.php?callback=?', { 'vote': buttonObj.data('vote') }, function(response) {
					if(response.mess=='success'){
						jQuery.post(ajaxurl, { 'action': 'twoj_gallery_fields_saveoption' }, function(response) {
							if(response=='ok'){
								$('#twoj_gallery_fields_voting_message').html( twojGalleryVotingTr.messageOk );
								$('#twoj_gallery_fields_voting_buttons').remove();
							} else {
								$('#twoj_gallery_fields_voting_message').html( twojGalleryVotingTr.messageError );
							}
						});
					} else {
						$('#twoj_gallery_fields_voting_message').html( twojGalleryVotingTr.messageError );
					}
				});
			});
		});
	});
})(jQuery);