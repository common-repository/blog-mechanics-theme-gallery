/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */
 /* twoJGalleryJSContent */
;(function (window, $) {
    window.twoJGalleryJSContent = function (gallery) {
        twoJGalleryJSBlock.apply(this, arguments);

        var self = this;
        self.grid = {
            entity: undefined,
            setting: undefined,
            playlistMeta: []
        };

        self.lightbox = {
            entity: undefined,
            obj: $(this),
            items: [],
            setting: $.extend(
                {},
                self.gallery.config.get('gallery/block/content/lightbox/setting'),
                {selector: "[data-twojg-link-type='image'], [data-twojg-link-type='video']"}
            )
        };

        self.gallery.$element.on('onChangeGallery', function (event, args) {
            self.onChangeGallery(event, args);
        });
        self.gallery.$element.on('onChangePage', function (event, args) {
            self.onChangePage(event, args);
        });
    };

    twoJGalleryJSContent.prototype = Object.create(twoJGalleryJSBlock.prototype);
    twoJGalleryJSContent.prototype.constructor = twoJGalleryJSContent;

    twoJGalleryJSContent.prototype.name = 'content';

    twoJGalleryJSContent.prototype.init = function () {
        var self = this;

        // remove previous grid
        if (self.grid.setting && self.grid.setting.instanceName && window[self.grid.setting.instanceName]) {
            window[self.grid.setting.instanceName] = undefined;
        }

        self.grid.setting = $.extend({}, self.gallery.config.get('gallery/block/content/grid/setting'));
        self.grid.setting.parentId = self.$element.find('.js-grid-view').attr('id');
        self.grid.setting.playlistId = self.$element.find('.js-playlist').attr('id');
        self.grid.setting.instanceName = 'instanceName-' + self.grid.setting.parentId;
        self.grid.setting.mainFolderPath = twoJGalleryJSConst.moduleUri + self.grid.setting.mainFolderPath;

        // collect grid items meta
        self.itemCount = 0;
        self.itemLightboxCount = 0;
        self.grid.playlistMeta = [];
        self.lightbox.contHTML = '';
        self.lightbox.items = [];

        getImageHTML = function (image, thumb, title){
        	return '<a href="'+image+'" title="'+title+'"><img src="'+thumb+'" /></a>';
        }

        $('#' + self.grid.setting.playlistId + ' ul').each(function (i, list) {
            $('li', list).each(function (i, item) {
                var $item = $(item);

                self.grid.playlistMeta[self.itemCount] = {
                    'data-twoJG-id': $item.attr('data-twoJG-id'),
                    'data-twoJG-type': $item.attr('data-twoJG-type'),
                    'data-twoJG-link-type': $item.attr('data-twoJG-link-type'),
                    'data-url': $item.attr('data-url'),

                    'data-thumbnail': $item.attr('data-thumbnail'),
                    'data-caption': $item.attr('data-caption'),

                };

                if( 
                	$item.attr('data-twoJG-link-type') == 'image' || 
                	$item.attr('data-twoJG-link-type') == 'video' 
                	){
                		
                		self.lightbox.contHTML += getImageHTML( 
                			$item.attr('data-url'), 
                			$item.attr('data-thumbnail'), 
                			$item.attr('data-caption') 
                		);

	                	/*self.lightbox.items[self.itemLightboxCount] = {
		                	'src': $item.attr('data-url'),
		                	'thumb': $item.attr('data-thumbnail'),
		                }*/

		                self.grid.playlistMeta[self.itemCount]['data-lightbox-index'] = self.itemLightboxCount;

		                self.itemLightboxCount++;
                }
                //console.log(self.grid.playlistMeta[itemCount]);
                self.itemCount++;

            });
        });

        //console.log(self.grid.playlistMeta);

        if (self.lightbox.entity) {
           // self.lightbox.entity.destroy(true);
           self.lightbox.entity.remove();
            self.lightbox.entity = undefined;
            self.lightbox.obj = $(self);
        }
        
        //console.log('count ' + self.itemCount + ' count lightbox:'+self.itemLightboxCount );

        if( GAMLDATUtils.isReadyMethodCalled_bl==false) GAMLDATUtils.checkIfHasTransofrms();

        self.grid.entity = new GAMMAGRID(self.grid.setting);

        // disable setup inbuilt lightbox
        self.grid.entity.setupLightBox = function () {   };

        // override open lightbox
        self.grid.entity.openLightbox = function(e) { 
      
        	var firstRun = 0;
        	if(!self.lightbox.entity) firstRun = 1;
        
        	//console.log(e);
        	//console.log('first'+firstRun);

        	var playlistItemMeta = self.grid.playlistMeta[e.id] || null;
            if (!playlistItemMeta) {
            	console.log('error: empty item');
                return false;
            }


            if ('self' == playlistItemMeta['data-twoJG-link-type']) {
                location.href = playlistItemMeta['data-url'];
                return false;
            }

            if ('blank' == playlistItemMeta['data-twoJG-link-type']) {
                window.open(playlistItemMeta['data-url'], '_blank');
                return false;
            }

            if ('gallery' == playlistItemMeta['data-twoJG-link-type']) {
                self.gallery.$element.trigger('onChangeGallery', {gallery_id: playlistItemMeta['data-twoJG-id']});
                return false;
            }

            var lightboxElementCount = 0,
                lightboxStartFrom = 0,
                lightboxelementId = 0;

            $.each(self.grid.entity.data.lightboxPlaylist_ar['playlistItems'], function (id, playlistItem) {
            	var playlistItemMeta = self.grid.playlistMeta[id];

            	if (id == e.id) {
            		lightboxStartFrom = playlistItemMeta['data-lightbox-index'];
            		return false;
                }
                lightboxElementCount++;
            }); 

            //console.log(lightboxStartFrom);

            if(firstRun){


            	self.lightbox.entity = $(self.lightbox.contHTML).tosrus({ 
					'show': true,
					'slides': {
						'visible': true,
					},
					'caption': {
						'add': true,
					},
					'drag': true,
					//buttons    : "inline",
					'pagination' : {
						'add': true,
						'type': "thumbnails"
					}
				});

            	$('.tos-wrapper').on('dblclick', function(event) {
            		event.preventDefault();
            		$('.tos-close', this).click();
            	});

            }
            self.lightbox.entity.trigger("open", lightboxStartFrom, true );

	           /* self.lightbox.obj.lightGallery(
	                $.extend(
	                    {},
	                    self.gallery.config.get('gallery/block/content/lightbox/setting'),
	                    {
	                    	'dynamic': true,
	                    	'dynamicEl': self.lightbox.items,
	                    	'index': lightboxStartFrom
	                    }
	                )
	            );
	            self.lightbox.entity = self.lightbox.obj.data('lightGallery');*/
           /* } else {
				self.lightbox.obj.lightGallery();
	        	self.lightbox.obj.data('lightGallery').index = lightboxStartFrom;
	        }*/
            
        	return false ;
        };
      
    };


    twoJGalleryJSContent.prototype.onLoad = function (args, response) {
        $('html, body').animate({scrollTop: this.gallery.$element.offset().top - 100}, 'slow');
        this.$element.html(response);
        this.init();
    };

    twoJGalleryJSContent.prototype.onChangeGallery = function (event, args) {
        $('html, body').animate({scrollTop: this.gallery.$element.offset().top - 100}, 'slow');
        this.$element.html('');
        this.load(args);
    };

    twoJGalleryJSContent.prototype.onChangePage = function (event, args) {
        $('html, body').animate({scrollTop: this.gallery.$element.offset().top - 100}, 'slow');
        this.$element.html('');
        this.load(args);
    };

})(window, jQuery);