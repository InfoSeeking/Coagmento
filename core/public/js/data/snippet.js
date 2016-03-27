// Backbone classes for snippet models, collections, and views.

var SnippetModel = FeedModel.extend({
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
	tagName: 'div',
	className: 'snippet',
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
		var modalEl = $('#delete-snippet-modal');
		modalEl.find('[name=snippet_id]').val(this.model.get('id'));
		modalEl.modal('show');
	},
	onEdit: function(e) {
		e.preventDefault();
		var modalEl = $('#edit-snippet-modal');
		modalEl.find('[name=snippet_id]').val(this.model.get('id'));
		modalEl.find('[name=title]').val(this.model.get('title'));
		modalEl.find('[name=text]').val(this.model.get('text'));
		modalEl.find('[name=url]').html(this.model.get('url'));
		modalEl.modal('show');
	},
	render: function() {
		var template = this.layout.getTemplate('snippet');
		var html = template(this.model.toJSON());
		this.$el.html(html).addClass(this.layout.key);
		return this;
	}
});

var SnippetListView = FeedListView.extend({
	el: '#snippet-list',
	supportedLayouts: ['grid', 'list'],
	initialize: function(options) {
		this.collection.on('add', this.add, this);
		this.setLayout(options.layout || 'list');
		this.collection.on('update', this.checkEmpty, this);
		this.checkEmpty();
	},
	add: function(model) {
		var item = new SnippetListItemView({model: model, layout: this.layout});
		item.render();
		this.layout.add(item.$el);
		var that = this;
		model.on('destroy', function() {
			item.remove();
			that.layout.refresh();
		});
	}
});

function initializeSnippetFormEventHandlers(collection){
	if (!collection) throw 'Collection not passed';
	$('#create-snippet').on('submit', onCreateSubmit);
	$('#edit-snippet').on('submit', onEditSubmit);
	$('#delete-snippet').on('submit', onDeleteSubmit);

	function onCreateSubmit(e){
		e.preventDefault();
		$('#create-snippet-modal').modal('hide');
		var form = $(this),
			urlInput = form.find('input[name=url]'),
			titleInput = form.find('input[name=title]'),
			textInput = form.find('textarea[name=text]');

		$.ajax({
			url: '/api/v1/snippets',
			method: 'post',
			data: {
				project_id : Config.get('projectId'),
				url: urlInput.val(),
				title: titleInput.val(),
				text: textInput.val()
			},
			dataType: 'json',
			success: function(response) {
				collection.add(new SnippetModel(response.result));
				urlInput.val('');
				titleInput.val('');
				text: textInput.val('')
			},
			error: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				MessageDisplay.displayIfError(json);
			}
		});
	}

	function onEditSubmit(e){
		e.preventDefault();
		$('#edit-snippet-modal').modal('hide');
		var form = $(this)
		, snippet_id = form.find('[name=snippet_id]').val()
		, title = form.find('[name=title]').val()
		, text = form.find('[name=text]').val()
		, snippet = collection.get(snippet_id)
		;
		if (!snippet) {
			MessageDisplay.display(['Could not save snippet'], 'danger');
			return;
		}
		snippet.set('title', title);
		snippet.set('text', text);
		snippet.save();
		MessageDisplay.display(['Snippet saved'], 'success');
	}

	function onDeleteSubmit(e) {
		e.preventDefault();
		$('#delete-snippet-modal').modal('hide');
		var snippet_id = $(this).find('[name=snippet_id]').val();
		var snippet = collection.get(snippet_id);
		if (!snippet) {
			MessageDisplay.display(['Could not delete snippet'], 'danger');
			return;
		}
		snippet.destroy();
	}

}