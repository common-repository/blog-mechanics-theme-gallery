/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */
 /* twoJGalleryJSBreadcrumbs */
;(function (window, $) {
    window.twoJGalleryJSBreadcrumbs = function (gallery) {
        twoJGalleryJSBlock.apply(this, arguments);

        var self = this;

        self.gallery.$element.on('onChangeGallery', function (event, args) {
            self.onChangeGallery(event, args);
        });
    };

    twoJGalleryJSBreadcrumbs.prototype = Object.create(twoJGalleryJSBlock.prototype);
    twoJGalleryJSBreadcrumbs.prototype.constructor = twoJGalleryJSBreadcrumbs;
    twoJGalleryJSBreadcrumbs.prototype.name = 'breadcrumbs';
    
    twoJGalleryJSBreadcrumbs.prototype.init = function () {
        var self = this;

        $('a', self.$element).click(function () {
            var galleryId = $(this).attr('data-twoJG-id');

            self.gallery.$element.trigger('onChangeGallery', {gallery_id: galleryId});
            return false;
        });
    };

    twoJGalleryJSBreadcrumbs.prototype.onChangeGallery = function (event, args) {
        this.load({
            gallery_id: this.gallery.$element.attr('data-twoJG-root-id'),
            active_gallery_id: args.gallery_id
        });
    };
})(window, jQuery);

/* twoJGalleryJSPagination */
;(function (window, $) {
    window.twoJGalleryJSPagination = function (gallery) {
        twoJGalleryJSBlock.apply(this, arguments);

        var self = this;
        self.isLoadingThumbs = true;

        self.gallery.$element.on('onChangeGallery', function (event, args) {
            self.onChangeGallery(event, args);
        });
    };

    twoJGalleryJSPagination.prototype = Object.create(twoJGalleryJSBlock.prototype);
    twoJGalleryJSPagination.prototype.constructor = twoJGalleryJSPagination;
    twoJGalleryJSPagination.prototype.name = 'paging';
    
    twoJGalleryJSPagination.prototype.init = function () {
        var self = this;

        switch (self.view) {
            case 'numbering':
                self.initViewNumbering();
                break;
            case 'load_more':
                self.initViewLoadMore();
                break;
        }
    };

    twoJGalleryJSPagination.prototype.initViewNumbering = function () {
        var self = this,
            $pageNumbering = $('.numbering', this.$element),
            setting = self.gallery.config.get('gallery/block/pagination/numbering/setting');

        if( $pageNumbering.length > 0){
	        $pageNumbering.pagination($.extend(setting, {
	            currentPage: $pageNumbering.attr('data-twoJG-page'),
	            itemsOnPage: $pageNumbering.attr('data-twoJG-per_page'),
	            pages: $pageNumbering.attr('data-twoJG-pages'),
	            onPageClick: function (pageNumber) {
	                self.setPage(pageNumber);
	                return false;
	            }
	        }));
	    }
    };

    twoJGalleryJSPagination.prototype.initViewLoadMore = function () {
        var self = this,
            $loadMore = $('.load_more', self.$element);

        if ($loadMore.length) {
            $('a', $loadMore).on('click', function () {
                if (self.isLoadingThumbs) {
                    return false;
                }

                var page = self.getPage(),
                    pages = self.getPages();

                page += 1;
                if (page <= pages) {
                    self.isLoadingThumbs = true;
                    self.setPage(page);
                }
                if (page === pages) {
                    $loadMore.hide();
                }

                return false;
            });
        }

        $(self.gallery.blocks['content'].$element).off('jsGridFW.loaded.gallery', '.grid');
        $(self.gallery.blocks['content'].$element).on('jsGridFW.loaded.gallery', '.grid', function () {
            setTimeout(function () { self.isLoadingThumbs = false; }, 500);
            // fix showing images
            setTimeout(function () { $(window).trigger('resize'); }, 2000);
            setTimeout(function () { $(window).trigger('resize'); }, 4000);
            setTimeout(function () { $(window).trigger('resize'); }, 6000);
        });

        if ($loadMore.length && 'scroll' == self.gallery.config.get('gallery/block/pagination/load_more/type')) {
            var $window = $(window),
                galleryId = self.gallery.getId(),
                offset = 0,
                galleryNamespace = 'gallery-' + galleryId;

            $window.off('scroll.' + galleryNamespace);
            $window.on('scroll.' + galleryNamespace, function () {
                if (self.isLoadingThumbs) {
                    return;
                }

                if (($window.height() + $window.scrollTop()) > ($loadMore.offset().top + offset)) {
                    var page = self.getPage(),
                        pages = self.getPages();

                    if (page < pages) {
                        $('a', $loadMore).trigger('click');
                    }
                }
            });
        }
    };

    twoJGalleryJSPagination.prototype.setPage = function (page) {
        var args = {};
        args[this.name] = {page: page};

        $('.' + this.view, this.$element).attr('data-twoJG-page', page);
        this.gallery.$element.trigger('onChangePage', args);
    };

    twoJGalleryJSPagination.prototype.getPage = function (event, args) {
        var page = parseInt($('.' + this.view, this.$element).attr('data-twoJG-page'));
        return isNaN(page) ? 1 : page;
    };

    twoJGalleryJSPagination.prototype.getPerPage = function (event, args) {
        var perPage =  parseInt($('.' + this.view, this.$element).attr('data-twoJG-per_page'));
        return isNaN(perPage) ? 12 : perPage;
    };

    twoJGalleryJSPagination.prototype.getPages = function (event, args) {
        var pages = parseInt($('.' + this.view, this.$element).attr('data-twoJG-pages'));
        return isNaN(pages) ? 1 : pages;
    };

    twoJGalleryJSPagination.prototype.onChangeGallery = function (event, args) {
        args[this.name] = {page: 1};
        this.load(args);
    };

})(window, jQuery);