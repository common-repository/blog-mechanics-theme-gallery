/*
 *	jQuery Touch Optimized Sliders "R"Us 2.5.0
 *	
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 *
 *	Plugin website:
 *	tosrus.frebsite.nl
 *
 *	Licensed under the MIT license.
 *	http://en.wikipedia.org/wiki/MIT_License
 */



(function( $ ) {

	var _PLUGIN_	= 'tosrus',
		_ABBR_		= 'tos',
		_VERSION_	= '2.5.0';


	//	Plugin already excists
	if ( $[ _PLUGIN_ ] )
	{
		return;
	}


	//	Global variables
	var _c = {}, _d = {}, _e = {}, _f = {}, _g = {};


	/*
		Class
	*/
	$[ _PLUGIN_ ] = function( $node, opts, conf )
	{
		this.$node	= $node;
		this.opts	= opts;
		this.conf	= conf;

		this.vars	= {};
		this.nodes	= {};
		this.slides	= {};

		this._init();

		return this;
	};
	$[ _PLUGIN_ ].prototype = {

		//	Initialize the plugin
		_init: function()
		{
			var that = this;

			this._complementOptions();
			this.vars.fixed = ( this.opts.wrapper.target == 'window' );

			//	Add markup
			this.nodes.$wrpr = $('<div class="' + _c.wrapper + '" />');
			this.nodes.$sldr = $('<div class="' + _c.slider + '" />').appendTo( this.nodes.$wrpr );
	
			this.nodes.$wrpr
				.addClass( this.vars.fixed ? _c.fixed : _c.inline )
				.addClass( _c( 'fx-' + this.opts.effect ) )
				.addClass( _c( this.opts.slides.scale ) )
				.addClass( this.opts.wrapper.classes );

			//	Bind events
			this.nodes.$wrpr

				//	Custom events
				.on( _e.open + ' ' + _e.close + ' ' + _e.prev + ' ' + _e.next + ' ' + _e.slideTo,
					function( e )
					{
						arguments = Array.prototype.slice.call( arguments );
						var e = arguments.shift(),
							t = e.type;
	
						e.stopPropagation();

						if ( typeof that[ t ] == 'function' )
						{
							that[ t ].apply( that, arguments );
						}
					}
				)

				//	Callback events
				.on( _e.opening + ' ' + _e.closing + ' ' + _e.sliding + ' ' + _e.loading + ' ' + _e.loaded,
					function( e )
					{
						e.stopPropagation();
					}
				)

				//	Toggle UI
				.on( _e.click,
					function( e )
					{
						e.stopPropagation();

						switch ( that.opts.wrapper.onClick )
						{
							case 'toggleUI':
								that.nodes.$wrpr.toggleClass( _c.hover );
								break;
							
							case 'close':
								if ( !$(e.target).is( 'img' ) )
								{
									that.close();
								}
								break;
						}
					}
				);

/*
			//	Prevent pinching if opened
			if ( $.fn.hammer && $[ _PLUGIN_ ].support.touch )
			{
				this.nodes.$wrpr
					.hammer()
					.on( _e.pinch,
						function( e )
						{
							if ( _g.$body.hasClass( _c.opened ) )
							{
								e.gesture.preventDefault();
								e.stopPropagation();
							}
						}
					);
			}
*/

			//	Nodes
			this.nodes.$anchors = this._initAnchors();
			this.nodes.$slides  = this._initSlides();

			//	Slides
			this.slides.total	= this.nodes.$slides.length;
			this.slides.visible	= this.opts.slides.visible;
			this.slides.index	= 0;

			//	Vars
			this.vars.opened	= true;


			//	Init addons
			for ( var a = 0; a < $[ _PLUGIN_ ].addons.length; a++ )
			{
				if ( $.isFunction( this[ '_addon_' + $[ _PLUGIN_ ].addons[ a ] ] ) )
				{
					this[ '_addon_' + $[ _PLUGIN_ ].addons[ a ] ]();
				}
			}
			for ( var u = 0; u < $[ _PLUGIN_ ].ui.length; u++ )
			{
				if ( this.nodes.$wrpr.find( '.' + _c[ $[ _PLUGIN_ ].ui[ u ] ] ).length )
				{
					this.nodes.$wrpr.addClass( _c( 'has-' + $[ _PLUGIN_ ].ui[ u ] ) );
				}
			}


			//	Prevent closing when clicking on UI elements
			if ( this.opts.wrapper.onClick == 'close' )
			{
				this.nodes.$uibg || $()
					.add( this.nodes.$capt || $() )
					.add( this.nodes.$pagr || $() )
					.on( _e.click,
						function( e )
						{
							e.stopPropagation();
						}
					);
			}


			//	Start
			if ( this.vars.fixed )
			{
				this.nodes.$wrpr.appendTo( _g.$body );
				this.close( true );
			}
			else
			{
				this.nodes.$wrpr.appendTo( this.opts.wrapper.target );

				if ( this.opts.show )
				{
					this.vars.opened = false;
					this.open( 0, true );
				}
				else
				{
					this.close( true );
				}
			}
		},


		//	Open method, opens the gallery and slides to the designated slide
		open: function( index, direct )
		{
			var that = this;

			if ( !this.vars.opened )
			{
				if ( this.vars.fixed )
				{
					_g.scrollPosition = _g.$wndw.scrollTop();
					_g.$body.addClass( _c.opened );
					_f.setViewportScale();
				}

				if ( direct )
				{
					this.nodes.$wrpr
						.addClass( _c.opening )
						.trigger( _e.opening, [ index, direct ] );
				}
				else
				{
					setTimeout(
						function()
						{
							that.nodes.$wrpr
								.addClass( _c.opening )
								.trigger( _e.opening, [ index, direct ] );
						}, 5
					);
				}

				this.nodes.$wrpr
					.addClass( _c.hover )
					.addClass( _c.opened );
			}

			this.vars.opened = true;
			this._loadContents();

			//	Slide to given slide
			if ( $.isNumeric( index ) )
			{
				direct = ( direct || !this.vars.opened );
				this.slideTo( index, direct );
			}
		},


		//	Close method, closes the gallery
		close: function( direct )
		{
			if ( this.vars.opened )
			{
				if ( this.vars.fixed )
				{
					_g.$body.removeClass( _c.opened );
				}

				if ( direct )
				{
					this.nodes.$wrpr.removeClass( _c.opened );
				}
				else
				{
					_f.transitionend( this.nodes.$wrpr,
						function()
						{
							$(this).removeClass( _c.opened );
						}, this.conf.transitionDuration
					);
				}

				//	Close + Callback event
				this.nodes.$wrpr
					.removeClass( _c.hover )
					.removeClass( _c.opening )
					.trigger( _e.closing, [ this.slides.index, direct ] );
			}
			this.vars.opened = false;
		},


		//	Prev method, slides to the previous set of slides
		prev: function( slides, direct )
		{
			if ( !$.isNumeric( slides ) )
			{
				slides = this.opts.slides.slide;
			}
			this.slideTo( this.slides.index - slides, direct );

		},


		//	Next method, slides to the next set of slides
		next: function( slides, direct )
		{
			if ( !$.isNumeric( slides ) )
			{
				slides = this.opts.slides.slide;
			}
			this.slideTo( this.slides.index + slides, direct );
		},


		//	SlideTo method, slides to the designated slide
		slideTo: function( index, direct )
		{
			if ( !this.vars.opened )
			{
				return false;
			}
			if ( !$.isNumeric( index ) )
			{
				return false;
			}

			var doSlide = true;

			//	Less then first
			if ( index < 0 )
			{
				var atStart = ( this.slides.index == 0 );

				//	Infinite
				if ( this.opts.infinite )
				{
					if ( atStart )
					{
						index = this.slides.total - this.slides.visible;
					}
					else
					{
						index = 0;
					}
				}
				//	Non-infinite
				else
				{
					index = 0;
					if ( atStart )
					{
						doSlide = false;
					}
				}
			}

			//	More then last
			if ( index + this.slides.visible > this.slides.total )
			{
				var atEnd = ( this.slides.index + this.slides.visible >= this.slides.total );

				//	Infinite
				if ( this.opts.infinite )
				{
					if ( atEnd )
					{
						index = 0;
					}
					else
					{
						index = this.slides.total - this.slides.visible;
					}
				}
				//	Non-infinite
				else
				{
					index = this.slides.total - this.slides.visible;
					if ( atEnd )
					{
						doSlide = false;
					}
				}
			}

			this.slides.index = index;
			this._loadContents();

			if ( doSlide )
			{
				var left = 0 - ( this.slides.index * this.opts.slides.width ) + this.opts.slides.offset;
				if ( this.slides.widthPercentage )
				{
					left += '%';
				}

				if ( direct )
				{
					this.nodes.$sldr.addClass( _c.noanimation );
					_f.transitionend( this.nodes.$sldr,
						function()
						{
							$(this).removeClass( _c.noanimation );
						}, 5
					);
				}

				//	Transition
				for ( var e in $[ _PLUGIN_ ].effects )
				{
					if ( e == this.opts.effect )
					{
						$[ _PLUGIN_ ].effects[ e ].call( this, left, direct );
						break;
					}
				}
				
				//	Callback event
				this.nodes.$wrpr.trigger( _e.sliding, [ index, direct ] );
			}
		},

		_initAnchors: function()
		{
			var that = this,
				$a = $();

			if ( this.$node.is( 'a' ) )
			{
				for ( var m in $[ _PLUGIN_ ].media )
				{
					$a = $a.add( 
						this.$node.filter(
							function()
							{
								if ( that.opts.media[ m ] && that.opts.media[ m ].filterAnchors )
								{
									var result = that.opts.media[ m ].filterAnchors.call( that, $(this) );
									if ( typeof result == 'boolean' )
									{
										return result;
									}
								}
								return $[ _PLUGIN_ ].media[ m ].filterAnchors.call( that, $(this) );
							}
						)
					);
				}
			}
			return $a;
		},
		_initSlides: function()
		{
			this[ this.$node.is( 'a' ) ? '_initSlidesFromAnchors' : '_initSlidesFromContent' ]();
			return this.nodes.$sldr.children().css( 'width', this.opts.slides.width + ( this.slides.widthPercentage ? '%' : 'px' ) );
		},
		_initSlidesFromAnchors: function()
		{
			var that = this;

			this.nodes.$anchors
				.each(
					function( index )
					{
						var $anchor = $(this);

						//	Create the slide
						var $slide = $('<div class="' + _c.slide + ' ' + _c.loading + '" />')
							.data( _d.anchor, $anchor )
							.appendTo( that.nodes.$sldr );

						//	Clicking an achor opens the slide
						$anchor
							.data( _d.slide, $slide )
							.on( _e.click,
								function( e )
								{
									e.preventDefault();
									that.open( index );
								}
							);
					}
				);
		},
		_initSlidesFromContent: function()
		{
			var that = this;

			this.$node
				.children()
				.each(
					function()
					{
						var $slide = $(this);

						$('<div class="' + _c.slide + '" />')
							.append( $slide )
							.appendTo( that.nodes.$sldr );

						//	Init slide content
						for ( var m in $[ _PLUGIN_ ].media )
						{
							var result = null;
							if ( that.opts.media[ m ] && that.opts.media[ m ].filterSlides )
							{
								result = that.opts.media[ m ].filterSlides.call( that, $slide );
							}
							if ( typeof result != 'boolean' )
							{
								result = $[ _PLUGIN_ ].media[ m ].filterSlides.call( that, $slide );
							}
							if ( result )
							{
								$[ _PLUGIN_ ].media[ m ].initSlides.call( that, $slide );
								$slide.parent().addClass( _c( m ) );
								break;
							}
						}
					}
			);
		},

		_loadContents: function()
		{
			var that = this;

			switch ( this.opts.slides.load )
			{
				//	Load all
				case 'all':
					this._loadContent( 0, this.slides.total );
					break;

				//	Load current
				case 'visible':
					this._loadContent( this.slides.index, this.slides.index + this.slides.visible );
					break;

				//	Load current + prev + next
				case 'near-visible':
				default:
					this._loadContent( this.slides.index, this.slides.index + this.slides.visible );
					setTimeout(
						function()
						{
							that._loadContent( that.slides.index - that.slides.visible, that.slides.index );								//	prev
							that._loadContent( that.slides.index + that.slides.visible, that.slides.index + ( that.slides.visible * 2 ) );	//	next
						}, this.conf.transitionDuration
					);
					break;
			}
		},
		_loadContent: function( start, end )
		{
			var that = this;

			this.nodes.$slides
				.slice( start, end )
				.each(
					function()
					{
						var $slide		= $(this),
							contenttype = false;

						if ( $slide.children().length == 0 )
						{
							var $anchor = $slide.data( _d.anchor ),
								content = $anchor.attr( 'href' );

							//	Search for slide content
							for ( var m in $[ _PLUGIN_ ].media )
							{
								var result = null;
								if ( that.opts.media[ m ] && that.opts.media[ m ].filterAnchors )
								{
									result = that.opts.media[ m ].filterAnchors.call( that, $anchor );
								}
								if ( typeof result != 'boolean' )
								{
									result = $[ _PLUGIN_ ].media[ m ].filterAnchors.call( that, $anchor );
								}
								
								if ( result )
								{
									$[ _PLUGIN_ ].media[ m ].initAnchors.call( that, $slide, content );
									$slide.addClass( _c( m ) );
									break;
								}
							}

							//	Callback event
							$slide.trigger( _e.loading, [ $slide.data( _d.anchor ) ] );
						}
					}
			);
		},

		_complementOptions: function()
		{
			//	Wrapper
			if ( typeof this.opts.wrapper.target == 'undefined' )
			{
				this.opts.wrapper.target = ( this.$node.is( 'a' ) ) ? 'window' : this.$node;
			}
			if ( this.opts.wrapper.target != 'window' )
			{
				if ( typeof this.opts.wrapper.target == 'string' )
				{
					this.opts.wrapper.target = $(this.opts.wrapper.target);
				}
			}
	
			//	Show
			this.opts.show = _f.complBoolean(  this.opts.show, this.opts.wrapper.target != 'window' );

			//	Slides
			if ( $.isNumeric( this.opts.slides.width ) )
			{
				this.slides.widthPercentage	= false;
				this.opts.slides.visible 	= _f.complNumber( this.opts.slides.visible, 1 );
			}
			else
			{
				var percWidth = ( _f.isPercentage( this.opts.slides.width ) ) ? _f.getPercentage( this.opts.slides.width ) : false;

				this.slides.widthPercentage	= true;
				this.opts.slides.visible 	= _f.complNumber( this.opts.slides.visible, ( percWidth ) ? Math.floor( 100 / percWidth ) : 1 );
				this.opts.slides.width 		= ( percWidth ) ? percWidth : Math.ceil( 100 * 100 / this.opts.slides.visible ) / 100;
			}
			this.opts.slides.slide		=   _f.complNumber( this.opts.slides.slide, this.opts.slides.visible );
			this.opts.slides.offset 	= ( _f.isPercentage( this.opts.slides.offset ) ) ? _f.getPercentage( this.opts.slides.offset ) : _f.complNumber( this.opts.slides.offset, 0 );
		},
		
		_uniqueID: function()
		{
			if ( !this.__uniqueID )
			{
				this.__uniqueID = 0;
			}
			this.__uniqueID++;
			return _c( 'uid-' + this.__uniqueID );
		}
	};


	/*
		jQuery Plugin
	*/
	$.fn[ _PLUGIN_ ] = function( opts, optsD, optsT, conf )
	{
		//	First time plugin is fired
		if ( !_g.$wndw )
		{
			initPlugin();
		}

		//	Extend options
		opts = $.extend( true, {}, $[ _PLUGIN_ ].defaults, opts );
		opts = $.extend( true, {}, opts, $[ _PLUGIN_ ].support.touch ? optsT : optsD );

		//	Extend configuration
		conf = $.extend( true, {}, $[ _PLUGIN_ ].configuration, conf );

		var clss = new $[ _PLUGIN_ ]( this, opts, conf );

		this.data( _PLUGIN_, clss );
		return clss.nodes.$wrpr;
	};


	/*
		SUPPORT
	*/
	$[ _PLUGIN_ ].support = {
		touch: 'ontouchstart' in window.document || navigator.msMaxTouchPoints
	};


	/*
		Options
	*/
	$[ _PLUGIN_ ].defaults = {
//		show		: null,				//	true for inline slider, false for popup lightbox
		infinite	: false,
		effect		: 'slide',
		wrapper	: {
//			target	: null,				//	"window" for lightbox popup
			classes	: '',
			onClick	: 'toggleUI'		//	"toggleUI", "close" or null
		},
		slides	: {
//			slide	: null,				//	slides.visible
//			width	: null,				//	auto, max 100%
			offset	: 0,
			scale	: 'fit',			//	"fit" or "fill" (for images only)
			load	: 'near-visible',	//	"all", "visible" or "near-visible"
			visible	: 1
		},
		media	: {}
	};

	$[ _PLUGIN_ ].configuration = {
		transitionDuration: 400
	};
	
	$[ _PLUGIN_ ].constants = {};


	/*
		DEBUG
	*/
	$[ _PLUGIN_ ].debug = function( msg ) {};
	$[ _PLUGIN_ ].deprecated = function( depr, repl )
	{
		if ( typeof console != 'undefined' && typeof console.warn != 'undefined' )
		{
			console.warn( _PLUGIN_ + ': ' + depr + ' is deprecated, use ' + repl + ' instead.' );
		}
	};


	/*
		EFFECTS
	*/
	$[ _PLUGIN_ ].effects = {
		'slide': function( left )
		{
			this.nodes.$sldr.css( 'left', left );
		},
		'fade': function( left )
		{
			_f.transitionend( this.nodes.$sldr,
				function()
				{
					$(this)
						.css( 'left', left )
						.css( 'opacity', 1 );
				}, this.conf.transitionDuration
			);
			this.nodes.$sldr.css( 'opacity', 0 );
		}
	};


	$[ _PLUGIN_ ].version 	= _VERSION_;
	$[ _PLUGIN_ ].media		= {};
	$[ _PLUGIN_ ].addons 	= [];
	$[ _PLUGIN_ ].ui		= [];


	/*
		Private functions
	*/
	function initPlugin()
	{

		//	Classnames, Datanames, Eventnames
		_c = function( c ) { return _ABBR_ + '-' + c; };
		_d = function( d ) { return _ABBR_ + '-' + d; };
		_e = function( e ) { return e + '.' + _ABBR_; };

		$.each( [ _c, _d, _e ],
			function( i, o )
			{
				o.add = function( c )
				{
					c = c.split( ' ' );
					for ( var d in c )
					{
						o[ c[ d ] ] = o( c[ d ] );
					}
				};
			}
		);

		//	Classnames
		_c.add( 'touch desktop scale-1 scale-2 scale-3 wrapper opened opening fixed inline hover slider slide loading noanimation fastanimation' );

		//	Datanames
		_d.add( 'slide anchor' );

		//	Eventnames
		_e.add( 'open opening close closing prev next slideTo sliding click pinch scroll resize orientationchange load loading loaded transitionend webkitTransitionEnd' );

		//	Functions
		_f = {
			complObject: function( option, defaultVal )
			{
				if ( !$.isPlainObject( option ) )
				{
					option = defaultVal;
				}
				return option;
			},
			complBoolean: function( option, defaultVal )
			{
				if ( typeof option != 'boolean' )
				{
					option = defaultVal;
				}
				return option;
			},
			complNumber: function( option, defaultVal )
			{
				if ( !$.isNumeric( option ) )
				{
					option = defaultVal;
				}
				return option;
			},
			complString: function( option, defaultVal )
			{
				if ( typeof option != 'string' )
				{
					option = defaultVal;
				}
				return option;
			},
			isPercentage: function( value )
			{
				return ( typeof value == 'string' && value.slice( -1 ) == '%' );
				/*{
					value = parseInt( value.slice( 0, -1 ) );
				}
				return !isNaN( value );*/
			},
			getPercentage: function( value )
			{
				return parseInt( value.slice( 0, -1 ) );
			},
			resizeRatio: function( $i, $o, maxWidth, maxHeight, ratio )
			{
				if ( $o.is( ':visible' ) )
				{
					var _w = $o.width(),
						_h = $o.height();
			
					if ( maxWidth && _w > maxWidth )
					{
						_w = maxWidth;
					}
					if ( maxHeight && _h > maxHeight )
					{
						_h = maxHeight;
					}
			
					if ( _w / _h < ratio )
					{
						_h = _w / ratio;
					}
					else
					{
						_w = _h * ratio;
					}
					$i.width( _w ).height( _h );	
				}
			},
			transitionend: function( $e, fn, duration )
	        {
				var _ended = false,
					_fn = function()
					{
						if ( !_ended )
						{
							fn.call( $e[ 0 ] );
						}
						_ended = true;
					};

				$e.one( _e.transitionend, _fn );
				$e.one( _e.webkitTransitionEnd, _fn );
				setTimeout( _fn, duration * 1.1 );
	        },
	        setViewportScale: function()
	        {
	        	if ( _g.viewportScale )
				{
					var scale = _g.viewportScale.getScale();
					if ( typeof scale != 'undefined' )
					{
						scale = 1 / scale;
						_g.$body
							.removeClass( _c[ 'scale-1' ] )
							.removeClass( _c[ 'scale-2' ] )
							.removeClass( _c[ 'scale-3' ] )
							.addClass( _c[ 'scale-' + Math.max( Math.min( Math.round( scale ), 3 ), 1 ) ] );
					}
				}
	        }
		};

		// Global variables
		_g = {
			$wndw	: $(window),
			$html	: $('html'),
			$body	: $('body'),

			scrollPosition			: 0,
			viewportScale			: null,
			viewportScaleInterval	: null
		};


		//	Touch or desktop
		_g.$body.addClass( $[ _PLUGIN_ ].support.touch ? _c.touch : _c.desktop )

		//	Prevent scroling if opened
		_g.$wndw
			.on( _e.scroll,
				function( e )
				{
					if ( _g.$body.hasClass( _c.opened ) )
					{
						window.scrollTo( 0, _g.scrollPosition );
						e.preventDefault();
						e.stopPropagation();
						e.stopImmediatePropagation();
					}
				}
			);

		//	Invert viewport-scale
		if ( !_g.viewportScale && $[ _PLUGIN_ ].support.touch && typeof FlameViewportScale != 'undefined' )
		{
			_g.viewportScale = new FlameViewportScale();
			_f.setViewportScale();
			_g.$wndw
				.on( _e.orientationchange + ' ' + _e.resize,
					function( e )
					{
						if ( _g.viewportScaleInterval )
						{
							clearTimeout( _g.viewportScaleInterval );
							_g.viewportScaleInterval = null;
						}
						_g.viewportScaleInterval = setTimeout(
							function()
							{
								_f.setViewportScale();
							}, 500
						);
					}
				);
		}


		//	Add to plugin
		$[ _PLUGIN_ ]._c = _c;
		$[ _PLUGIN_ ]._d = _d;
		$[ _PLUGIN_ ]._e = _e;
		$[ _PLUGIN_ ]._f = _f;
		$[ _PLUGIN_ ]._g = _g;
	};

})( jQuery );

