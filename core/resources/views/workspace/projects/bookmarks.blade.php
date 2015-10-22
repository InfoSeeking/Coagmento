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
					<button type='submit' class='btn btn-default'>Create</button>
				</form>
			</div>
		</div>

		@if (count($bookmarks) == 0)
		<p>No bookmarks have been saved.</p>
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

$("#btn_add_new").on('click', function(){
	$("#add_new").fadeIn(150);
})

</script>
@endsection('page-content')