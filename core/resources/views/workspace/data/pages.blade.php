<!-- Delete page confirmation window -->
<div class='row modal fade' tabindex='-1' id='delete-page-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Delete Page</div>
			<div class='modal-body'>
				<form action='/api/v1/pages' method='delete' id='delete-page'>
					<p>Are you sure you want to delete this page? This cannot be undone.</p>
					<input type='hidden' name='page_id' value='' />
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<script type='text/template' data-template='page' data-layout='three-d'>
	<img
    <% if (thumbnail) { %>
    src="/images/thumbnails/large/<%= _.escape(thumbnail.image_large) %>"
    <% } else { %>
    src="/images/thumbnails/large/generating.png"
    <% } %>
    />
    <div class='overlay'>
		<a target="_blank" href='<%= _.escape(url) %>' class='link'><%= _.escape(title) %></a>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
			<div class='right'></div>
		<% } %>
	</div>
</script>

<script type='text/template' data-template='page' data-layout='coverflow'>
	<img
    <% if (thumbnail) { %>
    src="/images/thumbnails/large/<%= _.escape(thumbnail.image_large) %>"
    <% } else { %>
    src="/images/thumbnails/large/generating.png"
    <% } %>
    />
    <div class='overlay'>
		<a target="_blank" href='<%= _.escape(url) %>' class='link'><%= _.escape(title) %></a>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
			<div class='right'>
				<a data-id='<%= id %>' class='delete'><span class='fa fa-trash'></span></a>
			</div>
		<% } %>
	</div>
</script>

<script type='text/template' data-template='page' data-layout='grid'>
    <img class='thumbnail'
    <% if (thumbnail) { %>
    src="/images/thumbnails/small/<%= _.escape(thumbnail.image_small) %>"
    <% } else { %>
    src="/images/thumbnails/small/generating.png"
    <% } %>
    />
    <div class='overlay'>
    	<div class='top'>
			<a target="_blank" href='<%= _.escape(url) %>' class='link'><%= _.escape(title).substring(0,20) %></a>
			<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
				<div class='right'>
					<a data-id='<%= id %>' class='delete'><span class='fa fa-trash'></span></a>
				</div>
			<% } %>
		</div>
		<!-- Bottom is hidden until hover. -->
		<div class='bottom'>
			<p>Saved <%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %></p>
			<p>By <%= user_name %></p>
		</div>
	</div>
</script>

<script type='text/template' data-template='page' data-layout='list'>
	<div>
		<a target="_blank" href='<%= _.escape(url) %>'><%= _.escape(title) %></a>
	</div>
	<p> Saved <%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %> </p>
	<p>By <%= user_name %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		<% } %>
	</p>
</script>