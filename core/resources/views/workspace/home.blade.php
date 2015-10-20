@extends('layouts.workspace')

@section('content')

<div class="row">
	<div class="col-md-2 col-sm-4">
	<ul>
		<li>New Project</li>
		<li>My Projects</li>
		<li>Shared with me</li>
	</ul>
	</div>
    <div class="col-md-10 col-sm-8 main-content">
    	@include('helpers.showAllMessages')
		<div class="col-sm-6">
			<form class="form-horizontal" action="/workspace/projects/create" method="post">
				<div class="form-group">
					<label class="col-sm-4 control-label">Create a Project</label>	
				</div>

				<div class="form-group">
					<label for="inputProjectTitle" class="col-sm-4 control-label">Project Title</label>
					<div class="col-sm-8">
						<input type="text" maxlength="512" class="form-control" id="inputProjectTitle" name="title" />
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-4 control-label">
						<div class="row">
							<label for="inputProjectDescription">Project Description</label>
						</div>
						<div class="row"><small>(Optional)</small></div>
					</div>
					<div class="col-sm-8">
						<textarea class="form-control" id="inputProjectDescription" name="description"></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="inputVisibility" class="col-sm-4 control-label">Visibility</label>
					<div class="col-sm-8">
						<label>
							<input type="radio" name="visibility" id="inputVisibility" value="public" checked/>
							Public
						</label>
						<p>Public projects are publicly searchable and can be viewed by others.</p>
						<label>
							<input type="radio" name="visibility" id="inputVisibility" value="public"/>
							Private
						</label>
						<p>Private projects are unlisted and restricted to project members.</p>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-4"></div>
					<div class="col-sm-8">
						<button type="submit">Create Project</button>
					</div>
				</div>
			</form>

			@foreach($projects as $project)
			<li> <a href='/workspace/projects/{{ $project->id}}'>{{ $project->title }}</a> <a class='delete' href='#' data-id='{{$project->id}}'>X</a></li>
			@endforeach
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
</script>
@endsection('content')