// Backbone classes for data models, collections, and views.

var BookmarkModel = Backbone.Model.extend({
	events: {
		'error' : 'onError'
	},
	onError: function(response) {
		console.log('ERROR');
		console.log(response);
	}
});

var BookmarkCollection = Backbone.Collection.extend({
	model: BookmarkModel,
	url: '/api/v1/bookmarks',
	parse: function(json){
		return json.result;
	}
});

var BookmarkListItemView = Backbone.View.extend({
	tagName: 'li',
	className: 'bookmark',
	template: _.template($('#bookmarkTemplate').html()),
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
		var html = this.template(this.model.toJSON());
		this.$el.html(html);
		return this;
	}
});

var BookmarkListView = Backbone.View.extend({
	el: '#bookmark_list',
	initialize: function() {
		this.collection.on('add', this.add, this);
	},
	render: function() {
		this.$el.empty();
		this.collection.each(function(model){
			this.add(model);
		});
	},
	add: function(model) {
		var item = new BookmarkListItemView({model: model});
		this.$el.append(item.render().$el);
		model.on('destroy', function() {
			item.remove();
		});
	}
});