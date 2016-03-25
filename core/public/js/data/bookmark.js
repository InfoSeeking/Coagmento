// Backbone classes for bookmark models, collections, and views.
// Requires the data and form templates for bookmarks to be included.
var BookmarkModel = FeedModel.extend({
	initialize: function() {
		this.on('error', this.onError, this);
		// Attempt to set user name if available.
		var user = userList ? userList.get(this.get('user_id')) : null;
		this.set('user_name', user ? user.get('name') : "Unknown");
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
		var modalEl = $('#delete-bookmark-modal');
		modalEl.find('[name=bookmark_id]').val(this.model.get('id'));
		modalEl.modal('show');
	},
	onEdit: function(e) {
		e.preventDefault();
		var modalEl = $('#edit-bookmark-modal');
		modalEl.find('[name=bookmark_id]').val(this.model.get('id'));
		modalEl.find('[name=title]').val(this.model.get('title'));
		modalEl.find('[name=notes]').val(this.model.get('notes'));
		modalEl.find('[name=url]').html(this.model.get('url'));
		modalEl.modal('show');
	},
	render: function() {
		var template = this.layout.getTemplate('bookmark');
		var html = template(this.model.toJSON());
		this.$el.html(html).addClass(this.layout.key);
		return this;
	},
});


var BookmarkListView = FeedListView.extend({
	el: '#bookmark-list',
	supportedLayouts: ['grid', 'list', 'coverflow', 'three-d', 'sidebar'],
	initialize: function(options) {
		this.collection.on('add', this.add, this);
		this.setLayout(options.layout || 'grid');
	},
	add: function(model) {
		var item = new BookmarkListItemView({model: model, layout: this.layout});
		item.render();
		this.layout.add(item.$el);
		var that = this;
		model.on('destroy', function() {
			item.remove();
			that.layout.refresh();
		});
	}
});

// This function will initialize event handlers for the bookmark forms.
// The collection is updated when a bookmark is created/updated/deleted.
// This way, the collection could be a BookmarkCollection or any other 
// collection. (e.g. a feed list containing multiple types of objects).
//
function initializeBookmarkFormEventHandlers(collection){
	if (!collection) throw 'Collection not passed';
	$('#create-bookmark').on('submit', onCreateSubmit);
	$('#edit-bookmark').on('submit', onEditSubmit);
	$('#delete-bookmark').on('submit', onDeleteSubmit);

	function onCreateSubmit(e){
		e.preventDefault();
		$('#create-bookmark-modal').modal('hide');
		var form = $(this),
			urlInput = form.find('input[name=url]'),
			titleInput = form.find('input[name=title]'),
			notesInput = form.find('textarea[name=notes]')
			tagsInput = form.find('input[name=tags]');

		$.ajax({
			url: '/api/v1/bookmarks',
			method: 'post',
			data: {
				project_id : Config.get('projectId'),
				url: urlInput.val(),
				title: titleInput.val(),
				tags: tagsInput.val().split(/\s*,\s*/),
				notes: notesInput.val()
			},
			dataType: 'json',
			success: function(response) {
				collection.add(new BookmarkModel(response.result));
				urlInput.val('');
				titleInput.val('');
				tagsInput.val('');
				notes: notesInput.val('')
			},
			error: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				MessageDisplay.displayIfError(json);
			}
		});
	}

	function onEditSubmit(e){
		e.preventDefault();
		$('#edit-bookmark-modal').modal('hide');
		var form = $(this)
		, bookmark_id = form.find('[name=bookmark_id]').val()
		, title = form.find('[name=title]').val()
		, notes = form.find('[name=notes]').val()
		, bookmark = collection.get(bookmark_id)
		;
		if (!bookmark) {
			MessageDisplay.display(['Could not save bookmark'], 'danger');
			return;
		}
		bookmark.set('title', title);
		bookmark.set('notes', notes);
		bookmark.save();
		MessageDisplay.display(['Bookmark saved'], 'success');
	}

	function onDeleteSubmit(e) {
		e.preventDefault();
		$('#delete-bookmark-modal').modal('hide');
		var bookmark_id = $(this).find('[name=bookmark_id]').val();
		var bookmark = collection.get(bookmark_id);
		if (!bookmark) {
			MessageDisplay.display(['Could not delete bookmark'], 'danger');
			return;
		}
		bookmark.destroy();
	}
}