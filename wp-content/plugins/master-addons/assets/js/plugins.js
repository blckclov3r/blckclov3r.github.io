/* 
 * ================== js/jquery.appearl.js =================== 
 */ 

;( function( $, window, document, undefined ) {

  "use strict";

  var pluginName = "appearl",
      defaults = {
        offset: 0,
        insetOffset: '50%'
      },
      attributesMap = {
        'offset': 'offset',
        'inset-offset': 'insetOffset'
    },
    $window = $(window);

  // The actual plugin constructor
  function Plugin ( element, options ) {
      this.element   = element;
      this.$element  = $(element);
      this.settings  = $.extend( {}, defaults, options );

      // read attributes
      for ( var key in attributesMap ) {
        var value = attributesMap[ key ],
            dataAttr = this.$element.data( key );

        if ( dataAttr === undefined ) {
            continue;
        }

        this.settings[ value ] = dataAttr;
      }

      this.init();
  }

  // Avoid Plugin.prototype conflicts
  $.extend( Plugin.prototype, {
      init: function() {
        if ( typeof this.settings.offset === 'object' ) {
          this._offsetTop = this.settings.offset.top;
          this._offsetBottom = this.settings.offset.bottom;
        } else {
          this._offsetTop = this._offsetBottom = this.settings.offset;
        }

        // To check if the element is on viewport and set the offset 0 for them
        if ( this._isOnViewPort( this.$element) ) {
            this._offsetTop = this._offsetBottom = 0
        }

        this._appeared = false;
        this._lastScroll = 0;

        $window.on( 'scroll resize', this.update.bind( this ) );
        setTimeout( this.update.bind(this) );
      },

      update: function( event ) {
        var rect = this.element.getBoundingClientRect(),
        areaTop = this._parseOffset( this._offsetTop ),
        areaBottom = window.innerHeight - this._parseOffset( this._offsetBottom ),
        insetOffset = this._parseOffset( this.settings.insetOffset, true );

        if ( rect.top + insetOffset <= areaBottom && rect.bottom - insetOffset >= areaTop ) {
          !this._appeared && this.$element.trigger( 'appear', [{ from: ( this._lastScroll <= $window.scrollTop() ? 'bottom' : 'top' ) }] );
          this._appeared = true;
        } else if ( this._appeared ) {
          this.$element.trigger( 'disappear', [{ from: ( rect.top < areaTop ? 'top' : 'bottom' ) }] );
          this._appeared = false;
        }

        this._lastScroll = $window.scrollTop();
      },

      _parseOffset: function( value, inset ) {
        var percentage = typeof value === 'string' && value.indexOf( '%' ) !== -1;
        value = parseInt( value );

        return !percentage ? value : ( inset ? this.element.offsetHeight : window.innerHeight ) * value / 100;
      },

      _isOnViewPort: function( element ) {
        var bottomOffset = this.element.getBoundingClientRect().bottom;
        return bottomOffset <  window.innerHeight
      },
  } );

  $.fn[ pluginName ] = function( options ) {
      return this.each( function() {
          if ( !$.data( this, "plugin_" + pluginName ) ) {
              $.data( this, "plugin_" +
                  pluginName, new Plugin( this, options ) );
          }
      } );
  };

} )( jQuery, window, document );
