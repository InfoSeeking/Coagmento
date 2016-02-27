<script type='text/template' id='doc-template'>
	<div>
		<a href='/workspace/projects/<%= project_id %>/docs/<%= id %>'><%= _.escape(title) %></a>
	</div>
	<p>
		Saved <%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		<% } %>
	</p>
</script>