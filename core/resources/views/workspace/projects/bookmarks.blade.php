@extends('layouts.workspace.single-project')

@section('context')
<div class='context'>
	<button class='btn btn-warning' id='btn_add_new'>New Bookmark</button>
</div>
@endsection('context')

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/bookmarks'><span class='fa fa-star-o'></span> Bookmarks</a>
@endsection('navigation')

@section('page-content')

<div class='row'>
	@include('helpers.showAllMessages')
    <div class='col-md-12'>

    	<div class='row' id="add_new">
			<div class='col-md-6'>
				<h3>Create Bookmark</h3>
				<form action='/api/v1/bookmarks' method='post' id='createBookmark'>
					<div class='form-group'>
						<input class='form-control' type='url' name='url' placeholder='Bookmark URL'/>
					</div>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Page title'/>
					</div>
					<div class='form-group'>
						<input class='form-control' type='text' name='tags' placeholder='Comma separated tags' />
					</div>
					<input type='hidden' name='project_id' value='{{ $project->id }}' />
					<button class='cancel btn btn-danger'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Create</button>
					</div>
				</form>
			</div>
		</div>

		@if (count($bookmarks) == 0)
		<p>No bookmarks have been saved.</p>
		@endif

		<ul id='bookmark_list'>
		<!--
		Remove this once Backbone is working.
		@foreach ($bookmarks as $bookmark) 
		<li>
			<div><a target="_blank" href='{{ $bookmark->url }}'>{{ $bookmark->title }}</a></div>
			<p>
				Saved {{ $bookmark->created_at }} 
				@if ($permission == 'w' || $permission == 'o')
				| <a href='#' data-id='{{ $bookmark->id }}' class='delete'>Delete</a>
				@endif
			</p>
		</li>
		@endforeach
		-->
		</ul>
	</div>
</div>

<script type='text/template' id='bookmarkTemplate'>
	<div><a target="_blank" href='<%= url %>'><%= title %></a></div>
	<p>
		Saved <%= created_at %>
		<% if(permission == 'w' || permission == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		<% } %>
	</p>
</script>

<script src='/js/vendor/underscore.js'></script>
<script src='/js/vendor/backbone.js'></script>

<script>
var permission = '{{ $permission }}';

var BookmarkModel = Backbone.Model.extend({
	events: {
		'error' : 'onError'
	},
	onError: function(response) {
		console.log("ERROR");
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
	},
	attributes: function() {
		return {
			'data-id': this.model.id
		}
	},
	onDelete: function(e) {
		e.preventDefault();
		this.model.destroy();
	},
	render: function() {
		var html = this.template(this.model.toJSON());
		this.$el.html(html);
		return this;
	}
})

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

var bookmarkList = new BookmarkCollection();
bookmarkList.fetch();

var bookmarkListView = new BookmarkListView({collection: bookmarkList});

$("#createBookmark .cancel").on("click", function(e){
	e.preventDefault();
	$("#add_new").fadeOut(150);
})

$("#createBookmark").on('submit', function(e){
	e.preventDefault();
	var form = $(this),
		urlInput = form.find('input[name=url]'),
		titleInput = form.find('input[name=title]'),
		tagsInput = form.find('input[name=tags]');

	$.ajax({
		url: '/api/v1/bookmarks',
		method: 'post',
		data: {
			project_id : {{ $project->id }},
			url: urlInput.val(),
			title: titleInput.val(),
			tags: tagsInput.val().split(/\s*,\s*/)
		},
		dataType: 'json',
		success: function(response) {
			bookmarkList.add(new BookmarkModel(response.result));
		},
		error: function(xhr) {
			console.log("error");
			console.log(xhr.responseText);
		}
	});
	urlInput.val("");
	titleInput.val("");
	tagsInput.val("");
});

$("#btn_add_new").on('click', function(){
	$("#add_new").fadeIn(150);
})

</script>
@endsection('page-content')