@extends('workspace.layouts.project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
@if ($type == 'mine')
page-my-projects
@elseif ($type == 'shared')
page-shared-projects
@endif
@endsection

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
@endsection('navigation')

@section('main-content')
@include('helpers.showAllMessages')

<div class='row'>
	<div class='col-md-8'>
		<p class='welcome'>
		@if ($type == 'mine')		
			Here you can manage, update, and share your projects.
		@elseif ($type == 'shared')
			Projects which other users share with you appear here.
		@endif
		</p>


		@if (count($projects) == 0)
		<p>
		@if ($type == 'mine')		
			There are no projects here yet. You can <a href='/workspace/projects/create'>create one</a> now.
		@elseif ($type == 'shared')
			There are no projects here yet.
		@endif
		</p>
		@endif

		<a href='#' class='deleteSelected' style='display:none'>Delete Selected</a>

		<ul id='project-list'>
		@foreach($projects as $project)
		<li>
			<h4>
				<a href='/workspace/projects/{{ $project->id}}'>{{ $project->title }}</a>
				<small>
				@if ($project->creator_id == $user->id || $project->level == 'o')
				<a href='/workspace/projects/{{ $project->id}}/settings'>Settings</a>
				@else
				{{ ucfirst($memberService->permissionToString($project->level)) }} permission.
				@endif
				</small>
			</h4>
			<p>{{ $project->description or 'No description.' }}</p>		
		</li>
		@endforeach
		</ul>
	</div>
</div>

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
@endsection('main-content')