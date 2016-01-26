@extends('workspace.layouts.single-project')


@section('page')
page-bookmarks
@endsection

@section('context')
<div class='context'>
	<button class='btn btn-warning' id='new-btn'>New Bookmark</button>
</div>
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
    <div class='col-md-12'>

    	<div class='row' id="new">
			<div class='col-md-6'>
				<h3>Create Bookmark</h3>
				<form action='/api/v1/bookmarks' method='post' id='create-bookmark'>
					<div class='form-group'>
						<input class='form-control' type='text' name='url' placeholder='Bookmark URL'/>
					</div>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Page title'/>
					</div>
					<div class='form-group'>
						<textarea class='form-control' name='notes' placeholder='Notes'></textarea>
					</div>
					<div class='form-group'>
						<input class='form-control' type='text' name='tags' placeholder='Comma separated tags' />
					</div>
					<input type='hidden' name='project_id' value='{{ $project->id }}' />
					<button class='cancel btn btn-danger'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Create</button>
					</div>
				</form>
			</div>
		</div>

		<ul id='bookmark-list' class='data-view row'>
		</ul>
	</div>
</div>

@include('helpers.dataTemplates')

<script src='/js/realtime.js'></script>
<script src='/js/data/bookmark.js'></script>
<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
});

var bookmarkList = new BookmarkCollection();
bookmarkList.fetch({
	data: {
		project_id: Config.get('projectId')
	}
});

var bookmarkListView = new BookmarkListView({collection: bookmarkList});

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

$("#create-bookmark .cancel").on("click", function(e){
	e.preventDefault();
	$("#new").fadeOut(150);
})

$("#create-bookmark").on('submit', function(e){
	e.preventDefault();
	var form = $(this),
		urlInput = form.find('input[name=url]'),
		titleInput = form.find('input[name=title]'),
		notesInput = form.find('textarea[name=notes]')
		tagsInput = form.find('input[name=tags]');

	$.ajax({
		url: '/api/v1/bookmarks',
		method: 'post',
		data: {
			project_id : Config.get('projectId'),
			url: urlInput.val(),
			title: titleInput.val(),
			tags: tagsInput.val().split(/\s*,\s*/),
			notes: notesInput.val()
		},
		dataType: 'json',
		success: function(response) {
			bookmarkList.add(new BookmarkModel(response.result));
			urlInput.val('');
			titleInput.val('');
			tagsInput.val('');
			notes: notesInput.val('')
		},
		error: function(xhr) {
			var json = JSON.parse(xhr.responseText);
			MessageDisplay.displayIfError(json);
		}
	});
});

$("#new-btn").on('click', function(){
	$("#new").fadeIn(150);
});

</script>
@endsection('main-content')