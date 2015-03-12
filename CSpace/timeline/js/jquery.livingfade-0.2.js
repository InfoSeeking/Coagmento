// jQuery Plugin LivingFade
// A plugin to fade in or out a selection of elements in a living way (randomly delayed and with random speeds)
// Version 0.2 - 24. 7. 2011
// by Fredi Bach

(function($) {

    $.livingFade = function(element, options) {

        var defaults = {
			maxDelay: 1000,
			minSpeed: 500,
			maxSpeed: 250,
			fadeTo: 0,
			affected: '.fademe',
			onFinish: function() {}
        }
		
        var plugin = this;

        plugin.settings = {}
		
        var $element = $(element),
             element = element;
		
        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
			
			var cnt = 0;
			
			$element.children(plugin.settings.affected).each( function(){
				var delay = Math.ceil( Math.random() * plugin.settings.maxDelay );
				var speed = plugin.settings.maxSpeed + Math.ceil( Math.random() * ( plugin.settings.minSpeed - plugin.settings.maxSpeed ) );
				
				cnt++;
				
				$(this).delay(delay).fadeTo(speed, plugin.settings.fadeTo, function(){
					
					cnt--;
					
					if (cnt == 0){
						plugin.settings.onFinish();
					}
				});
			});
        }
		
        plugin.init();

    }

    $.fn.livingFade = function(options) {

        return this.each(function() {
            var plugin = new $.livingFade(this, options);
			$(this).data('livingFade', plugin);
        });

    }

})(jQuery);