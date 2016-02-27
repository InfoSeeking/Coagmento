// Backbone classes for doc models, collections, and views.

var DocModel = FeedModel.extend({
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
	tagName: 'div',
	className: 'doc',
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
		this.model.on('remove', this.remove, this);
		this.model.on('change', this.render, this);
		this.layout = options.layout;
	},
	onDelete: function(e) {
		e.preventDefault();
		var modalEl = $('#delete-doc-modal')
		modalEl.find(['name=doc_id']).val(this.model.get('id'));
		modalEl.modal('show');
	},
	onEdit: function(e) {
		e.preventDefault();
		var modalEl = $('#edit-doc-modal');
		modalEl.find('[name=title]').val(this.model.get('title'));
		modalEl.find(['name=doc_id']).val(this.model.get('id'));
		modalEl.modal('show');
	},
	render: function() {
		var template = this.layout.getTemplate('doc');
		var html = template(this.model.toJSON());
		this.$el.html(html).addClass(this.layout.key);
		return this;
	}
});

var DocListView = FeedListView.extend({
	el: '#doc-list',
	supportedLayouts: ['list'],
	initialize: function() {
		this.collection.on('add', this.add, this);
		this.setLayout('list');
	},
	add: function(model) {
		var item = new DocListItemView({model: model, layout: this.layout});
		this.$el.prepend(item.render().$el);
		model.on('destroy', function() {
			item.remove();
			this.layout.refresh();
		});
	}
});

function initializeDocFormEventHandlers(collection){
	if (!collection) throw 'Collection not passed';
	$('#create-doc').on('submit', onCreateSubmit);
	$('#edit-doc').on('submit', onEditSubmit);
	$('#delete-doc').on('submit', onDeleteSubmit);

	function onCreateSubmit(e){
		e.preventDefault();
		$('#create-doc-modal').modal('hide');
		var form = $(this),
			titleInput = form.find('input[name=title]')
			;
			
		$.ajax({
			url: '/api/v1/docs',
			method: 'post',
			data: {
				project_id : Config.get('projectId'),
				title: titleInput.val(),
			},
			dataType: 'json',
			success: function(response) {
				collection.add(new DocModel(response.result));
				titleInput.val('');
			},
			error: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				MessageDisplay.displayIfError(json);
			}
		});
	}

	function onEditSubmit(e){
		e.preventDefault();
		$('#edit-doc-modal').modal('hide');
		var form = $(this)
		, doc_id = form.find('[name=doc_id]').val()
		, title = form.find('[name=title]').val()
		, doc = collection.get(doc_id)
		;
		if (!doc) {
			MessageDisplay.display(['Could not save doc'], 'danger');
			return;
		}
		doc.set('title', title);
		doc.save();
		MessageDisplay.display(['Doc saved'], 'success');
	}

	function onDeleteSubmit(e) {
		e.preventDefault();
		$('#delete-doc-modal').modal('hide');
		var doc_id = $(this).find('[name=doc_id]').val();
		var doc = collection.get(doc_id);
		if (!doc) {
			MessageDisplay.display(['Could not delete doc'], 'danger');
			return;
		}
		doc.destroy();
	}
}