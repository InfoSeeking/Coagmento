// Backbone classes for chat models, collections, and views.

var ChatModel = FeedModel.extend({
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
	attributes: function() {
		return {
			'data-id': this.model.id
		}
	},
	initialize: function (options) {
		this.model.on('change', this.render, this);
		this.layout = options.layout;
	},
	render: function() {
		var template = this.layout.getTemplate('chat');
		var html = template(this.model.toJSON());
		this.$el.html(html);
		return this;
	}
});

var ChatListView = FeedListView.extend({
	el: '#chat-list',
	supportedLayouts: ['list', 'sidebar'],
	initialize: function(options) {
		this.collection.on('add', this.add, this);
		this.setLayout(options.layout || 'list');
	},
	add: function(model) {
		var item = new ChatListItemView({model: model, layout: this.layout});
		item.render();
		this.layout.add(item.$el, true);
		this.$el.scrollTop(this.$el.prop('scrollHeight'));
		var that = this;
		model.on('destroy', function() {
			item.remove();
			that.layout.refresh();
		});
	}
});