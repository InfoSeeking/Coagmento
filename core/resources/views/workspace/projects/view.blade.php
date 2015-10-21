@extends('layouts.workspace.project')
@section('page-content')

<div class='row'>
	@include('helpers.showAllMessages')
    <div class='col-md-12'>
		<h2>{{ $project->title }} Bookmarks</h2>
		@if (count($bookmarks) == 0)
		No bookmarks have been saved.
		@endif

		<ul id='bookmark_list'>
		@foreach ($bookmarks as $bookmark) 
		<li>
			<div><a href='{{ $bookmark->url }}'>{{ $bookmark->title }}</a></div>
			<p>
				Saved {{ $bookmark->created_at }} 
				@if ($permission == 'w' || $permission == 'o')
				| <a href='#' data-id='{{ $bookmark->id }}' class='delete'>Delete</a>
				@endif
			</p>
		</li>
		@endforeach
		</ul>

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
				<button type='submit' class='btn btn-default'>Create</button>
			</form>
		</div>

	</div>
</div>

<script>
$('.delete').on('click', function(e) {
	e.preventDefault();
	var bookmarkId = $(this).attr('data-id');
	var link = $(this).parent().parent();
	$.ajax({
		url: '/api/v1/bookmarks/' + bookmarkId,
		method: 'delete',
		complete: function(xhr) {
			console.log(xhr.responseText);
		},
		success: function() {
			link.fadeOut();
		}
	})
});

$('#createBookmark').on('submit', function(e){
	e.preventDefault();
	var projectId = $(this).find('input[name=project_id]').val();
	var title = $(this).find('input[name=title]').val();
	var url = $(this).find('input[name=url]').val();
	var tags = $(this).find('input[name=tags]').val();
	tags = tags.split(/\s*,\s*/);
	$.ajax({
		url: '/api/v1/bookmarks',
		method: 'post',
		data: {
			'project_id' : projectId,
			'title': title,
			'url': url,
			'tags' : tags
		},
		complete: function(xhr) {
			console.log("COMPLETE");

			var errorJson = JSON.parse(xhr.responseText);
			console.log(errorJson);
			if (errorJson.status == 'error') {
				if (errorJson['errors']['general'].length > 0) {
					alert(errorJson['errors']['general'].join(' '));
				}
				var inputErrors = '';
				for (var prop in errorJson['errors']['input']) {
					if (errorJson['errors']['input'].hasOwnProperty(prop)) {
						inputErrors += errorJson['errors']['input'][prop] + ' ';
					}
				}
				if (inputErrors != '') alert(inputErrors);
			}			
		},
		success: function() {
			window.location.reload();
		}
	});
});

$('#createSnippet').on('submit', function(e){
	e.preventDefault();
	var projectId = $(this).find('input[name=project_id]').val();
	var text = $(this).find('textarea[name=text]').val();
	var url = $(this).find('input[name=url]').val();
	$.ajax({
		url: '/api/v1/snippets/',
		method: 'post',
		data: {
			'project_id' : projectId,
			'url': url,
			'text' : text
		},
		complete: function(xhr) {
			document.write(xhr.responseText);
		},
		success: function() {
			window.location.reload();
		}
	});
});

$('#createPage').on('submit', function(e){
	e.preventDefault();
	var projectId = $(this).find('input[name=project_id]').val();
	var title = $(this).find('input[name=title]').val();
	var url = $(this).find('input[name=url]').val();
	$.ajax({
		url: '/api/v1/pages/',
		method: 'post',
		data: {
			'project_id' : projectId,
			'title': title,
			'url': url
		},
		complete: function(xhr) {
			document.write(xhr.responseText);
		},
		success: function() {
			window.location.reload();
		}
	});
});

</script>
@endsection('page-content')