/*	
 *	jQuery Touch Optimized Sliders "R"Us
 *	Autoplay addon
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */
 
 (function( $ ) {
 
 	var _PLUGIN_ = 'tosrus',
		_ADDON_  = 'autoplay';

	var _addonInitiated = false,
		_c, _d, _e, _f, _g;

	$[ _PLUGIN_ ].prototype[ '_addon_' + _ADDON_ ] = function()
	{
		if ( !_addonInitiated )
		{
			_c = $[ _PLUGIN_ ]._c;
			_d = $[ _PLUGIN_ ]._d;
			_e = $[ _PLUGIN_ ]._e;
			_f = $[ _PLUGIN_ ]._f;
			_g = $[ _PLUGIN_ ]._g;

			_e.add( 'mouseover mouseout' );

			_addonInitiated = true;
		}

		var that = this,
			auto = this.opts[ _ADDON_ ];

		if ( auto.play )
		{
		
			this.opts.infinite = true;

			this.nodes.$wrpr
				.on( _e.sliding,
					function( e )
					{
						that.autoplay();
					}
				);

			if ( auto.pauseOnHover )
			{
				this.nodes.$wrpr
					.on( _e.mouseover,
						function( e )
						{
							that.autostop();
						}
					)
					.on( _e.mouseout,
						function( e )
						{
							that.autoplay();
						}
					);
			}

			this.autoplay();
		}
	};
	
	$[ _PLUGIN_ ].prototype.autoplay = function()
	{
		var that = this;

		this.autostop();
		this.vars.autoplay = setTimeout(
			function()
			{
				that.next();
			}, this.opts[ _ADDON_ ].timeout
		);
	};
	$[ _PLUGIN_ ].prototype.autostop = function()
	{
		if ( this.vars.autoplay )
		{
			clearTimeout( this.vars.autoplay );
		}
	};



	//	Defaults
	$[ _PLUGIN_ ].defaults[ _ADDON_ ] = {
		play			: false,
		timeout			: 4000,
		pauseOnHover	: false
	};

	//	Add to plugin
	$[ _PLUGIN_ ].addons.push( _ADDON_ );


})( jQuery );


