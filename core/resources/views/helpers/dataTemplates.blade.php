<script type='text/template' id='bookmark-template'>
	<div>
		<% if (thumbnail) { %>
	    <img class='thumbnail' src="/images/thumbnails/small/<%= _.escape(thumbnail.image_small) %>" />
	    <% } %>
		<a target="_blank" href='<%= _.escape(url) %>'><%= _.escape(title) %></a>
		<% if(notes) { %>
		<p>Notes: <%= _.escape(notes) %></p>
		<% } %>
	</div>
	<p>
		Saved <%= created_at %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		| <a data-id='<%= id %>' class='edit'>Edit</a>
		<% } %>
	</p>
</script>

<script type='text/template' id='snippet-template'>
	<div>
		<a target="_blank" href='<%= _.escape(url) %>'><%= _.escape(title) %></a>
		<p><%= _.escape(text) %>
	</div>
	<p>
		Saved <%= created_at %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		| <a data-id='<%= id %>' class='edit'>Edit</a>
		<% } %>
	</p>
</script>

<script type='text/template' id='chat-template'>
	<span class='name'><%= _.escape(user.name) %></span>
	<span class='content'><%= _.escape(message) %></span>
	<span class='time'><%= _.escape(created_at) %></span>
</script>

<script type='text/template' id='doc-template'>
	<div>
		<a href='/workspace/projects/<%= project_id %>/docs/<%= id %>'><%= _.escape(title) %></a>
	</div>
	<p>
		Saved <%= created_at %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		<% } %>
	</p>
</script>