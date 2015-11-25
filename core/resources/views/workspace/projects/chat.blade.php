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
    	<div id='chat-container'>
    		<ul id='chat-list'>
    		</ul>
    		<div id='chat-bar'>
    			<form id='chat-form'>
    				<input type='text' name='message' class='message' placeholder='Chat with your group'/><button type='submit'>Send</button>
    			</form>
    		</div>
    	</div>
	</div>
</div>

@include('helpers.dataTemplates')

<script src='/js/realtime.js'></script>
<script src='/js/data/chat.js'></script>
<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
});

var chatList = new ChatCollection();
chatList.fetch({
	data: {
		project_id: Config.get('projectId')
	}
});

var chatListView = new ChatListView({collection: chatList});

function realtimeDataHandler(param) {
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
@endsection('page-content')