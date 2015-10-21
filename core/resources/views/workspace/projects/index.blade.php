@extends('layouts.workspace.project')

@section('page-content')
@include('helpers.showAllMessages')

@if (count($projects) == 0)
<p>There aren't any projects here yet.</p>
@endif

<a href='#' class='deleteSelected' style='display:none'>Delete Selected</a>

<ul id='project_list'>
@foreach($projects as $project)
<li>
	<h4><a href='/workspace/projects/{{ $project->id}}'>{{ $project->title }}</a></h4>
	<p>{{ $project->description }}</p>
	<!-- Owner specific settings -->
	@if ($project->creator_id == $user->id || $project->level == 'o')
	<input class='select' type='checkbox' data-id='{{$project->id}}'/> |
	<a href='/workspace/projects/{{ $project->id}}/settings'>Settings</a> |
	<a class='delete' href='#' data-id='{{$project->id}}'>Delete</a>
	@endif
</li>
@endforeach
</ul>

<script>
$('.delete').on('click', function(e) {
	e.preventDefault();
	var confirmed = confirm('Are you sure you want to delete this project with its data?');
	if (confirmed) {
		var projectId = $(this).attr('data-id');
		var link = $(this).parent();
		$.ajax({
			url: '/api/v1/projects/' + projectId,
			method: 'delete',
			success: function() {
				link.fadeOut(150);
				// TODO: hacky way of making sure "deleted" dom doesn't effect us.
				// We should remove from the DOM on deletion.
				link.find('.select').prop('checked', false);
			}
		});
	}
});

$('.select').on('click', function(){
	$('.deleteSelected').fadeIn(150);
});

$('.deleteSelected').on('click', function(e) {
	e.preventDefault();
	var ids = [];
	var items = [];
	var inputBoxes = $('.select');
	
	inputBoxes.each(function(i,e){
		var checkbox = $(e);
		if (checkbox.prop('checked')) {
			ids.push(parseInt(checkbox.attr('data-id')));
			items.push(checkbox.parent());
		}
	});

	var confirmed = confirm('Are you sure you want to delete these ' + ids.length + ' projects with their data?');
	if (confirmed) {
		$.ajax({
			url: '/api/v1/projects',
			data: {
				'ids' : ids
			},
			method: 'delete',
			success: function() {
				for(var i = 0; i < items.length; i++) {
					items[i].fadeOut(150);
					items[i].find('.select').prop('checked', false);
				}
			}
		});
	}
});

</script>
@endsection('page-content')