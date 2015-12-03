// Backbone classes for doc models, collections, and views.

var DocModel = Backbone.Model.extend({
	initialize: function() {
		this.on('error', this.onError, this);
	},
	onError: function(model, response) {
		MessageDisplay.displayIfError(response.responseJSON);
	}
});

var DocCollection = Backbone.Collection.extend({
	model: DocModel,
	url: '/api/v1/docs',
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

var DocListItemView = Backbone.View.extend({
	tagName: 'li',
	className: 'doc',
	template: _.template($('#doc-template').html()),
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

var DocListView = Backbone.View.extend({
	el: '#doc-list',
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
		var item = new DocListItemView({model: model});
		this.$el.prepend(item.render().$el);
		model.on('destroy', function() {
			item.remove();
		});
	}
});