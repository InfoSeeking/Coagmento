var Layout = {
	add: function(html) {},
	// Called when a model is removed.
	refresh: function() {},
	initialize: function() {}
};

var GridLayout = function(container) {
	this.key = 'grid';
};

GridLayout.prototype = Layout;