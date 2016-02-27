<script type='text/template' data-template='doc' data-layout='list'>
	<div>
		<a href='/workspace/projects/<%= project_id %>/docs/<%= id %>'><%= _.escape(title) %></a>
	</div>
	<p>
		Saved <%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='edit'>Edit</a> | <a data-id='<%= id %>' class='delete'>Delete</a>
		<% } %>
	</p>
</script>

<!-- Modal forms -->
<!-- Create doc modal window -->
<div class='row modal fade' tabindex='-1' id='create-doc-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Create Document</div>
			<div class='modal-body'>
				<form action='/api/v1/docs' method='post' id='create-doc'>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Document title'/>
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

<!-- Edit doc modal window -->
<div class='row modal fade' tabindex='-1' id='edit-doc-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Edit Document</div>
			<div class='modal-body'>
				<form action='/api/v1/docs' method='put' id='edit-doc'>
					<p name='url'></p>
					<div class='form-group'>
						<input class='form-control' type='text' name='title' placeholder='Document title'/>
					</div>
					<input type='hidden' name='doc_id' />
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Delete doc confirmation window -->
<div class='row modal fade' tabindex='-1' id='delete-doc-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Delete Document</div>
			<div class='modal-body'>
				<form action='/api/v1/docs' method='delete' id='delete-doc'>
					<p>Are you sure you want to delete this document? This cannot be undone.</p>
					<input type='hidden' name='doc_id' value='' />
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>