@extends('layouts.workspace.single-project')

@section('page')
page-chat
@endsection

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/bookmarks'><span class='fa fa-star-o'></span> Bookmarks</a>
@endsection('navigation')

@section('page-content')

<div class='row'>
	@include('helpers.showAllMessages')
    <div class='col-md-12'>
    	<h3>Chat</h3>
	</div>
</div>

@include('helpers.dataTemplates')

<script src='/js/realtime.js'></script>
<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
});

</script>
@endsection('page-content')