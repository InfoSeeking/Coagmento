<!-- Includes the rendering templates and the edit/delete/create forms. -->
<!-- Depends on moment.js -->

<!-- Create bookmark modal window -->
<div class='row modal fade' tabindex='-1' id='create-bookmark-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Create Bookmark</div>
			<div class='modal-body'>
				<form action='/api/v1/bookmarks' method='post' id='create-bookmark'>
					<div class='form-group'>
						<input class='form-control' type='text' name='url' placeholder='Bookmark URL'/>
					</div>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Page title'/>
					</div>
					<div class='form-group'>
						<textarea class='form-control' name='notes' placeholder='Notes'></textarea>
					</div>
					<div class='form-group'>
						<input class='form-control' type='text' name='tags' placeholder='Comma separated tags' />
					</div>
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Create</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Edit bookmark modal window -->
<div class='row modal fade' tabindex='-1' id='edit-bookmark-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Edit Bookmark</div>
			<div class='modal-body'>
				<form action='/api/v1/bookmarks' method='put' id='edit-bookmark'>
					<p name='url'></p>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Page title'/>
					</div>
					<div class='form-group'>
						<textarea class='form-control' name='notes' placeholder='Notes'></textarea>
					</div>
					<input type='hidden' name='bookmark_id' />
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Delete bookmark confirmation window -->
<div class='row modal fade' tabindex='-1' id='delete-bookmark-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Delete Bookmark</div>
			<div class='modal-body'>
				<form action='/api/v1/bookmarks' method='delete' id='delete-bookmark'>
					<p>Are you sure you want to delete this bookmark? This cannot be undone.</p>
					<input type='hidden' name='bookmark_id' value='' />
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<script type='text/template' data-template='bookmark' data-layout='three-d'>
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
				<a data-id='<%= id %>' class='edit'><span class='fa fa-pencil'></span></a>
			</div>
		<% } %>
	</div>
</script>

<script type='text/template' data-template='bookmark' data-layout='coverflow'>
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
				<a data-id='<%= id %>' class='edit'><span class='fa fa-pencil'></span></a>
			</div>
		<% } %>
	</div>
</script>

<script type='text/template' data-template='bookmark' data-layout='grid'>
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
					<a data-id='<%= id %>' class='edit'><span class='fa fa-pencil'></span></a>
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

<script type='text/template' data-template='bookmark' data-layout='list'>
	<div>
		<a target="_blank" href='<%= _.escape(url) %>'><%= _.escape(title) %></a>
		<% if(notes) { %>
		<p>Notes: <%= _.escape(notes) %></p>
		<% } %>
	</div>
	<p>
		Saved <%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		| <a data-id='<%= id %>' class='edit'>Edit</a>
		<% } %>
	</p>
</script>