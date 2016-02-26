@extends('workspace.layouts.single-project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
page-docs
@endsection

@section('context')
@if ($memberService->can($project->id, 'w', $user))
<div class='context'>
	<button class='btn btn-warning' id='new-btn'>New Doc</button>
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
    <div class='col-md-12'>

    	<div class='row' id="new">
			<div class='col-md-6'>
				<h3>Create Doc</h3>
				<form action='/api/v1/docs' method='post' id='create-doc'>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Page title'/>
					</div>
					<input type='hidden' name='project_id' value='{{ $project->id }}' />
					<button class='cancel btn btn-danger'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Create</button>
					</div>
				</form>
			</div>
		</div>

		<ul id='doc-list'>
		</ul>
	</div>
</div>

@include('helpers.dataTemplates')

<script src='/js/realtime.js'></script>
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

$("#create-doc .cancel").on("click", function(e){
	e.preventDefault();
	$("#new").fadeOut(150);
})

$("#create-doc").on('submit', function(e){
	e.preventDefault();
	var form = $(this),
		titleInput = form.find('input[name=title]');

	$.ajax({
		url: '/api/v1/docs',
		method: 'post',
		data: {
			project_id : Config.get('projectId'),
			title: titleInput.val()
		},
		dataType: 'json',
		success: function(response) {
			docList.add(new DocModel(response.result));
			titleInput.val('');
		},
		error: function(xhr) {
			var json = JSON.parse(xhr.responseText);
			if (json) {
				MessageDisplay.displayIfError(json);
			}
		}
	});
});

$("#new-btn").on('click', function(){
	$("#new").fadeIn(150);
});

</script>
@endsection('main-content')