/*	
 *	jQuery Touch Optimized Sliders "R"Us
 *	Buttons addon
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */
 
 (function( $ ) {
 
 	var _PLUGIN_ = 'tosrus',
		_ADDON_  = 'buttons';

	var _addonInitiated = false,
		_c, _d, _e, _f, _g;

	$[ _PLUGIN_ ].prototype[ '_addon_' + _ADDON_ ] = function()
	{
		if ( !_addonInitiated )
		{
			_c = $[ _PLUGIN_ ]._c;
			_d = $[ _PLUGIN_ ]._d;
			_e = $[ _PLUGIN_ ]._e;
			_f = $[ _PLUGIN_ ]._f;
			_g = $[ _PLUGIN_ ]._g;

			_c.add( 'prev next close disabled' );

			_addonInitiated = true;
		}

		var that = this,
			btns = this.opts[ _ADDON_ ];

		this.nodes.$prev = null;
		this.nodes.$next = null;
		this.nodes.$clse = null;

		if ( typeof btns == 'boolean' || ( typeof btns == 'string' && btns == 'inline' ) )
		{
			btns = {
				prev: btns,
				next: btns
			};
		}
		if ( typeof btns.close == 'undefined' )
		{
 			btns.close = this.vars.fixed;
		}
		if ( this.nodes.$slides.length < 2 )
		{
			btns.prev = false;
			btns.next = false;
		}

		$.each(
			{
				'prev'	: 'prev',
				'next'	: 'next',
				'close'	: 'clse'
			},
			function( btn, value )
			{
				if ( btns[ btn ] )
				{
					//	Inline buttons
					if ( typeof btns[ btn ] == 'string' && btns[ btn ] == 'inline' )
					{
						if ( that.vars.fixed && btn != 'close' )
						{
							that.nodes.$slides
								.on( _e.loading,
									function( e, $anchor )
									{
										var $btn = createButton( btn, ' ' + _c.inline )[ btn == 'prev' ? 'prependTo' : 'appendTo' ]( this );
										bindEvent( that.nodes.$wrpr, $btn, btn, 1 );
										
										if ( !that.opts.infinite )
										{
											if (( btn == 'prev' && $(this).is( ':first-child' ) ) ||
												( btn == 'next' && $(this).is( ':last-child' ) ) )
											{
												$btn.addClass( _c.disabled );
											}
										}
									}
								);
						}
					}

					//	External buttons
					else
					{
						if ( typeof btns[ btn ] == 'string' )
						{
							btns[ btn ] = $(btns[ btn ]);
						}
						that.nodes[ '$' + value ] = ( btns[ btn ] instanceof $ )
							? btns[ btn ]
							: createButton( btn, '' ).appendTo( that.nodes.$wrpr );

						bindEvent( that.nodes.$wrpr, that.nodes[ '$' + value ], btn, null );
					}
				}
			}
		);

		if ( !this.opts.infinite )
		{
			this.updateButtons();
			this.nodes.$wrpr
				.on( _e.sliding,
					function( e, slide, direct )
					{
						that.updateButtons();
					}
				);
		}
	};

	function createButton( dir, cls )
	{
		return $('<a class="' + _c[ dir ] + '' + cls + '" href="#"><span></span></a>');
	}
	function bindEvent( $wrpr, $btn, dir, slides )
	{
		$btn
			.on( _e.click,
				function( e )
				{
					e.preventDefault();
					e.stopPropagation();
					$wrpr.trigger( _e[ dir ], [ slides ] );
				}
			);
	}

	$[ _PLUGIN_ ].prototype.updateButtons = function()
	{
		if ( this.nodes.$prev )
		{
			this.nodes.$prev[ ( ( this.slides.index < 1 ) ? 'add' : 'remove' ) + 'Class' ]( _c.disabled );
		}
		if ( this.nodes.$next )
		{
			this.nodes.$next[ ( ( this.slides.index >= this.slides.total - this.slides.visible ) ? 'add' : 'remove' ) + 'Class' ]( _c.disabled );
		}
	};

	//	Defaults
	$[ _PLUGIN_ ].defaults[ _ADDON_ ] = {
		prev	: !$[ _PLUGIN_ ].support.touch,
		next	: !$[ _PLUGIN_ ].support.touch
	};

	//	Add to plugin
	$[ _PLUGIN_ ].addons.push( _ADDON_ );
	$[ _PLUGIN_ ].ui.push( 'prev' );
	$[ _PLUGIN_ ].ui.push( 'next' );
	$[ _PLUGIN_ ].ui.push( 'close' );


})( jQuery );
/*	
 *	jQuery Touch Optimized Sliders "R"Us
 *	Caption addon
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */
 
 (function( $ ) {
 
 	var _PLUGIN_ = 'tosrus',
		_ADDON_  = 'caption';

	var _addonInitiated = false,
		_c, _d, _e, _f, _g;

	$[ _PLUGIN_ ].prototype[ '_addon_' + _ADDON_ ] = function()
	{		
		if ( !_addonInitiated )
		{
			_c = $[ _PLUGIN_ ]._c;
			_d = $[ _PLUGIN_ ]._d;
			_e = $[ _PLUGIN_ ]._e;
			_f = $[ _PLUGIN_ ]._f;
			_g = $[ _PLUGIN_ ]._g;

			_c.add( 'caption uibg' );
			_d.add( 'caption' );

			_addonInitiated = true;
		}

		var that = this,
			capt = this.opts[ _ADDON_ ];


		if ( capt.add )
		{

			capt.attributes = capt.attributes || [];

			if ( typeof capt.target == 'string' )
			{
				capt.target = $(capt.target);
			}
			if ( capt.target instanceof $ )
			{
				this.nodes.$capt = capt.target;
			}
			else
			{
				this.nodes.$capt = $('<div class="' + _c.caption + '" />').appendTo( this.nodes.$wrpr );
				if ( !this.nodes.$uibg )
				{
					this.nodes.$uibg = $('<div class="' + _c.uibg + '" />').prependTo( this.nodes.$wrpr );
				}
			}
			for ( var c = 0, l = this.slides.visible; c < l; c++ )
			{
				$('<div class="' + _c.caption + '-' + c + '" />')
					.css( 'width', this.opts.slides.width + ( ( this.slides.widthPercentage ) ? '%' : 'px' ) )
					.appendTo( this.nodes.$capt );
			}

			this.nodes.$slides
				.each(
					function( index )
					{
						var $slide = $(this),
							$anchor = ( that.vars.fixed )
								? $slide.data( _d.anchor )
								: $slide.children();

						$slide.data( _d.caption, '' );
						for ( var c = 0, l = capt.attributes.length; c < l; c++ )
						{
							var caption = $anchor.attr( capt.attributes[ c ] );
							if ( caption && caption.length )
							{
								$slide.data( _d.caption, caption );
								break;
							}
						}
					}
				);

			this.nodes.$wrpr
				.on( _e.sliding,
					function( e, slide, direct )
					{
						var show = false;
						for ( var c = 0, l = that.slides.visible; c < l; c++ )
						{
							that.nodes.$capt
								.children()
								.eq( c )
								.html( that.nodes.$sldr.children().eq( that.slides.index + c ).data( _d.caption ) || '' );
						}						
					}
				);
		}
	};

	//	Defaults
	$[ _PLUGIN_ ].defaults[ _ADDON_ ] = {
		add			: false,
		target		: null,
		attributes	: [ 'title', 'alt', 'rel' ]
	};

	//	Add to plugin
	$[ _PLUGIN_ ].addons.push( _ADDON_ );
	$[ _PLUGIN_ ].ui.push( 'caption' );


})( jQuery );
/*	
 *	jQuery Touch Optimized Sliders "R"Us
 *	Drag addon
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */

