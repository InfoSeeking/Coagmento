<script type='text/template' id='bookmark-template'>
	<div>
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