<!-- Create snippet modal window -->
<div class='row modal fade' tabindex='-1' id='create-snippet-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Create Snippet</div>
			<div class='modal-body'>
				<form action='/api/v1/snippets' method='post' id='create-snippet'>
					<div class='form-group'>
						<input class='form-control' type='text' name='url' placeholder='Snippet URL'/>
					</div>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Page title'/>
					</div>
					<div class='form-group'>
						<textarea class='form-control' name='text' placeholder='Snippet text'></textarea>
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

<!-- Edit snippet modal window -->
<div class='row modal fade' tabindex='-1' id='edit-snippet-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Edit Snippet</div>
			<div class='modal-body'>
				<form action='/api/v1/snippets' method='put' id='edit-snippet'>
					<p name='url'></p>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Page title'/>
					</div>
					<div class='form-group'>
						<textarea class='form-control' name='text' placeholder='Text'></textarea>
					</div>
					<input type='hidden' name='snippet_id' />
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Delete snippet confirmation window -->
<div class='row modal fade' tabindex='-1' id='delete-snippet-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Delete Snippet</div>
			<div class='modal-body'>
				<form action='/api/v1/snippets' method='delete' id='delete-snippet'>
					<p>Are you sure you want to delete this snippet? This cannot be undone.</p>
					<input type='hidden' name='snippet_id' value='' />
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Rendering templates -->

<script type='text/template' data-template='snippet' data-layout='grid'>
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
			<p><%= _.escape(text).substring(0,50) %></p>
			<p>Saved <%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %></p>
			<!-- <p>By user_name </p> -->
		</div>
	</div>
</script>

<script type='text/template' data-template='snippet' data-layout='list'>
	<div>
		<a target="_blank" href='<%= _.escape(url) %>'><%= _.escape(title) %></a>
		<p><%= _.escape(text) %>
	</div>
	<p>
		Saved <%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		| <a data-id='<%= id %>' class='edit'>Edit</a>
		<% } %>
	</p>
</script>