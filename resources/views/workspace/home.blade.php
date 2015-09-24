@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <h1>Coagmento Workspace</h1>
    <div>
    <div class="row">
    	@include('helpers.showAllErrors')
        <div class="col-md-12">
		<h2>Projects</h2>
		@foreach($projects as $project)
		<li> {{ $project->title }} <a class='delete' href='#' data-id='{{$project->id}}'>X</a></li>
		@endforeach
		
		<h2>Create</h2>
		<form action='workspace/project/create' method='post'>
		{!! csrf_field() !!}
		<input type='text' name='title' placeholder='Project Name' />
		<input type='submit' />
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
</script>
@endsection('content')