(function( $ ) {

	if ( typeof Hammer != 'function' )
	{
		return;
	}

	var _PLUGIN_ = 'tosrus',
		_ADDON_  = 'drag';

	var _addonInitiated = false,
		_c, _d, _e, _f, _g;

	$[ _PLUGIN_ ].prototype[ '_addon_' + _ADDON_ ] = function()
	{
		if ( !_addonInitiated )
		{
			_c = $[ _PLUGIN_ ]._c;
			_d = $[ _PLUGIN_ ]._d;
			_e = $[ _PLUGIN_ ]._e;
			_f = $[ _PLUGIN_ ]._f;
			_g = $[ _PLUGIN_ ]._g;

			_addonInitiated = true;
		}

		var that = this;

		if ( this.opts[ _ADDON_ ] && this.opts.effect == 'slide' )
		{
			if ( Hammer.VERSION < 2 )
			{
				$[ _PLUGIN_ ].deprecated( 'Older version of the Hammer library', 'version 2 or newer' );
				return;
			}

			if ( this.nodes.$slides.length > 1 )
			{
				var _distance 	= 0,
					_direction	= false,
					_swiping	= false;
	
				var _hammer = new Hammer( this.nodes.$wrpr[ 0 ] );

				_hammer
					.on( 'panstart panleft panright panend swipeleft swiperight',
						function( e )
						{
							e.preventDefault();
						}
					)
					.on( 'panstart',
						function( e )
						{
		            		that.nodes.$sldr.addClass( _c.noanimation );
						}
					)
					.on( 'panleft panright',
						function( e )
						{
							_distance	= e.deltaX;
							_swiping	= false;

							switch( e.direction )
							{
								case 2:
									_direction = 'left';
									break;
								
								case 4:
									_direction = 'right';
									break;
								
								default:
									_direction = false;
									break;
							}
		
							if ( ( _direction == 'left' && that.slides.index + that.slides.visible >= that.slides.total  ) ||
								( _direction == 'right' && that.slides.index == 0 ) )
							{
								_distance /= 2.5;
							}
	
							that.nodes.$sldr.css( 'margin-left', Math.round( _distance ) );
						}
					)
					.on( 'swipeleft swiperight',
						function( e )
						{
							_swiping = true;
						}
					)
					.on( 'panend',
						function( e )
						{
							that.nodes.$sldr
								.removeClass( _c.noanimation )
								.addClass( _c.fastanimation );

							_f.transitionend( that.nodes.$sldr,
								function()
								{
									that.nodes.$sldr.removeClass( _c.fastanimation );
								}, that.conf.transitionDuration / 2
							);
	
							that.nodes.$sldr.css( 'margin-left', 0 );
	
							if ( _direction == 'left' || _direction == 'right' )
							{
								if ( _swiping )
								{
									var slides = that.slides.visible;
								}
								else
								{
									var slideWidth = that.nodes.$slides.first().width(),
										slides = Math.floor( ( Math.abs( _distance ) + ( slideWidth / 2 ) ) / slideWidth );	
								}
		
								if ( slides > 0 )
								{
									that.nodes.$wrpr.trigger( _e[ _direction == 'left' ? 'next' : 'prev' ], [ slides ] );
								}
							}
	
							_direction = false;
						}
					);
			}
		}

	};

	//	Defautls
	$[ _PLUGIN_ ].defaults[ _ADDON_ ] = $[ _PLUGIN_ ].support.touch;

	//	Add to plugin
	$[ _PLUGIN_ ].addons.push( _ADDON_ );


})( jQuery );
/*	
 *	jQuery Touch Optimized Sliders "R"Us
 *	Keys addon
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */
 
 (function( $ ) {
 
 	var _PLUGIN_ = 'tosrus',
		_ADDON_  = 'keys';

	var _addonInitiated = false,
		_c, _d, _e, _f, _g;

	$[ _PLUGIN_ ].prototype[ '_addon_' + _ADDON_ ] = function()
	{		
		if ( !_addonInitiated )
		{
			_c = $[ _PLUGIN_ ]._c;
			_d = $[ _PLUGIN_ ]._d;
			_e = $[ _PLUGIN_ ]._e;
			_f = $[ _PLUGIN_ ]._f;
			_g = $[ _PLUGIN_ ]._g;

			_e.add( 'keyup' );

			_addonInitiated = true;
		}

		var that = this,
			keys = this.opts[ _ADDON_ ];

		if ( typeof keys == 'boolean' && keys )
		{
			keys = {
				prev	: true,
				next	: true,
				close	: true
			};
		}
		if ( $.isPlainObject( keys) )
		{
			for ( var k in $[ _PLUGIN_ ].constants[ _ADDON_ ] )
			{
				if ( typeof keys[ k ] == 'boolean' && keys[ k ] )
				{
					keys[ k ] = $[ _PLUGIN_ ].constants[ _ADDON_ ][ k ];
				}
			}

			if ( this.nodes.$slides.length < 2 )
			{
				keys.prev = false;
				keys.next = false;
			}

			$(document)
				.on( _e.keyup,
					function( e )
					{
						if ( that.vars.opened )
						{
							var fn = false;
							switch( e.keyCode )
							{
								case keys.prev:
									fn = _e.prev;
									break;
	
								case keys.next:
									fn = _e.next;
									break;
	
								case keys.close:
									fn = _e.close;
									break;
							}
							if ( fn )
							{
								e.preventDefault();
								e.stopPropagation();
								that.nodes.$wrpr.trigger( fn );
							}
						}
					}
				);
		}

	};

	//	Defaults
	$[ _PLUGIN_ ].defaults[ _ADDON_ ] = false;

	$[ _PLUGIN_ ].constants[ _ADDON_ ] = {
		prev	: 37,
		next	: 39,
		close	: 27
	};

	//	Add to plugin
	$[ _PLUGIN_ ].addons.push( _ADDON_ );


})( jQuery );
/*	
 *	jQuery Touch Optimized Sliders "R"Us
 *	Pagination addon
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */

 (function( $ ) {
 
 	var _PLUGIN_ = 'tosrus',
		_ADDON_  = 'pagination';

	var _addonInitiated = false,
		_c, _d, _e, _f, _g;

	$[ _PLUGIN_ ].prototype[ '_addon_' + _ADDON_ ] = function()
	{		
		if ( !_addonInitiated )
		{
			_c = $[ _PLUGIN_ ]._c;
			_d = $[ _PLUGIN_ ]._d;
			_e = $[ _PLUGIN_ ]._e;
			_f = $[ _PLUGIN_ ]._f;
			_g = $[ _PLUGIN_ ]._g;

			_c.add( 'pagination selected uibg bullets thumbnails' );

			_addonInitiated = true;
		}

		var that = this,
			pagr = this.opts[ _ADDON_ ];


		if ( this.nodes.$slides.length < 2 )
		{
			pagr.add = false;
		}

		if ( pagr.add )
		{
			if ( typeof pagr.target == 'string' )
			{
				pagr.target = $(pagr.target);
			}
			if ( pagr.target instanceof $ )
			{
				this.nodes.$pagr = pagr.target;
			}
			else
			{
				this.nodes.$pagr = $('<div class="' + _c.pagination + ' ' + _c[ pagr.type ] + '" />').appendTo( this.nodes.$wrpr );
				if ( !this.nodes.$uibg )
				{
					this.nodes.$uibg = $('<div class="' + _c.uibg + '" />').prependTo( this.nodes.$wrpr );
				}
			}

			if ( typeof pagr.anchorBuilder != 'function' )
			{
				switch( pagr.type )
				{
					case 'thumbnails':
						var pre 	= '<a href="#" style="background-image: url(\'',
							post	= '\');"></a>';

						if ( this.vars.fixed )
						{
							pagr.anchorBuilder = function( index )
							{
								return pre + $(this).data( _d.anchor ).attr( 'href' ) + post;
							};
						}
						else
						{
							pagr.anchorBuilder = function( index )
							{
								return pre + $(this).find( 'img' ).attr( 'src' ) + post;
							};
						}
						break;

					case 'bullets':
					default:
						pagr.anchorBuilder = function( index )
						{
							return '<a href="#"></a>';
						};
						break;
				}
			}

			this.nodes.$slides
				.each(
					function( index )
					{
						$(pagr.anchorBuilder.call( this, index + 1 ) )
							.appendTo( that.nodes.$pagr )
							.on( _e.click,
								function( e )
								{
									e.preventDefault();
									e.stopPropagation();

									that.nodes.$wrpr.trigger( _e.slideTo, [ index ] );
								}
							);
					}
				);
			
			this.updatePagination();
			this.nodes.$wrpr
				.on( _e.sliding,
					function( e, slide, direct )
					{
						that.updatePagination();
					}
				);
		}
	};
	
	$[ _PLUGIN_ ].prototype.updatePagination = function()
	{
		if ( this.nodes.$pagr )
		{
			this.nodes.$pagr
				.children()
				.removeClass( _c.selected )
				.eq( this.slides.index )
				.addClass( _c.selected );
		}
	};

	//	Defaults
	$[ _PLUGIN_ ].defaults[ _ADDON_ ] = {
		add				: false,
		type			: 'bullets',
		target			: null,
		anchorBuilder	: null
	};

	//	Add to plugin
	$[ _PLUGIN_ ].addons.push( _ADDON_ );
	$[ _PLUGIN_ ].ui.push( 'pagination' );
	$[ _PLUGIN_ ].ui.push( 'bullets' );
	$[ _PLUGIN_ ].ui.push( 'thumbnails' );


})( jQuery );
/*	
 * jQuery Touch Optimized Sliders "R"Us
 * HTML media
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */

