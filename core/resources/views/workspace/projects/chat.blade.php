@extends('workspace.layouts.single-project')
@inject('memberService', 'App\Services\MembershipService')

@section('page')
page-chat
@endsection

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}/chat'><span class='fa fa-comment'></span> Group Chat</a>
@endsection('navigation')

@section('main-content')

<div class='row'>
	@include('helpers.showAllMessages')
    <div class='col-md-8'>
    	<div id='chat-container'>
    		<ul id='chat-list'>
    		</ul>
    		<div id='chat-bar'>
    			@if ($memberService->can($project->id, 'w', $user))
    			<form id='chat-form' class='form-horizontal'>
    				<div class='form-group'>
    					<div class='col-md-11'>
    						<input type='text' name='message' class='message form-control' placeholder='Chat with your group'/>
    					</div>
    					<div class='col-md-1 text-right'>
    						<button class='btn btn-primary' type='submit'>Send</button>
    					</div>
    				</div>
    			</form>
    			@endif
    		</div>
    	</div>
	</div>
</div>

@include('workspace.data.chat')

<script src='/js/realtime.js'></script>
<script src='/js/data/layouts.js'></script>
<script src='/js/data/feed.js'></script>
<script src='/js/data/chat.js'></script>
<script src='/js/vendor/moment.js'></script>

<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ is_null($user) ? 'null' : $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
});

if (!Config.get('realtimeEnabled')) {
	MessageDisplay.display(['Realtime updates are disabled,' +
	' so chat messages will only load on page refresh.'], 'danger');
}

var chatList = new ChatCollection();
chatList.fetch({
	data: {
		project_id: Config.get('projectId')
	}
});

var chatListView = new ChatListView({collection: chatList});

function realtimeDataHandler(param) {
	updateStats(param);
	if (param.dataType != "chat_messages") return;
	if (param.action == "create") {
		_.each(param.data, function(message){
			chatList.add(message);
			$('#chat-list li:last-child').addClass('new');
		});
	}
}

Realtime.init(realtimeDataHandler);

$('#chat-form').on('submit', function(e){
	e.preventDefault();
	var messageInput = $(this).find('input[name=message]'),
		message = messageInput.val();
	if (message.trim() == '') return;
	$.ajax({
		url: '/api/v1/chatMessages',
		method: 'post',
		data: {
			message: message,
			project_id : Config.get('projectId')
		},
		dataType: 'json',
		error: function(xhr) {
			var json = JSON.parse(xhr.responseText);
			if (json) {
				MessageDisplay.displayIfError(json);
			}
		},
		success: function(response) {
			messageInput.val('');
		},
	});
});
</script>
@endsection('main-content')