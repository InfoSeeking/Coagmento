@extends('workspace.layouts.single-project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
page-bookmarks
@endsection

@section('context')
@if ($memberService->can($project->id, 'w', $user))
<div class='context'>
	<button class='btn btn-warning' id='new-btn' data-toggle='modal' data-target='#create-bookmark-modal'>New Bookmark</button>
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

	<!-- Create bookmark modal window -->
	<div class='row modal fade' tabindex='-1' id='create-bookmark-modal'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>Create Bookmark</div>
				<div class='modal-body'>
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
						<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
						<div class='pull-right'>
							<button type='submit' class='btn btn-primary'>Create</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Edit bookmark modal window -->
	<div class='row modal fade' tabindex='-1' id='edit-bookmark-modal'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>Edit Bookmark</div>
				<div class='modal-body'>
					<form action='/api/v1/bookmarks' method='put' id='edit-bookmark'>
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
						<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
						<div class='pull-right'>
							<button type='submit' class='btn btn-primary'>Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Delete bookmark confirmation window -->
	<div class='row modal fade' tabindex='-1' id='delete-bookmark-modal'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>Delete Bookmark</div>
				<div class='modal-body'>
					<form action='/api/v1/bookmarks' method='delete' id='delete-bookmark'>
						<p>Are you sure you want to delete this bookmark? This cannot be undone.</p>
						<input type='hidden' name='bookmark_id' value='' />
						<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
						<div class='pull-right'>
							<button type='submit' class='btn btn-primary'>Delete</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

    <div class='col-md-12'>
		
		<form id='layout-selection' class='form-inline'>
			<select class='form-control'>
				<option value="grid">Grid</option>
				<option value="list">List</option>
				<option value="coverflow">Coverflow</option>
			</select>
		</form>
		
		<div id='bookmark-list' class='data-view row'>
		</div>
		
	</div>
</div>

@include('helpers.dataTemplates')

<script src='/js/realtime.js'></script>
<script src='/js/vendor/jquery-ui.core.widget.min.js'></script>
<script src='/js/vendor/jquery.coverflow.js'></script>

<!--<script src='/js/vendor/jquery.coverflow.js'></script>-->
<script src='/js/data/bookmark.js'></script>
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

$('#create-bookmark').on('submit', function(e){
	e.preventDefault();
	$('#create-bookmark-modal').modal('hide');
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

$('#delete-bookmark').on('submit', function(e) {
	e.preventDefault();
	$('#delete-bookmark-modal').modal('hide');
	var bookmark_id = $(this).find('[name=bookmark_id]').val();
	var bookmark = bookmarkList.get(bookmark_id);
	if (!bookmark) {
		MessageDisplay.display(['Could not delete bookmark'], 'danger');
		return;
	}
	bookmark.destroy();
});

$("#new-btn").on('click', function(){
	$("#new").fadeIn(150);
});

$('#layout-selection').on('change', function(e){
	e.preventDefault();
	bookmarkListView.setLayout(getSelectedLayout());
});

</script>
@endsection('main-content')