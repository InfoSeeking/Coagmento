@extends('layouts.workspace.project')

@section('page-content')
@include('helpers.showAllMessages')
<ul>
@foreach($projects as $project)
<li> <a href='/workspace/projects/{{ $project->id}}'>{{ $project->title }}</a> <a class='delete' href='#' data-id='{{$project->id}}'>X</a></li>
@endforeach
</ul>

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
@endsection('page-content')