(function( $ ) {
	
	var _PLUGIN_ = 'tosrus',
		_MEDIA_	 = 'html';

	$[ _PLUGIN_ ].media[ _MEDIA_ ] = {

		//	Filter anchors
		filterAnchors: function( $anchor )
		{
			var href = $anchor.attr( 'href' );
			return ( href.slice( 0, 1 ) == '#' && $(href).is( 'div' ) )
		},

		//	Create Slides from anchors
		initAnchors: function( $slide, href )
		{
			$('<div class="' + $[ _PLUGIN_ ]._c( 'html' ) + '" />')
				.append( $(href) )
				.appendTo( $slide );

			$slide.removeClass( $[ _PLUGIN_ ]._c.loading )
				.trigger( $[ _PLUGIN_ ]._e.loaded );
		},

		//	Filter slides
		filterSlides: function( $slide )
		{
			return $slide.is( 'div' );
		},

		//	Create slides from existing content
		initSlides: function( $slide ) {}
	};
	
	$[ _PLUGIN_ ].defaults.media[ _MEDIA_ ] = {};
	
})( jQuery );
/*	
 * jQuery Touch Optimized Sliders "R"Us
 * Images media
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */

(function( $ ) {
	
	var _PLUGIN_ = 'tosrus',
		_MEDIA_	 = 'image';

	$[ _PLUGIN_ ].media[ _MEDIA_ ] = {

		//	Filter anchors
		filterAnchors: function( $anchor )
		{
			return ( $.inArray( $anchor.attr( 'href' ).toLowerCase().split( '.' ).pop().split( '?' )[ 0 ], [ 'jpg', 'jpe', 'jpeg', 'gif', 'png' ] ) > -1 );
		},
		
		//	Create Slides from anchors
		initAnchors: function( $slide, href )
		{
			$('<img border="0" />')
				.on( $[ _PLUGIN_ ]._e.load,
					function( e )
					{
						e.stopPropagation();
						$slide.removeClass( $[ _PLUGIN_ ]._c.loading )
							.trigger( $[ _PLUGIN_ ]._e.loaded );
					}
				)
				.appendTo( $slide )
				.attr( 'src', href );
		},

		//	Filter slides
		filterSlides: function( $slide )
		{
			return $slide.is( 'img' );
		},

		//	Create slides from existing content
		initSlides: function( $slide ) {}
	};
	
	$[ _PLUGIN_ ].defaults.media[ _MEDIA_ ] = {};
	
})( jQuery );
/*	
 * jQuery Touch Optimized Sliders "R"Us
 * Vimeo media
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */

