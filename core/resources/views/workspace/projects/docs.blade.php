@extends('workspace.layouts.single-project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
page-docs
@endsection

@section('context')
@if ($memberService->can($project->id, 'w', $user))
<div class='context'>
	<button class='btn btn-warning' id='new-btn' data-toggle='modal' data-target='#create-doc-modal'>New Document</button>
</div>
@endif
@endsection('context')

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/docs'><span class='fa fa-file-text-o'></span> Docs</a>
@endsection('navigation')

@section('main-content')

<div class='row'>
	@include('helpers.showAllMessages')
    <div class='col-md-8'>
		<div id='doc-list' class='data-view'>
		</div>
	</div>
</div>

@include('workspace.data.docs')

<script src='/js/realtime.js'></script>
<script src='/js/data/layouts.js'></script>
<script src='/js/data/feed.js'></script>
<script src='/js/data/doc.js'></script>
<script src='/js/vendor/moment.js'></script>
<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ is_null($user) ? 'null' : $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
});

var docList = new DocCollection();
docList.fetch({
	data: {
		project_id: Config.get('projectId')
	}
});

var docListView = new DocListView({collection: docList});

function realtimeDataHandler(param) {
	updateStats(param);
	if (param.dataType != "docs") return;
	if (param.action == "create") {
		_.each(param.data, function(doc){
			docList.add(doc);	
		});
	} else if (param.action == "delete") {
		_.each(param.data, function(doc){
			docList.remove(doc);
		});	
	}
}

Realtime.init(realtimeDataHandler);

initializeDocFormEventHandlers(docList);

</script>
@endsection('main-content')