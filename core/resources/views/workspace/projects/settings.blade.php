@extends('workspace.layouts.single-project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
page-settings
@endsection

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/settings'>Settings</a>
@endsection('navigation')

@section('main-content')

<div class="row">
	@include('helpers.showAllMessages')
    <div class="col-md-8">
		<h2>Project Settings ({{ $project->title }})</h2>
		<h4>Manage</h4>
		<p>
			<p>This is a {{ $project->private ? "private" : "public"}} project.</p>
			<a href='#' data-target-privateness='{{ $project->private ? "0" : "1"}}' class='btn btn-danger switch-visibility'>
				Make this Project {{ $project->private ? "Public" : "Private"}}
			</a>
			<a href='#' class='btn btn-danger delete'>Delete this Project</a>
		</p>
		<p>Edit your project information.
		<div class='row'><div class='col-md-12'>
		<form id='project-description'>
			<div class='form-group'>
				<input type='text' name='title' placeholder='Edit project title.' class='form-control' value='{{$project->title}}'/>
			</div>
			<div class='form-group'>

				<textarea class='form-control' name='description' placeholder='Edit project description.'>{{$project->description}}</textarea>
			</div>
			<div class='form-group'>
				<button type='submit' class='btn btn-primary pull-right'>Save</button>
			</div>
		</form>
		</div></div>
		</p>

		<h4>Sharing</h4>
		@if (count($sharedUsers) == 1)
			<p>This project is currently not being shared with anyone else.</p>
		@else
			<p>
			This project is being shared with {{ count($sharedUsers) - 1 }}
			@if (count($sharedUsers) - 1 > 1)
				people
			@else
				person
			@endif
			</p>
			<ul>
			@foreach ($sharedUsers as $sharedUser)
			@if ($sharedUser->user != $user)
			<li>
			{{$sharedUser->user->name}} ({{$sharedUser->user->email}}) has {{ $memberService->permissionToString($sharedUser->level) }} permission. 
			<a href='#' data-user-id='{{$sharedUser->user->id}}' data-user-email='{{$sharedUser->user->email}}' class='remove-permissions'>Remove</a>.
			</li>
			@endif
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


	</div>
</div>
<script src='/js/realtime.js'></script>


<script>
(function(){
	Config.setAll({
		projectId: {{ $project->id }},
		userId: {{ is_null($user) ? 'null' : $user->id }},
		realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
		realtimeServer: '{{ env('REALTIME_SERVER') }}'
	});
	var projectId = {{ $project->id}};

	// Handles deleting the project. Redirects to workspace after alerting user.
	$('.delete').on('click', function(e) {
		e.preventDefault();
		var confirmation = confirm("Are you sure you wish to delete this project with all of it's data");
		if (!confirmation) return;
		$.ajax({
			url: '/api/v1/projects/' + projectId,
			method: 'delete',
			success: function() {
				alert('Project has been deleted');
				window.location = '/workspace';
			},
			error: function(xhr)  {
				var json = JSON.parse(xhr.responseText);
				MessageDisplay.displayIfError(json);
			}
		})
	});

	$('.remove-permissions').on('click', function(e) {
		e.preventDefault();
		var userEmail = $(this).attr('data-user-email');
		var userId = $(this).attr('data-user-id');
		var listItem = $(this).parent();
		var confirmation = confirm("Are you sure you wish to remove the user (" + userEmail + ") from this project?");
		if (!confirmation) return;
		$.ajax({
			url: '/api/v1/projects/' + projectId + '/share',
			data: {
				user_id: userId
			},
			method: 'delete',
			success: function(response) {
				MessageDisplay.display(['User removed from project'], 'success');
				listItem.detach();
			},
			error: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				MessageDisplay.displayIfError(json);
			}
		})
	});

	$("#shareUser").on('submit', function(e){
		e.preventDefault();
		var permissions = $(this).find('select[name=permissions]').val();
		var email = $(this).find('input[name=email]').val();
		$.ajax({
			url: '/api/v1/projects/' + projectId + '/share',
			method: 'post',
			data: {
				'user_email': email,
				'permission': permissions,
			},
			success: function() {
				// Add to list.
				window.location.reload();
			},
			error: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				MessageDisplay.displayIfError(json);
			}
		});
	});

	// Handles updating project title and description.
	$('#project-description').on('submit', function(e){
		e.preventDefault();
		var description = $(this).find('textarea[name=description]').val();
		var title = $(this).find('input[name=title]').val();
		$.ajax({
			url: '/api/v1/projects/' + projectId,
			method: 'put',
			data: {
				'title': title,
				'description': description
			},
			success: function() {
				MessageDisplay.display(['Project title and description updated.'], 'success');
			},
			error: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				MessageDisplay.displayIfError(json);
			}
		})
	});


	$('.switch-visibility').on('click', function(e){
		e.preventDefault();
		var targetPrivateness = $(this).attr('data-target-privateness');
		var targetWord = targetPrivateness ? 'private' : 'public';
		var confirmation = confirm('Are you sure you wish to make this project ' + targetWord + '?');
		if (!confirmation) return;
		$.ajax({
			url: '/api/v1/projects/' + projectId,
			method: 'put',
			data: {
				"private": targetPrivateness
			},
			success: function(response) {
				// Reload to update button.
				window.location.reload();
			},
			error: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				MessageDisplay.displayIfError(json);
			}
		});
	});

	function realtimeDataHandler(param) {
		updateStats(param);
	}

	Realtime.init(realtimeDataHandler);

}());

</script>
@endsection('main-content')