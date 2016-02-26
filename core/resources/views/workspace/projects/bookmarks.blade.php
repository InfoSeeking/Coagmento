@extends('workspace.layouts.single-project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
page-bookmarks
@endsection

@section('context')
@if ($memberService->can($project->id, 'w', $user))
<div class='context'>
	<button class='btn btn-warning' data-toggle='modal' data-target='#create-bookmark-modal'>New Bookmark</button>
</div>
@endif
@endsection('context')

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/bookmarks'><span class='fa fa-star-o'></span> Bookmarks</a>
@endsection('navigation')

@section('main-content')

<div class='row'>
	@include('helpers.showAllMessages')
	@include('workspace.data.bookmarks')
    <div class='col-md-12'>
		<form id='layout-selection' class='form-inline'>
			<select class='form-control'>
				<option value='grid'>Grid</option>
				<option value='list'>List</option>
				<option value='coverflow'>Coverflow</option>
				<option value='three-d'>3D (experimental)</option>
			</select>
		</form>
		
		<div id='bookmark-list' class='data-view row'>
		</div>
		
		<!--
		Concerns
		- We MUST be able to destroy/recreate without issues for this to work.
		- It seems like adding/removing slides dynamically would require a bit more of modification in impress code, but it may be possible.
		- If all we're using this for is transitioning z position, it might be more worth it to write this from scratch.
		
		
		<div id='impress'>
			<div class='slide selected'>Demonstration of 3d</div>
			<div class='slide'>Slide 2</div>
			<div class='slide'>Slide 3</div>
			<div class='slide'>Slide 3</div>
			<div class='slide'>Slide 3</div>
			<div class='slide'>Slide 3</div>
			<div class='slide'>Slide 3</div>
			<div class='slide'>Slide 3</div>
			<div class='slide'>Slide 3</div>
		</div>
		<script src='/js/three-d.js'></script>
		<script>
			$('#impress').threeD('init');
		</script>
		-->
	</div>
</div>

<script src='/js/realtime.js'></script>
<script src='/js/vendor/jquery-ui.core.widget.min.js'></script>
<script src='/js/vendor/jquery.coverflow.js'></script>
<script src='/js/three-d.js'></script>
<script src='/js/vendor/impress.js'></script>
<script src='/js/vendor/moment.js'></script>
<script src='/js/data/user.js'></script>
<script src='/js/data/feed.js'></script>
<script src='/js/data/bookmark.js'></script>

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


function getSelectedLayout() {
	return $('#layout-selection').find('option:selected').attr('value');
}

var bookmarkList = new BookmarkCollection();
bookmarkList.fetch({
	data: {
		project_id: Config.get('projectId')
	}
});

var bookmarkListView = new BookmarkListView({
	collection: bookmarkList,
	layout: getSelectedLayout()
});

function realtimeDataHandler(param) {
	if (param.dataType != "bookmarks") return;
	if (param.action == "create") {
		_.each(param.data, function(bookmark){
			bookmarkList.add(bookmark);	
		});
	} else if (param.action == "delete") {
		_.each(param.data, function(bookmark){
			bookmarkList.remove(bookmark);
		});	
	} else if (param.action == "update") {
		_.each(param.data, function(bookmark){
			var model = bookmarkList.get(bookmark);
			model.set(bookmark);
		});	
	}
}

Realtime.init(realtimeDataHandler);

$('#layout-selection select').on('change', function(e){
	e.preventDefault();
	$(this).blur();
	bookmarkListView.setLayout(getSelectedLayout());
});

initializeBookmarkFormEventHandlers(bookmarkList);

</script>
@endsection('main-content')