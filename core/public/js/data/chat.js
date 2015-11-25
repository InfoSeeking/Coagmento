// Backbone classes for chat models, collections, and views.

var ChatModel = Backbone.Model.extend({
	initialize: function() {
		this.on('error', this.onError, this);
	},
	onError: function(model, response) {
		MessageDisplay.displayIfError(response.responseJSON);
	}
});

var ChatCollection = Backbone.Collection.extend({
	model: ChatModel,
	url: '/api/v1/chatMessages',
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

var ChatListItemView = Backbone.View.extend({
	tagName: 'li',
	className: 'chat-message',
	template: _.template($('#chat-template').html()),
	attributes: function() {
		return {
			'data-id': this.model.id
		}
	},
	initialize: function () {
		this.model.on('change', this.render, this);
	},
	render: function() {
		var html = this.template(this.model.toJSON());
		this.$el.html(html);
		return this;
	}
});

var ChatListView = Backbone.View.extend({
	el: '#chat-list',
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
		var item = new ChatListItemView({model: model});
		this.$el.append(item.render().$el);
		this.$el.scrollTop(this.$el.prop('scrollHeight'));
		model.on('destroy', function() {
			item.remove();
		});
	}
});