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
						<input class='form-control' type='text' name='url' placeholder='Bookmark URL'/>
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
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		| <a data-id='<%= id %>' class='edit'>Edit</a>
		<% } %>
	</p>
</script>

<script src='/js/vendor/socket.io.js'></script>
<script src='/js/vendor/backbone.js'></script>
<script src='/js/config.js'></script>
<script src='/js/data.js'></script>
<script src='/js/realtime.js'></script>

<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
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
			urlInput.val("");
			titleInput.val("");
			tagsInput.val("");
		},
		error: function(xhr) {
			var json = JSON.parse(xhr.responseText);
			if (json) {
				MessageDisplay.displayIfError(json);
			}
		}
	});
});

$("#btn_add_new").on('click', function(){
	$("#add_new").fadeIn(150);
});


function realtimeDataHandler(param) {
	if (param.dataType != "bookmarks") return;
	if (param.action == "create") {
		_.each(param.data, function(bookmark){
			bookmarkList.add(bookmark);	
		});
	} else if (param.action == "delete") {
		_.each(param.data, function(bookmark){
			bookmarkList.remove(bookmark);
		});	
	} else if (param.action == "update") {
		_.each(param.data, function(bookmark){
			var model = bookmarkList.get(bookmark);
			model.set(bookmark);
		});	
	}
}

Realtime.init(realtimeDataHandler);


/*
Interesting finds
=================
- Backbone doesn't complain if you try to remove a non-existing item. 
- Additionally, if you add an item to a collection with the same id, it
ignores it.
- The create method had to be done with regular AJAX, because I can't find out
how to have Backbone create an item based on the response
data (which includes the ID).

Next:
- Display errors
- Add toggling option
- Organize
- Ignore self edits (potentially).
	- My concern is if an update takes 2 seconds, and the user makes another quick update 
	in 1 second, they'll see their update revert for 1 second.

*/
</script>
@endsection('page-content')