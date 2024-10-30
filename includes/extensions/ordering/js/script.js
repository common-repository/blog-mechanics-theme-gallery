/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */

 ;(function ($, undefined){
    

    function TwoJClassOrdering(options) {
    	var self = this;

    	this.$element = $('#wrapper-nestable-list');

    	this.$spinner = $('.nestable-list-spinner');

    	this.$status = $('#gallery_label_status');

        this.$edit = this.$element.find('.buttonSave');
        
        this.$nestableList = this.$element.find('.nestable-list');

        this.$nestableList.nestable({
        	group: 1, 
        	maxDepth: 100,
        	callback: function(l,e){
        		self.statusUpdate(gallery2JOrderingAttributes.status.modified);
    		}
       	});
        
        window['catDialogClick'] = 0 ;

        this.init();
    }
    TwoJClassOrdering.prototype = {
        init: function() {
            var self = this;

            self.$edit.click( function(){
            	if( window['catDialogClick'] == 0 ){
            		window['catDialogClick'] = 1;
            		return self.save();
            	} else {
            		return false;
            	}
            });
        },
        
        save : function () {
            var self = this,
                hierarchyPosts = this.$nestableList.nestable('serialise');

            self.spinner(true);
            self.statusUpdate(gallery2JOrderingAttributes.status.saving);
            self.$nestableList.hide();

            $.ajax({
                url: gallery2JOrderingAttributes.ajaxUrl,
                method: 'post',
                data: {
                    action: gallery2JOrderingAttributes.action.save,
                    hierarchy_posts: hierarchyPosts
                },
                success: function (response) {
                    self.spinner(false);
                    self.$nestableList.show();
                    window['catDialogClick'] = 0;;
                    self.statusUpdate(gallery2JOrderingAttributes.status.saved);
                },
                error: function (jqXHR) {
                	self.statusUpdate( gallery2JOrderingAttributes.status.error);
                    new DialogError(jqXHR.responseText);
                }
            });
        },
        spinner: function(isShow) {
            if (isShow) {
                this.$spinner.show();
            } else {
                this.$spinner.hide();
            }
        },
        statusUpdate: function(text) {
            this.$status.text(text);
        },
        destroy: function () {
        	window['catDialogClick'] = 0;
            this.$element.dialog('close');
            this.$element.remove();
        }
    };

    function DialogError(message) {
        this.$element = $('<div id="hierarchy-post-error">' + message + '</div>');
        this.show()
    }
    DialogError.prototype = {
        show: function () {
            var self = this;

            self.$element.appendTo('body');
            self.$element.dialog({
                'dialogClass' : 'wp-dialog',
                'title': gallery2JOrderingAttributes.status.error,
                'modal' : true,
                'autoOpen' : true,
                'closeOnEscape' : false,
                'buttons' : [
                    {
                        'text' : gallery2JOrderingAttributes.status.ok,
                        'class' : 'button',
                        'click' : function() { return self.destroy(); }
                    }
                ],
                'close': function() { return self.destroy(); }
            });
        },
        destroy: function () {
            this.$element.dialog('close');
            this.$element.remove();
        }
    };

    $(document).ready(function() {
        new TwoJClassOrdering( {} );
    });
}(jQuery));
