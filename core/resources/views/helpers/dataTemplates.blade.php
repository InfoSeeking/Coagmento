<!-- There are rendering templates for all data presented in the workspace -->
<!-- TODO: dates are assumed to be EST. Should we try to change this or just document it? -->

<script type='text/template' id='chat-template'>
	<div class="row">
		<div class="col-md-2">
			<p class='name'><%= _.escape(user.name) %></p>
			<p class='time'><%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %></p>
		</div>
		<div class="col-md-10">
			<span class='content'><%= _.escape(message) %></span>
		</div>
	</div>
</script>

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