(function( $ ) {
	
	var _PLUGIN_ = 'tosrus',
		_MEDIA_	 = 'vimeo';

	var _mediaInitiated = false,
		_c, _d, _e, _f, _g;

	$[ _PLUGIN_ ].media[ _MEDIA_ ] = {

		//	Filter anchors
		filterAnchors: function( $anchor )
		{
			return ( $anchor.attr( 'href' ).toLowerCase().indexOf( 'vimeo.com/' ) > -1 );
		},
	
		//	Create Slides from anchors
		initAnchors: function( $slide, href )
		{
			var id = this._uniqueID();
			href = href.split( 'vimeo.com/' )[ 1 ].split( '?' )[ 0 ] + '?api=1&player_id=' + id;
			$('<iframe id="' + id + '" src="https://player.vimeo.com/video/' + href + '" frameborder="0" allowfullscreen />')
				.appendTo( $slide );

			initVideo.call( this, $slide );
		},

		//	Filter slides
		filterSlides: function( $slide )
		{
			if ( $slide.is( 'iframe' ) && $slide.attr( 'src' ) )
			{			
				return ( $slide.attr( 'src' ).toLowerCase().indexOf( 'vimeo.com/video/' ) > -1 );
			}
			return false;
		},

		//	Create slides from existing content
		initSlides: function( $slide )
		{
			initVideo.call( this, $slide );
		}
	};
	
	$[ _PLUGIN_ ].defaults.media[ _MEDIA_ ] = {};


	//	Functions
	function initVideo( $s )
	{
		if ( !_mediaInitiated )
		{
			_c = $[ _PLUGIN_ ]._c;
			_d = $[ _PLUGIN_ ]._d;
			_e = $[ _PLUGIN_ ]._e;
			_f = $[ _PLUGIN_ ]._f;
			_g = $[ _PLUGIN_ ]._g;

			_d.add( 'ratio maxWidth maxHeight' );

			_mediaInitiated = true;
		}

		var that = this;

		var $v = $s.children(),
			$a = $s.data( $[ _PLUGIN_ ]._d.anchor ) || $();

		var src = $v.attr( 'src' );

		var ratio 		= $a.data( _d.ratio ) 		|| this.opts[ _MEDIA_ ].ratio,
			maxWidth 	= $a.data( _d.maxWidth ) 	|| this.opts[ _MEDIA_ ].maxWidth,
			maxHeight	= $a.data( _d.maxHeight )	|| this.opts[ _MEDIA_ ].maxHeight;

		$s.removeClass( _c.loading )
			.trigger( _e.loaded )
			.on( _e.loading,
				function( e )
				{
					_f.resizeRatio( $v, $s, maxWidth, maxHeight, ratio );
				}
			);

		this.nodes.$wrpr
			.on( _e.sliding,
				function( e )
				{
				//	commandVideo( 'unload' );
					unloadVideo();
				}
			)
			.on( _e.opening,
			    function( e )
			    {
			        _f.resizeRatio( $v, $s, maxWidth, maxHeight, ratio );
			    }
			)
			.on( _e.closing,
				function( e )
				{
				//	commandVideo( 'pause' );
					unloadVideo();
				}
			);


		_g.$wndw
			.on( _e.resize,
				function( e )
				{
					_f.resizeRatio( $v, $s, maxWidth, maxHeight, ratio );
				}
			);

		function unloadVideo()
		{
			if ( $v.length )
			{
				$v.attr( 'src', '' );
				$v.attr( 'src', src );
			}
		}
		//	Can't get this to work anymore...
		function commandVideo( fn )
		{
			if ( $v.length )
			{
				$v[ 0 ].contentWindow.postMessage( '{ "method": "' + fn + '" }', '*' );
			}
		}
	}


	//	Defaults
	$[ _PLUGIN_ ].defaults[ _MEDIA_ ] = {
		ratio		: 16 / 9,
		maxWidth	: false,
		maxHeight	: false
	};

	
})( jQuery );
/*	
 * jQuery Touch Optimized Sliders "R"Us
 * Youtube media
 *
 *	Copyright (c) Fred Heusschen
 *	www.frebsite.nl
 */

