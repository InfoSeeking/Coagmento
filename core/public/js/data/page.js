// Backbone classes for page models, collections, and views.
// Requires the data and form templates for pages to be included.
var PageModel = FeedModel.extend({
	initialize: function() {
		this.on('error', this.onError, this);
		// Attempt to set user name if available.
		var user = userList.get(this.get('user_id'));
		this.set('user_name', user ? user.get('name') : "Unknown");
	},
	onError: function(model, response) {
		MessageDisplay.displayIfError(response.responseJSON);
	}
});

var PageCollection = Backbone.Collection.extend({
	model: PageModel,
	url: '/api/v1/pages',
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

var PageListItemView = Backbone.View.extend({
	tagName: 'div',
	className: 'page',
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
		var modalEl = $('#delete-page-modal');
		modalEl.find('[name=page_id]').val(this.model.get('id'));
		modalEl.modal('show');
	},
	render: function() {
		var template = this.layout.getTemplate('page');
		var html = template(this.model.toJSON());
		this.$el.html(html).addClass(this.layout.key);
		return this;
	},
});


var PageListView = FeedListView.extend({
	el: '#page-list',
	supportedLayouts: ['grid', 'list'],
	initialize: function(options) {
		this.collection.on('add', this.add, this);
		this.setLayout(options.layout || 'grid');
	},
	add: function(model) {
		var item = new PageListItemView({model: model, layout: this.layout});
		item.render();
		this.layout.add(item.$el);
		var that = this;
		model.on('destroy', function() {
			item.remove();
			that.layout.refresh();
		});
	}
});

// This function will initialize event handlers for the page forms.
// The collection is updated when a page is created/updated/deleted.
// This way, the collection could be a PageCollection or any other 
// collection. (e.g. a feed list containing multiple types of objects).
//
function initializePageFormEventHandlers(collection){
	if (!collection) throw 'Collection not passed';
	$('#delete-page').on('submit', onDeleteSubmit);

	function onDeleteSubmit(e) {
		e.preventDefault();
		$('#delete-page-modal').modal('hide');
		var page_id = $(this).find('[name=page_id]').val();
		var page = collection.get(page_id);
		if (!page) {
			MessageDisplay.display(['Could not delete page'], 'danger');
			return;
		}
		page.destroy();
	}
}