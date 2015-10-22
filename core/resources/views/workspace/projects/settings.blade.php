@extends('layouts.workspace.project')
@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/settings'>Settings</a>
@endsection('navigation')

@section('page-content')

<div class="row">
	@include('helpers.showAllMessages')
    <div class="col-md-12">
		<h2>{{ $project->title }} Settings</h2>
		<h3>Sharing</h3>
		@if (count($sharedUsers) == 0)
			<p>This project is currently not being shared with anyone</p>
		@else
			<p>
			This project is being shared with {{ count($sharedUsers) }}
			@if (count($sharedUsers) > 1)
				people
			@else
				person
			@endif
			</p>
			<ul>
			@foreach ($sharedUsers as $user)
			<li>
			{{$user->name}} ({{$user->email}}) has
			@if ($user->level == 'r')
			read
			@elseif ($user->level == 'w')
			write
			@elseif ($user->level == 'o')
			own
			@endif
			permissions
			</li>
			@endforeach
			</ul>
		@endif
		<form class='form-inline' action='/api/v1/project/{{ $project->id }}/share' method='post' id='shareUser'>
			<div class="form-group">
				<input class='form-control' id='inputShareEmail' type='email' name='email' placeholder='User email'/>
				<select class='form-control' name='permissions'>
					<option value='r'>Can view</option>
					<option value='w'>Can write</option>
					<option value='o'>Is co-owner</option>
				</select>
				<button type='submit' class='btn btn-primary'>Add</button>
			</div>
			<input type='hidden' name='project_id' value='{{ $project->id }}' />
		</form>
		<!--
		<div class='col-md-4'>
			<h4>Bookmarks</h4>
			<ul>
			@foreach ($bookmarks as $bookmark)
				<li>{{ $bookmark->title }}</li>
			@endforeach
			</ul>
		</div>
		<div class='col-md-8'>
			<h3>Create Bookmark</h3>
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
		</div>
		-->

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

$("#shareUser").on('submit', function(e){
	e.preventDefault();
	var projectId = $(this).find('input[name=project_id]').val();
	var permissions = $(this).find('select[name=permissions]').val();
	var email = $(this).find('input[name=email]').val();
	$.ajax({
		url: '/api/v1/projects/' + projectId + '/share',
		method: 'post',
		data: {
			'user_email': email,
			'permission': permissions,
		},
		complete: function(xhr) {
			var errorJson = JSON.parse(xhr.responseText);
			if (errorJson['errors']['input'].length > 0) {
				alert(errorJson['errors']['input'].join(' '));
			}
			if (errorJson['errors']['general'].length > 0) {
				alert(errorJson['errors']['general'].join(' '));
			}
		},
		success: function() {
			// Add to list.
			window.location.reload();
		}
	})
})

</script>
@endsection('page-content')