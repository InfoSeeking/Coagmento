<script type='text/template' data-template='bookmark' data-layout='coverflow'>
	<% if (thumbnail) { %>
    <img src="/images/thumbnails/large/<%= _.escape(thumbnail.image_large) %>"/>
    <% } %>
    <div class='overlay'>
		<a target="_blank" href='<%= _.escape(url) %>' class='link'><%= _.escape(title) %></a>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
			<div class='right'>
				<a data-id='<%= id %>' class='delete'><span class='fa fa-trash'></span></a>
				<a data-id='<%= id %>' class='edit'><span class='fa fa-pencil'></span></a>
			</div>
		<% } %>
	</div>
</script>

<script type='text/template' data-template='bookmark' data-layout='grid'>
	<% if (thumbnail) { %>
    <img class='thumbnail' src="/images/thumbnails/small/<%= _.escape(thumbnail.image_small) %>" />
    <% } %>
    <div class='overlay'>
		<a target="_blank" href='<%= _.escape(url) %>' class='link'><%= _.escape(title).substring(0,20) %></a>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
			<div class='right'>
				<a data-id='<%= id %>' class='delete'><span class='fa fa-trash'></span></a>
				<a data-id='<%= id %>' class='edit'><span class='fa fa-pencil'></span></a>
			</div>
		<% } %>
	</div>
</script>

<script type='text/template' data-template='bookmark' data-layout='list'>
	<div>
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
		Saved <%= created_at %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		<% } %>
	</p>
</script>