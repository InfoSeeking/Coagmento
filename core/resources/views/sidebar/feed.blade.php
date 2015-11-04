<html>
<head></head>
<body>
	<h4>Bookmark Feed</h4>

	<ul id="bookmark_list">
	</ul>

	<script type='text/template' id='bookmarkTemplate'>
		<div><a target="_blank" href='<%= url %>'><%= title %></a></div>
		<p>
			Saved <%= created_at %>
		</p>
	</script>

	<script src='/js/vendor/socket.io.js'></script>
	<script src='/js/vendor/jquery-1.10.2.js'></script>
	<script src='/js/vendor/underscore.js'></script>
	<script src='/js/vendor/backbone.js'></script>
	<script src='/js/config.js'></script>
	<script src='/js/data.js'></script>
	<script src='/js/realtime.js'></script>

	<script>
	Config.setAll({
		permission: '{{ $permission }}',
		projectId: {{ $project->id }},
		userId: {{ $user->id }},
		realtimeEnabled: {{ env('REALTIME_SERVER') == null ? 'false' : 'true'}},
		realtimeServer: '{{ env('REALTIME_SERVER') }}'
	});

	var bookmarkList = new BookmarkCollection();
	bookmarkList.fetch();

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

	</script>
</body>
</html>