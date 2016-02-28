// Backbone classes for query models, collections, and views.
// Requires the data and form templates for querys to be included.
var QueryModel = FeedModel.extend({
	initialize: function() {
		this.on('error', this.onError, this);
		// Attempt to set user name if available.
		var user = userList.get(this.get('user_id'));
		this.set('user_name', user ? user.get('name') : "Unknown");

		// Attempt to set url link if it's a known search engine.
		var searchEngine = this.get('search_engine');
		var availableSearchEngines = ['google', 'duckduckgo', 'bing', 'yahoo'];
		if (availableSearchEngines.indexOf(searchEngine) == -1) {
			this.set('search_icon', null);
		} else {
			this.set('search_icon', searchEngine);
		}
	},
	onError: function(model, response) {
		MessageDisplay.displayIfError(response.responseJSON);
	}
});

var QueryCollection = Backbone.Collection.extend({
	model: QueryModel,
	url: '/api/v1/querys',
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

var QueryListItemView = Backbone.View.extend({
	tagName: 'div',
	className: 'query',
	events: {
		'click .delete': 'onDelete'
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
		var modalEl = $('#delete-query-modal');
		modalEl.find('[name=query_id]').val(this.model.get('id'));
		modalEl.modal('show');
	},
	render: function() {
		var template = this.layout.getTemplate('query');
		var html = template(this.model.toJSON());
		this.$el.html(html).addClass(this.layout.key);
		return this;
	},
});


var QueryListView = FeedListView.extend({
	el: '#query-list',
	supportedLayouts: ['list'],
	initialize: function(options) {
		this.collection.on('add', this.add, this);
		this.setLayout(options.layout || 'list');
	},
	add: function(model) {
		var item = new QueryListItemView({model: model, layout: this.layout});
		item.render();
		this.layout.add(item.$el);
		var that = this;
		model.on('destroy', function() {
			item.remove();
			that.layout.refresh();
		});
	}
});

// This function will initialize event handlers for the query forms.
// The collection is updated when a query is created/updated/deleted.
// This way, the collection could be a QueryCollection or any other 
// collection. (e.g. a feed list containing multiple types of objects).
//
function initializeQueryFormEventHandlers(collection){
	if (!collection) throw 'Collection not passed';
	$('#delete-query').on('submit', onDeleteSubmit);

	function onDeleteSubmit(e) {
		e.preventDefault();
		$('#delete-query-modal').modal('hide');
		var query_id = $(this).find('[name=query_id]').val();
		var query = collection.get(query_id);
		if (!query) {
			MessageDisplay.display(['Could not delete query'], 'danger');
			return;
		}
		query.destroy();
	}
}