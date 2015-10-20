@extends('layouts.workspace.project')
@section('page-content')

<div class="container">
    <div class="row">
        <h1>Coagmento Workspace</h1>
    <div>
    <div class="row">
    	@include('helpers.showAllMessages')
        <div class="col-md-12">
			<h2>Viewing Project '{{ $project->title }}'</h2>
			
			<div class='col-md-4'>
				<h4>Existing Bookmarks</h4>
				<ul>
				@foreach ($bookmarks as $bookmark)
					<li>{{ $bookmark->title }}</li>
				@endforeach
				</ul>
				<h4>Existing Snippets</h4>
				<ul>
				@foreach ($snippets as $snippet)
					<li>{{ $snippet->text }}</li>
				@endforeach
				</ul>
				<h4>Existing Pages</h4>
				<ul>
				@foreach ($pages as $page)
					<li>{{ $page->title }}</li>
				@endforeach
				</ul>
			</div>
			<div class='col-md-8'>
				<h3>Create Bookmark</h3>
				<!-- todo: Why doesn't this authenticate w/o ajax? -->
				<form action='/api/v1/bookmarks' method='post' id='createBookmark'>
					<div class="form-group">
						<input type='text' name='url' placeholder='url' value='http://example.com' />
					</div>
					<div class="form-group">
						<input type='text' name='title' placeholder='title' value='Example Title' />
					</div>
					<div class="form-group">
						<input type='text' name='tags' placeholder='tags' value='tag1, tag2' />
					</div>
					<input type='hidden' name='project_id' value='{{ $project->id }}' />
					<button type='submit' class='btn btn-default'>Create</button>
				</form>

				<h3>Create Snippet</h3>
				<form action='/api/v1/snippets' method='post' id='createSnippet'>
					<div class="form-group">
						<input type='text' name='url' placeholder='url' value='http://example.com' />
					</div>
					<div class="form-group">
						<textarea name='text'>Snippet Text</textarea>
					</div>
					<input type='hidden' name='project_id' value='{{ $project->id }}' />
					<button type='submit' class='btn btn-default'>Create</button>
				</form>


				<h3>Create Page</h3>
				<form action='/api/v1/pages' method='post' id='createPage'>
					<div class="form-group">
						<input type='text' name='url' placeholder='url' value='http://example.com' />
					</div>
					<div class="form-group">
						<input type='text' name='title' placeholder='title' value='Example Title' />
					</div>
					<input type='hidden' name='project_id' value='{{ $project->id }}' />
					<button type='submit' class='btn btn-default'>Create</button>
				</form>
			</div>

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
	});
});

$("#createSnippet").on('submit', function(e){
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