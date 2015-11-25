<script type='text/template' id='bookmark-template'>
	<div>
		<% if (thumbnail) { %>
	    <img class='thumbnail' src="/images/thumbnails/small/<%= thumbnail.image_small %>" />
	    <% } %>
		<a target="_blank" href='<%= url %>'><%= title %></a>
		<% if(notes) { %>
		<p>Notes: <%= notes %></p>
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
		<a target="_blank" href='<%= url %>'><%= title %></a>
		<p><%= text %>
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
<span class='name'><%= user.name %></span>
<span class='content'><%= message %></span>
<span class='time'><%= created_at %></span>
</script>