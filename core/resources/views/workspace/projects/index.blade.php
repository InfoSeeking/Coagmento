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
		<p>
		@if ($type == 'mine')		
			Welcome to your new Coagmento workspace. You can manage and share your projects and view analytics to see your progress. Coagmento is still under active development, but you can follow the development on our <a href="https://github.com/InfoSeeking/Coagmento" target="_blank">GitHub page</a>.
		@elseif ($type == 'shared')
			Projects which other users share with you appear here.
		@endif
		</p>


		@if (count($projects) == 0)
		<p>There aren't any projects here yet.</p>
		@endif

		<a href='#' class='deleteSelected' style='display:none'>Delete Selected</a>

		<ul id='project-list'>
		@foreach($projects as $project)
		<li>
			<h4>
				@if ($project->creator_id == $user->id || $project->level == 'o')
				<input class='select' type='checkbox' data-id='{{$project->id}}'/> 
				@endif
				<a href='/workspace/projects/{{ $project->id}}'>{{ $project->title }}</a>
			</h4>
			<p>{{ $project->description }}</p>


			<!-- Owner specific settings -->
			@if ($project->creator_id == $user->id || $project->level == 'o')
			<a href='/workspace/projects/{{ $project->id}}/settings'>Settings</a>&nbsp;
			<a class='delete' href='#' data-id='{{$project->id}}'>Delete</a>
			@else
			<p> You have {{ $memberService->permissionToString($project->level) }} permission.</p>
			@endif
				
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