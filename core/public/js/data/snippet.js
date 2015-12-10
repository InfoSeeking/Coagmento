// Backbone classes for snippet models, collections, and views.

var SnippetModel = Backbone.Model.extend({
	initialize: function() {
		this.on('error', this.onError, this);
	},
	onError: function(model, response) {
		MessageDisplay.displayIfError(response.responseJSON);
	}
});

var SnippetCollection = Backbone.Collection.extend({
	model: SnippetModel,
	url: '/api/v1/snippets',
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

var SnippetListItemView = Backbone.View.extend({
	tagName: 'li',
	className: 'snippet',
	template: _.template($('#snippet-template').html()),
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
		var response = prompt('Enter new text', this.model.get('text'));
		if (response) {
			this.model.set('text', response);
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

var SnippetListView = Backbone.View.extend({
	el: '#snippet-list',
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
		var item = new SnippetListItemView({model: model});
		this.$el.prepend(item.render().$el);
		model.on('destroy', function() {
			item.remove();
		});
	}
});