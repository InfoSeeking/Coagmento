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
	layout: 'grid',
	templates: {
		'grid': _.template($('[data-template=bookmark][data-layout=grid]').html()),
		'list': _.template($('[data-template=bookmark][data-layout=list]').html())
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
	initialize: function () {
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
		var html = this.templates[this.layout](this.model.toJSON());
		this.$el.html(html).addClass(this.layout);
		return this;
	},
	withLayout: function(layout) {
		if (!this.templates.hasOwnProperty(layout)) {
			throw 'Layout ' + this.layout + ' is not supported for bookmarks';
		};
		this.layout = layout;
		return this;
	}
});

var BookmarkListView = Backbone.View.extend({
	el: '#bookmark-list',
	layout: 'grid',
	supportedLayouts: ['grid', 'list'],
	initialize: function() {
		this.collection.on('add', this.add, this);
	},
	render: function() {
		this.$el.empty();
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
		var item = new BookmarkListItemView({model: model});
		this.$el.prepend(item.withLayout(this.layout).render().$el);
		model.on('destroy', function() {
			item.remove();
		});
	}
});