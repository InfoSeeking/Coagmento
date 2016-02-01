// Backbone classes for bookmark models, collections, and views.

var BookmarkModel = Backbone.Model.extend({
	initialize: function() {
		this.on('error', this.onError, this);
	},
	onError: function(model, response) {
		MessageDisplay.displayIfError(response.responseJSON);
	}
});

var BookmarkCollection = Backbone.Collection.extend({
	model: BookmarkModel,
	url: '/api/v1/bookmarks',
	initialize: function() {
		this.on('error', this.onError, this);
	},
	parse: function(json){
		return json.result;
	},
	onError: function(collection, response) {
		MessageDisplay.displayIfError(response.responseJSON);
	}
});

var BookmarkListItemView = Backbone.View.extend({
	tagName: 'div',
	className: 'bookmark',
	templates: {
		'grid': _.template($('[data-template=bookmark][data-layout=grid]').html()),
		'list': _.template($('[data-template=bookmark][data-layout=list]').html()),
		'coverflow': _.template($('[data-template=bookmark][data-layout=coverflow]').html()),
	},
	events: {
		'click .delete': 'onDelete',
		'click .edit': 'onEdit',
	},
	attributes: function() {
		return {
			'data-id': this.model.id
		}
	},
	initialize: function (options) {
		this.layout = options.layout;
		this.model.on('remove', this.remove, this);
		this.model.on('change', this.render, this);
	},
	onDelete: function(e) {
		e.preventDefault();
		if (!confirm('Are you sure you wish to delete?')) return;
		this.model.destroy();
	},
	onEdit: function(e) {
		e.preventDefault();
		// TODO: use a modal window.
		var response = prompt('Enter a new title', this.model.get('title'));
		if (response) {
			this.model.set('title', response);
			this.model.save();
		}
		this.render();
	},
	render: function() {
		var template = this.templates[this.layout];
		var html = template(this.model.toJSON());
		this.$el.html(html).addClass(this.layout);
		return this;
	},
});

var BookmarkListView = Backbone.View.extend({
	el: '#bookmark-list',
	container: $('#bookmark-list'),
	layout: 'grid',
	supportedLayouts: ['grid', 'list', 'coverflow'],
	initialize: function(options) {
		this.collection.on('add', this.add, this);
		if (options.layout) {
			this.setLayout(options.layout);
		}
	},
	render: function() {
		this.$el.empty();
		if (this.layout == 'coverflow') {
			this.initializeCoverflow();
			this.container = $('#coverflow-container');
		} else {
			this.container = $('#bookmark-list');
		}

		this.collection.forEach(function(model){
			this.add(model);
		}, this);



	},
	// Re-render the bookmark list with the request layout.
	// layout must be one of the values in supportedLayouts.
	setLayout: function(layout) {
		if (this.supportedLayouts.indexOf(layout) == -1) {
			throw 'Bookmarks does not support layout ' + layout;
		}
		this.layout = layout;
		this.render();
	},
	add: function(model) {
		var item = new BookmarkListItemView({model: model, layout: this.layout});
		this.container.prepend(item.render().$el);
		model.on('destroy', function() {
			item.remove();
			this.refreshCoverflow();
		});
		this.refreshCoverflow();
	},
	initializeCoverflow: function() {
		this.container = $("<div id='coverflow-container'>");
		this.$el.append(this.container);
		this.coverflow = this.container.coverflow();
	},
	refreshCoverflow: function() {
		if (this.layout != 'coverflow') return;
		if (!this.coverflow) return;
		this.coverflow.data('vanderlee-coverflow').refresh(500);
	}
});