(function( $ ) {
	
	var _PLUGIN_ = 'tosrus',
		_MEDIA_	 = 'youtube';

	var _mediaInitiated = false,
		_c, _d, _e, _f, _g;

	$[ _PLUGIN_ ].media[ _MEDIA_ ] = {

		//	Filter anchors
		filterAnchors: function( $anchor )
		{
			return ( $anchor.attr( 'href' ).toLowerCase().indexOf( 'youtube.com/watch?v=' ) > -1 );
		},
		
		//	Create Slides from anchors
		initAnchors: function( $slide, href )
		{
			var url = href;
			href = href.split( '?v=' )[ 1 ].split( '&' )[ 0 ];

			if ( this.opts[ _MEDIA_ ].imageLink )
			{
				var proto = window.location.protocol === 'https:' ? 'https:' : 'http:';
				href = proto + '//img.youtube.com/vi/' + href + '/0.jpg';
				$('<a href="' + url + '" class="' + $[ _PLUGIN_ ]._c( 'play' ) + '" target="_blank" />')
					.appendTo( $slide );

				$('<img border="0" />')
					.on( $[ _PLUGIN_ ]._e.load,
						function( e )
						{
							e.stopPropagation();
							$slide.removeClass( $[ _PLUGIN_ ]._c.loading )
								.trigger( $[ _PLUGIN_ ]._e.loaded );
						}
					)
					.appendTo( $slide )
					.attr( 'src', href );
			}
			else
			{
				$('<iframe src="https://www.youtube.com/embed/' + href + '?enablejsapi=1" frameborder="0" allowfullscreen />')
					.appendTo( $slide );

				initVideo.call( this, $slide );
			}
		},

		//	Filter slides
		filterSlides: function( $slide )
		{
			if ( $slide.is( 'iframe' ) && $slide.attr( 'src' ) )
			{
				return ( $slide.attr( 'src' ).toLowerCase().indexOf( 'youtube.com/embed/' ) > -1 );
			}
			return false;
		},

		//	Create slides from existing content
		initSlides: function( $slide )
		{
			initVideo.call( this, $slide );
		}
	};
	
	$[ _PLUGIN_ ].defaults.media[ _MEDIA_ ] = {};


	//	Functions
	function initVideo( $s )
	{
		if ( !_mediaInitiated )
		{
			_c = $[ _PLUGIN_ ]._c;
			_d = $[ _PLUGIN_ ]._d;
			_e = $[ _PLUGIN_ ]._e;
			_f = $[ _PLUGIN_ ]._f;
			_g = $[ _PLUGIN_ ]._g;

			_d.add( 'ratio maxWidth maxHeight' );

			_mediaInitiated = true;
		}

		var that = this;

		var $v = $s.children(),
			$a = $s.data( $[ _PLUGIN_ ]._d.anchor ) || $();

		var ratio 		= $a.data( _d.ratio ) 		|| this.opts[ _MEDIA_ ].ratio,
			maxWidth 	= $a.data( _d.maxWidth ) 	|| this.opts[ _MEDIA_ ].maxWidth,
			maxHeight	= $a.data( _d.maxHeight )	|| this.opts[ _MEDIA_ ].maxHeight;

		$s.removeClass( _c.loading )
			.trigger( _e.loaded )
			.on( _e.loading,
				function( e )
				{
					_f.resizeRatio( $v, $s, maxWidth, maxHeight, ratio );
				}
			);

		this.nodes.$wrpr
			.on( _e.sliding,
				function( e )
				{
					commandVideo( 'pause' );
				}
			)
			.on( _e.opening,
			    function( e )
			    {
			        _f.resizeRatio( $v, $s, maxWidth, maxHeight, ratio );
			    }
			)
			.on( _e.closing,
				function( e )
				{
					commandVideo( 'stop' );
				}
			);

		_g.$wndw
			.on( _e.resize,
				function( e )
				{
					_f.resizeRatio( $v, $s, maxWidth, maxHeight, ratio );
				}
			);


		function resizeVideo()
		{
			var _w = $s.width(),
				_h = $s.height();

			if ( maxWidth && _w > maxWidth )
			{
				_w = maxWidth;
			}
			if ( maxHeight && _h > maxHeight )
			{
				_h = maxHeight;
			}
	
			if ( _w / _h < ratio )
			{
				_h = _w / ratio;
			}
			else
			{
				_w = _h * ratio;
			}

			$v.width( _w ).height( _h );
		}
		
		function commandVideo( fn )
		{
			if ( $v.length )
			{
				$v[ 0 ].contentWindow.postMessage( '{ "event": "command", "func": "' + fn + 'Video" }', '*' );
			}
		}
	}


	//	Defaults
	$[ _PLUGIN_ ].defaults[ _MEDIA_ ] = {
		ratio		: 16 / 9,
		maxWidth	: false,
		maxHeight	: false,
		imageLink	: $[ _PLUGIN_ ].support.touch
	};

	
})( jQuery );