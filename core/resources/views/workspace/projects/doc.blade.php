@extends('layouts.workspace.single-project')


@section('page')
page-doc
@endsection

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/docs'><span class='fa fa-star-o'></span> Docs</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/docs/'><span class='fa fa-star-o'></span> {{ $doc->title }}</a>
@endsection('navigation')

@section('page-content')

@include('helpers.showAllMessages')
<div id='etherpad'></div>

<script src='/js/vendor/etherpad.js'></script>
<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ $user->id }},
	userName: '{{ $user->name }}',
	etherpadEnabled: {{ env('ETHERPAD_SERVER') == null ? 'false' : 'true'}},
	etherpadServer: '{{ env('ETHERPAD_SERVER') }}',
	padId: '{{ $doc->getPadId() }}'
});

MessageDisplay.init();

if (!Config.get('etherpadEnabled')) {
	MessageDisplay.display(['Coagmento Docs is not enabled'], 'danger');
}

$('#etherpad').pad({
	padId: Config.get('padId'),
	showChat: false,
	host: Config.get('etherpadServer'),
	showControls: true,
	userName: Config.get('userName'),
	height: 600
});

</script>
@endsection('page-content')