<!-- TODO Sections commented out are in progress. -->
@extends('sidebar.layout')

@section('content')

<div class="col-lg-12">
	<h1 style="font-size:16px"> Select a Project</h1>
</div>
<ul class="nav" id="side-menu">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a target="_self" href="/sidebar"><i class="fa fa-folder-open-o fa-fw"></i>Projects</a></li>
		</ol>
	</div>
	<div class="col-md-12">	
		<div class="panel panel-default">
		<!--
			<div class="sidebar-search">
				<div class="input-group custom-search-form">
					<input type="text" class="form-control" placeholder="Search for a project...">
					<span class="input-group-btn">
					<button class="btn btn-default" type="button">
						<i class="fa fa-search"></i>
					</button>
					</span>
				</div>
			</div>
		-->											
			<div class="panel-body">
			@foreach($projects as $project)
			<li>
				<a target="_self" href='/sidebar/project/{{$project->id}}'>{{$project->title}}</a><br/>
				
				@if ($project->level == 'w')
				<span class='permission'>You can edit this project.</span>
				@elseif ($project->level == 'r')
				<span class='permission'>You can view this project.</span>
				@elseif ($project->level == 'o')
				<span class='permission'>You own this project.</span>
				@endif
			</li>
			@endforeach
			</div>
		</div>
	</div>
	
	<!--
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus-square-o fa-fw"></i>New Project
			</div>
			<div class="panel-body">
				<form>
				  <div class="form-group">
					<label>Project Name</label>
					<input class="form-control" placeholder="Enter Project Name">
				  </div>
				  <div class="form-group">
					<label>Description</label>
					<textarea class="form-control" rows="3" placeholder="(Optional)"></textarea>
				  </div>
				  
				  <div class="radio">
					<label>
					  <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
					  Private 
					  <p>(Publicly searchable and can be viewed by others)</p>
					</label>
				  </div>
				  <div class="radio">
					<label>
					  <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
					  Public
					  <p>(Have to invite others to join)</p>
					</label>
				  </div>
				  
				  <button type="submit" class="btn btn-primary">Create</button>
				</form>
			</div>
		</div>
	</div>
	-->
</ul>

<script src='/js/sidebar.js'></script>
<script>
Sidebar.onParentMessage(function(evt){
	console.log('Parent window message received.');
	console.log(evt.data);
});

Sidebar.sendToParent({
	'state': {
		'user': {
			'status': true,
			'id': {{ $user->id }},
			'name': '{{ $user->name }}'
		},
		'page': 'select',
		'project': false
	}
});
</script>

@endsection('content')