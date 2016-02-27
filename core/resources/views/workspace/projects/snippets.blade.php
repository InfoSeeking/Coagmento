@extends('workspace.layouts.single-project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
page-snippets
@endsection

@section('context')
@if ($memberService->can($project->id, 'w', $user))
<div class='context'>
	<button class='btn btn-warning' id='new-btn' data-toggle='modal' data-target='#create-snippet-modal'>New Snippet</button>
</div>
@endif
@endsection('context')


@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/snippets'><span class='fa fa-sticky-note-o'></span> Snippets</a>
@endsection('navigation')

@section('main-content')

<div class='row'>
	@include('helpers.showAllMessages')
    <div class='col-md-12'>

		<form id='layout-selection' class='form-inline'>
			<select class='form-control'>
				<option value='list'>List</option>
				<option value='grid'>Grid</option>
			</select>
		</form>
		
		<div id='snippet-list' class='data-view row'>
		</div>

		@include('workspace.data.snippets')

	</div>
</div>

<script src='/js/realtime.js'></script>
<script src='/js/data/layouts.js'></script>
<script src='/js/data/feed.js'></script>
<script src='/js/data/snippet.js'></script>
<script src='/js/vendor/moment.js'></script>
<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ is_null($user) ? 'null' : $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
});

function getSelectedLayout() {
	return $('#layout-selection').find('option:selected').attr('value');
}

$('#layout-selection select').on('change', function(e){
	e.preventDefault();
	$(this).blur();
	snippetListView.setLayout(getSelectedLayout());
});

var snippetList = new SnippetCollection();
snippetList.fetch({
	data: {
		project_id: Config.get('projectId')
	}
});

var snippetListView = new SnippetListView({
	collection: snippetList,
	layout: getSelectedLayout()
});

function realtimeDataHandler(param) {
	if (param.dataType != "snippets") return;
	if (param.action == "create") {
		_.each(param.data, function(snippet){
			snippetList.add(snippet);	
		});
	} else if (param.action == "delete") {
		_.each(param.data, function(snippet){
			snippetList.remove(snippet);
		});	
	} else if (param.action == "update") {
		_.each(param.data, function(snippet){
			var model = snippetList.get(snippet);
			model.set(snippet);
		});	
	}
}

Realtime.init(realtimeDataHandler);

initializeSnippetFormEventHandlers(snippetList);

</script>
@endsection('main-content')