@extends('layouts.workspace.single-project')

@section('context')
<div class='context'>
	<button class='btn btn-warning' id='btn_add_new'>New Snippet</button>
</div>
@endsection('context')


@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/snippets'><span class='fa fa-star-o'></span> Snippets</a>
@endsection('navigation')

@section('page-content')

<div class='row'>
	@include('helpers.showAllMessages')
    <div class='col-md-12'>
    	<div class='row' id='add_new'>
			<div class='col-md-6' >
				<h3>Create Snippet</h3>
				<form action='/api/v1/snippets' method='post' id='createSnippet'>
					<div class='form-group'>
						<input class='form-control' type='url' name='url' placeholder='Snippet URL'/>
					</div>
					<div class='form-group'>
						<textarea class='form-control' type='text' name='text' placeholder='Snippet Text'></textarea>
					</div>
					<input type='hidden' name='project_id' value='{{ $project->id }}' />
					<button class='cancel btn btn-danger'>Cancel</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Create</button>
					</div>
				</form>
			</div>
		</div>

		@if (count($snippets) == 0)
		<p>No snippets have been saved.</p>
		@endif

		<ul id='bookmark_list'>
		@foreach ($snippets as $snippet) 
		<li>
			<div>
				<a href='{{ $snippet->url }}'>{{ $snippet->url }}</a>
				<p>{{ $snippet->text }}</p>
			</div>
			<p>
				Saved {{ $snippet->created_at }} 
				@if ($permission == 'w' || $permission == 'o')
				| <a href='#' data-id='{{ $snippet->id }}' class='delete'>Delete</a>
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
	var snippetId = $(this).attr('data-id');
	var link = $(this).parent().parent();
	$.ajax({
		url: '/api/v1/snippets/' + snippetId,
		method: 'delete',
		complete: function(xhr) {
			console.log(xhr.responseText);
		},
		success: function() {
			link.fadeOut();
		}
	})
});

$('#createSnippet').on('submit', function(e){
	e.preventDefault();
	var projectId = $(this).find('input[name=project_id]').val();
	var text = $(this).find('textarea[name=text]').val();
	var url = $(this).find('input[name=url]').val();
	$.ajax({
		url: '/api/v1/snippets',
		method: 'post',
		data: {
			'project_id' : projectId,
			'text': text,
			'url': url,
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

$("#createSnippet .cancel").on("click", function(e){
	e.preventDefault();
	$("#add_new").fadeOut(150);
})

$("#btn_add_new").on('click', function(){
	$("#add_new").fadeIn(150);
})

</script>
@endsection('page-content')