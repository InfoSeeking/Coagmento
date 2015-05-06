(function($) {

	$.widget("ui.betterflow", {
		options: {
			childrenSelector: "> *",
			position: 1,
			scaleFactor: 10,
			marginFactor: 3
		},

		_create: function() {
			this.position = this.options.position;
			this.flipped  = false;

			var initial = $(this.options.childrenSelector + ":nth-child(" + this.position + ")", this.element);

			this.size = {
				width:  initial.width(),
				height: initial.height()
			};

			this._bind();
			this._select(this.position);
		},

		_render: function() {
			var that = this;
			$(this.options.childrenSelector, this.element).each(function(i) {
				that._renderEach(i+1);
			});
		},

		_renderEach: function(i) {
			var diff = Math.abs(this.position - i);
			var diffScale = diff / this.options.scaleFactor;

			var newscale  = (1 - diffScale);
			var newsize   = this.size.width * newscale;
			var newmargin = this.size.width * (diff / (this.options.scaleFactor * 2));

			var factor = 0.5 * diff * (2 * newscale + (diff - 1) / this.options.scaleFactor); // magic
			var pos    = this.size.width * factor;
			var anchor = ((this.element.width() - this.size.width) * 0.5);
			var left   = i < this.position ? anchor - pos : anchor + pos;
			var mfixed = i < this.position ? newmargin : -newmargin;

			$(this.options.childrenSelector + ":nth-child(" + i + ")", this.element)
				.css("z-index", 100-diff)
				.stop()
				.animate({
					"left": left,
					"width": newsize,
					"height": newsize,
					"margin-top": newmargin,
					"margin-left": mfixed * this.options.marginFactor,
					"opacity": newscale
				});
		},

		_bind: function() {
			var that = this;

			$("li:not(.betterflow-selected)", this.element).live("click", function(e) {
				that._select($(that.options.childrenSelector, that.element).index(e.currentTarget) + 1);
				return false;
			});

			this.element.bind("betterflow-flip",   function(e)    { that._flip(!that.flipped); });
			this.element.bind("betterflow-select", function(e, i) { that._select(i);           });
			this.element.bind("betterflow-prev",   function(e)    { that._prev();              });
			this.element.bind("betterflow-next",   function(e)    { that._next();              });

			this.element.bind("mousewheel", function(e, delta, deltaX, deltaY) {
				if (deltaX == 0) return;

				that[deltaX > 0 ? "_next" : "_prev"]();
			});

			$(window).bind("keydown", function(e) {
				switch (e.which) {
					case 37:
						that._prev();
						break;

					case 39:
						that._next();
						break;

					default:
						break;
				}
			});
		},

		_flip: function(state) {
			$(this.options.childrenSelector + ".betterflow-selected > *", this.element).toggleClass("betterflow-flipped", state);
			this.flipped = state;
		},

		_select: function(i) {
			this._flip(false);
			$(this.options.childrenSelector + ".betterflow-selected", this.element).removeClass("betterflow-selected");
			$(this.options.childrenSelector + ":nth-child(" + i + ")", this.element).addClass("betterflow-selected");
			this.position = i;

			this._render();

			this.element.trigger("betterflow-selected", [i]);
		},

		_next: function() {
			if ($(this.options.childrenSelector, this.element).size() > this.position)
				this._select(this.position + 1);
		},

		_prev: function() {
			if (this.position > 1)
				this._select(this.position - 1);
		}
	});

})(jQuery);