@extends('workspace.layouts.single-project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
page-history
@endsection

@section('context')
@if ($memberService->can($project->id, 'w', $user))
<div class='context'></div>
@endif
@endsection('context')

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/history'><span class='fa fa-line-chart'></span> Activity</a>
@endsection('navigation')

@section('main-content')

<p>Page visits and searches are collected through Coagmento web browser extensions.</p>
<div class='row'>
	@include('helpers.showAllMessages')
    <div class='col-md-7 page-list-container'>
    	<h4>Pages Visited</h4>
    	<form id='page-layout-selection' class='form-inline'>
			<select class='form-control'>
				<option value='grid'>Grid</option>
				<option value='list'>List</option>
			</select>
		</form>
		<div id='page-list' class='row data-view'>
		</div>
	</div>
	<div class='col-md-5'>
		<h4>Searches</h4>
		<div id='query-list' class='row data-view'>
		</div>
	</div>
</div>

@include('workspace.data.pages')
@include('workspace.data.queries')

<script src='/js/realtime.js'></script>
<script src='/js/data/layouts.js'></script>
<script src='/js/data/feed.js'></script>
<script src='/js/data/user.js'></script>
<script src='/js/data/page.js'></script>
<script src='/js/data/query.js'></script>
<script src='/js/vendor/moment.js'></script>
<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ is_null($user) ? 'null' : $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
});

var userList = new UserCollection();
// Add all project users to user collection.
@foreach ($sharedUsers as $sharedUser)
userList.add(new UserModel(
	{!! $sharedUser->user->toJson() !!}
));
@endforeach

var pageList = new PageCollection();
pageList.add({!! $pages->toJSON() !!});

var pageListView = new PageListView({collection: pageList, layout: getPageSelectedLayout()});

var queryList = new QueryCollection();
queryList.add({!! $queries->toJSON() !!});

var queryListView = new QueryListView({collection: queryList});

function getPageSelectedLayout() {
	return $('#page-layout-selection').find('option:selected').attr('value');
}

function realtimeDataHandler(param) {
	var list;
	if (param.dataType == 'pages') list = pageList
	else if (param.dataType == 'search') list = queryList;
	else return;

	if (param.action == 'create') {
		_.each(param.data, function(item){
			list.add(doc);	
		});
	} else if (param.action == 'delete') {
		_.each(param.data, function(item){
			list.remove(doc);
		});	
	}
}

$('#page-layout-selection select').on('change', function(e){
	e.preventDefault();
	$(this).blur();
	pageListView.setLayout(getPageSelectedLayout());
});

Realtime.init(realtimeDataHandler);
initializePageFormEventHandlers(pageList);

</script>
@endsection('main-content')