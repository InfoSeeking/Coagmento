@extends('sidebar.layout')

@section('content')

@include('helpers.showAllMessages')

<div class='col-lg-12'>
	<h1 style='font-size:16px'>Project Feed</h1>
</div>
<ul class='nav' id='side-menu'>
	<div class='col-md-12'>
		<ol class='breadcrumb'>
			<li><a target='_self' href='/sidebar'><i class='fa fa-folder-open-o fa-fw'></i>Projects</a>
			</li>
			<li class='active'>{{$project->title}}</li>
		</ol>
	</div>

	<div class='col-md-12'>	
		 <div class='chat-panel panel panel-default'>
			<div class='panel-heading'>
				<i class='fa fa-comments fa-fw'></i>Chat
			</div>
			<!-- /.panel-heading -->
			<ul class='panel-body chat' id='chat-list'>
			</ul>
			<!-- /.panel-body -->
			<div class='panel-footer'>
				<form id='chat-form'>
				<div class='input-group'>
					<input id='btn-input' type='text' name='message' class='form-control input-sm' placeholder='Type a message' />
					<span class='input-group-btn'>
						<button type='submit' class='btn btn-warning btn-sm' id='btn-chat'>
							Send
						</button>
					</span>
				</div>
				</form>
			</div>
			<!-- /.panel-footer -->
		</div>
		<!-- /.panel .chat-panel -->
	</div> <!--Chat-->
	
	<div class='col-md-12'>
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<i class='fa fa-list fa-fw'></i>Live Feed 
			</div>
			<!-- /.panel-heading -->
			<div class='panel-body'>
				<!-- Nav tabs -->
				<ul class='nav nav-tabs'>
					<li class='active'><a href='#bookmark' data-toggle='tab' class='top' title='' data-placement='top' data-toggle='tooltip' href='#' data-original-title='Bookmarks'><i class='fa fa-bookmark fa-fw'></i></a>
					</li>
					<li><a href='#snippets' data-toggle='tab' class='top' title='' data-placement='top' data-toggle='tooltip' href='#' data-original-title='Snippets'><i class='fa fa-file-text-o fa-fw'></i></a>
					</li>
				</ul>

				<!-- Tab panes -->
				<div class='tab-content'>
					<div class='tab-pane in active fade' id='bookmark'>
						<h5>Bookmarks</h5>
						<div id='bookmark-list' class='data-list'></div>
					</div>
					<div class='tab-pane fade' id='snippets'>
						<h5>Snippets</h5>
						<div id='snippet-list' class='data-list'>
						</div>
					</div>
				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
</ul>

@include('workspace.data.bookmarks')
@include('workspace.data.snippets')
@include('workspace.data.chat')

<script src='/js/vendor/moment.js'></script>
<script src='/js/vendor/socket.io.js'></script>
<script src='/js/vendor/underscore.js'></script>
<script src='/js/vendor/backbone.js'></script>
<script src='/js/config.js'></script>
<script src='/js/message.js'></script>
<script src='/js/data/layouts.js'></script>
<script src='/js/data/feed.js'></script>
<script src='/js/data/user.js'></script>
<script src='/js/data/bookmark.js'></script>
<script src='/js/data/snippet.js'></script>
<script src='/js/data/chat.js'></script>
<script src='/js/realtime.js'></script>
<script src='/js/sidebar.js'></script>

<script>
Config.setAll({
	permission: '{{ $permission }}',
	projectId: {{ $project->id }},
	userId: {{ $user->id }},
	realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
	realtimeServer: '{{ env('REALTIME_SERVER') }}'
});
MessageDisplay.init();

var userList = new UserCollection();
// Add all project users to user collection.
@foreach ($sharedUsers as $sharedUser)
userList.add(new UserModel(
	{!! $sharedUser->user->toJson() !!}
));
@endforeach

var bookmarkList = new BookmarkCollection();
bookmarkList.add({!! $bookmarks->toJSON() !!});

var bookmarkListView = new BookmarkListView({collection: bookmarkList, layout: 'sidebar'});

var snippetList = new SnippetCollection();
snippetList.add({!! $snippets->toJSON() !!});

var snippetListView = new SnippetListView({collection: snippetList, layout: 'list'});

var chatList = new ChatCollection();
chatList.fetch({
	data: {
		project_id: Config.get('projectId')
	}
});

var chatListView = new ChatListView({collection: chatList, layout: 'sidebar'});


function realtimeDataHandler(param) {
	if (param.dataType == 'bookmarks') {
		if (param.action == 'create') {
			_.each(param.data, function(bookmark){
				bookmarkList.add(bookmark);	
			});
		} else if (param.action == 'delete') {
			_.each(param.data, function(bookmark){
				bookmarkList.remove(bookmark);
			});	
		} else if (param.action == 'update') {
			_.each(param.data, function(bookmark){
				var model = bookmarkList.get(bookmark);
				model.set(bookmark);
			});	
		}
	} else if (param.dataType == 'snippets') {
		if (param.action == 'create') {
			_.each(param.data, function(snippet){
				snippetList.add(snippet);	
			});
		} else if (param.action == 'delete') {
			_.each(param.data, function(snippet){
				snippetList.remove(snippet);
			});	
		} else if (param.action == 'update') {
			_.each(param.data, function(snippet){
				var model = snippetList.get(snippet);
				model.set(snippet);
			});	
		}
	} else if (param.dataType == 'chat_messages') {
		if (param.action == "create") {
			_.each(param.data, function(message){
				chatList.add(message);
				$('#chat-list li:last-child').addClass('new');
			});
		}
	}
}

Realtime.init(realtimeDataHandler);


Sidebar.sendToParent({
	'state': {
		'user': {
			'status': true,
			'id': {{ $user->id }},
			'name': '{{ $user->name }}'
		},
		'page': 'select',
		'project': {
			'id': {{ $project->id }},
			'title': '{{ $project->title }}',
			'permission': '{{ $permission }}'
		}
	}
});

Sidebar.onParentMessage(function(data) {
	console.log(data);
	switch (data.action) {
		case 'save-bookmark':
		var form = $('#create-bookmark-modal').modal('show');
		form.find('input[name=title]').val(data.title);
		form.find('input[name=url]').html(data.url).attr('href', data.url);
		break;

		case 'save-snippet':
		var form = $('#create-snippet-modal').modal('show');
		form.find('input[name=title]').val(data.title);
		form.find('input[name=url]').html(data.url).attr('href', data.url);
		if (data.text) {
			form.find('textarea[name=text]').html(data.text);
		}
		break;
	}
});

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

initializeBookmarkFormEventHandlers(bookmarkList);
initializeSnippetFormEventHandlers(snippetList);

</script>
@endsection('content')