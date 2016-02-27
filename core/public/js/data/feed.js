var FeedModel = Backbone.Model.extend({
	
});

var FeedCollection = Backbone.Collection.extend({
	model: FeedModel
});

// Handles rendering data items in a selected layout.
var FeedListView = Backbone.View.extend({
	el: '#feed',
	layout: null,
	supportedLayouts: [],
	layoutClasses: {
		'grid': GridLayout,
		'list': ListLayout,
		'coverflow': CoverflowLayout,
		'three-d': ThreeDLayout
	},
	render: function() {
		this.$el.empty();
		this.layout.preInit(this.$el);
		var that = this;
		this.collection.forEach(function(model){
			that.add(model);
		})
		this.layout.postInit();
	},
	setLayout: function(key) {
		if (this.supportedLayouts.indexOf(key) === -1) {
			throw 'Layout ' + key + ' not supported';
		}

		if (this.layout != null) {
			this.layout.destroy();
		}

		this.layout = new this.layoutClasses[key]();
		this.$el.attr('data-layout', key);
		this.render();
	},
});