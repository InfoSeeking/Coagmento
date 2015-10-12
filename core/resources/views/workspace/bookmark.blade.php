@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <h1>Coagmento Workspace</h1>
    <div>
    <div class="row">
    	@include('helpers.showAllErrors')
        <div class="col-md-12">
		<h2>Viewing Bookmark {{ $project->title }}</h2>
		
		<h3>Create Tag</h3>
		<form action='/api/v1/bookmark/{{ $bookmark->id }}/addTag' method='put'>
			<input type='text' placeholder='Tag name'/>
			<input type='submit' value='Add' />
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