(function($) {
	$.fn.threeD = function(fn) {
		var root = $(this);
		if (fn == 'init') init();
		else if (fn == 'destroy') destroy();
		else throw fn + ' is not an available function';

		function onSlideClick(e) {
			select(e.target);
		}

		function onKeyDown(e) {
			if(e.which == 39) change(1);
			else if (e.which == 37) change(-1);
		}

		function selectDefault() {
			// TODO: is there an easy way to select the slide which is nearest to the front
			// so we can show the next slide after removal of the current slide?
			return root.find('.three-d').first().addClass('selected');
		}

		function transition() {
			var slides = root.find('.three-d');
			var selected = slides.filter('.selected');
			// If nothings selected, select the first by default.
			if (selected.size() == 0) selected = selectDefault();
			var index = slides.index(selected);
			var zIndex = slides.size() + 1;
			slides.each(function(i, el){
				var zPos = (index - i) * 1000;
				var xPos = (index - i) * 100;
				var yPos = (index - i) * -100;
				$(el).css({
					'transform': 'translateZ(' + zPos + 'px) translateX(' + xPos + 'px) translateY(' + yPos + 'px)',
					'z-index': zIndex--
				});
			});
		}

		function change(delta) {
			var slides = root.find('.three-d');
			var selected = slides.filter('.selected');
			if (selected.size() == 0) selected = selectDefault();
			var index = slides.index(selected);
			var newSelectedIndex = (index + delta % slides.size());
			// If newSelectedIndex is negative, it is between - (size - 1) and -1.
			if (newSelectedIndex < 0) newSelectedIndex += slides.size();
			select(slides.get(newSelectedIndex));
		}

		function select(el) {
			root.find('.three-d').removeClass('selected');
			$(el).addClass('selected');
			transition();
		}

		function init() {
			root.on('click', '.three-d', onSlideClick);
			$(document).on('keydown.three-d', onKeyDown);
			transition();
		}

		function destroy() {
			root.find('.three-d').removeClass('selected');
			root.off('click', '.three-d');
			$(document).off('keydown.three-d');
		}
	};

}(jQuery));