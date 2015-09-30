@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <h1>Coagmento Workspace</h1>
    <div>
    <div class="row">
    	@include('helpers.showAllErrors')
        <div class="col-md-12">
		<h2>Viewing Project {{ $project->title }}</h2>
		
		<h3>Bookmarks</h3>
		<ul>
		@foreach($bookmarks as $bookmark)
		<li><a href='/workspace/projects/{{ $project->id }}/bookmarks/{{ $bookmark->id }}'>{{$bookmark->title}}</a></li>
		@endforeach
		</ul>

		<h3>Create Bookmark</h3>
		<!-- todo: Why doesn't this authenticate w/o ajax? -->
		<form action='/api/v1/bookmarks' method='post' id='createBookmark'>
			<input type='text' name='url' placeholder='url' value='http://example.com' />
			<input type='text' name='title' placeholder='title' value='Example Title' />
			<input type='text' name='tags' placeholder='tags' value='tag1, tag2' />
			<input type='hidden' name='project_id' value='{{ $project->id }}' />
			<input type='submit' value='Create' />
		</form>

		</div>
	</div>
</div>

<script>
$('.delete').on('click', function(e) {
	e.preventDefault();
	var projectId = $(this).attr('data-id');
	var link = $(this).parent();
	$.ajax({
		url: 'api/v1/projects/' + projectId,
		method: 'delete',
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
		url: '/api/v1/bookmarks/',
		method: 'post',
		data: {
			'project_id' : projectId,
			'title': title,
			'url': url,
			'tags' : tags
		},
		complete: function(xhr) {
			document.write(xhr.responseText);
		},
		success: function() {
			window.location.reload();
		}
	})
})
</script>
@endsection('content')