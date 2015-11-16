<html>
<head>
	<style>
		a {
			cursor: pointer;
		}
		a:hover {
			text-decoration: underline;
		}
	</style>
</head>
<body>
	@include('helpers.showAllMessages')

	<form id='save-bookmark' style='display:none'>
		<h4>Save Bookmark</h4>
		<a href='#' class='url'></a><br/>
		<input type='text' name='title' placeholder='Page title'/><br/>
		<textarea name='notes' placeholder='Notes'></textarea><br/>
		<input type='text' name='tags' placeholder='Comma separated tags' /><br/>
		<button type='submit'>Save</button><br/>
	</form>

	<form id='save-snippet' style='display:none'>
		<h4>Save Snippet</h4>
		<a href='#' class='url'></a><br/>
		<input type='text' name='title' placeholder='Page title'/><br/>
		<textarea name='text' placeholder='Text'></textarea><br/>
		<button type='submit'>Save</button><br/>
	</form>

	<h4>Bookmarks</h4>
	<ul id='bookmark-list'>
	</ul>

	<h4>Snippets</h4>
	<ul id='snippet-list'>
	</ul>

	@include('helpers.dataTemplates')

	<script src='/js/vendor/socket.io.js'></script>
	<script src='/js/vendor/jquery-1.10.2.js'></script>
	<script src='/js/vendor/underscore.js'></script>
	<script src='/js/vendor/backbone.js'></script>
	<script src='/js/config.js'></script>
	<script src='/js/message.js'></script>
	<script src='/js/data/bookmark.js'></script>
	<script src='/js/data/snippet.js'></script>
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

	var bookmarkList = new BookmarkCollection();
	bookmarkList.fetch({
		data: {
			project_id: Config.get('projectId')
		}
	});

	var bookmarkListView = new BookmarkListView({collection: bookmarkList});

	var snippetList = new SnippetCollection();
	snippetList.fetch({
		data: {
			project_id: Config.get('projectId')
		}
	});

	var snippetListView = new SnippetListView({collection: snippetList});


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
			var form = $('#save-bookmark').fadeIn();
			form.find('input[name=title]').val(data.title);
			form.find('.url').html(data.url).attr('href', data.url);
			break;

			case 'save-snippet':
			var form = $('#save-snippet').fadeIn();
			form.find('input[name=title]').val(data.title);
			form.find('.url').html(data.url).attr('href', data.url);
			if (data.text) {
				form.find('textarea[name=text]').html(data.text);
			}
			break;
		}
	});


	$('#save-bookmark').on('submit', function(e){
		e.preventDefault();
		var form = $(this),
			urlLink = form.find('.url'),
			titleInput = form.find('input[name=title]'),
			notesInput = form.find('textarea[name=notes]'),
			tagsInput = form.find('input[name=tags]');

		$.ajax({
			url: '/api/v1/bookmarks',
			method: 'post',
			data: {
				project_id : Config.get('projectId'),
				url: urlLink.html(),
				title: titleInput.val(),
				tags: tagsInput.val().split(/\s*,\s*/),
				notes: notesInput.val()
			},
			dataType: 'json',
			success: function(response) {
				bookmarkList.add(new BookmarkModel(response.result));
				$('#save-bookmark').fadeOut();
			},
			error: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				if (json) {
					MessageDisplay.displayIfError(json);
				}
			}
		});
	});

	$('#save-snippet').on('submit', function(e){
		e.preventDefault();
		var projectId = Config.get('projectId'),
			textInput = $(this).find('textarea[name=text]'),
			urlLink = $(this).find('.url'),
			titleInput = $(this).find('input[name=title]');
		$.ajax({
			url: '/api/v1/snippets',
			method: 'post',
			data: {
				'project_id' : projectId,
				'text': textInput.val(),
				'url': urlLink.html(),
				'title': titleInput.val()
			},
			complete: function(xhr) {
				var json = JSON.parse(xhr.responseText);
				if (json) {
					MessageDisplay.displayIfError(json);
				}	
			},
			success: function(response) {
				snippetList.add(new SnippetModel(response.result));
				$('#save-snippet').fadeOut();
			}
		});
	});

	</script>
</body>
</html>