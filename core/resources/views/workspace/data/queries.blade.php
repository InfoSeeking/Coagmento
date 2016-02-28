<!-- Delete query confirmation window -->
<div class='row modal fade' tabindex='-1' id='delete-query-modal'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>Delete Query</div>
			<div class='modal-body'>
				<form action='/api/v1/queries' method='delete' id='delete-query'>
					<p>Are you sure you want to delete this query? This cannot be undone.</p>
					<input type='hidden' name='query_id' value='' />
					<button class='cancel btn btn-danger' data-dismiss='modal'>Close</button>
					<div class='pull-right'>
						<button type='submit' class='btn btn-primary'>Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<script type='text/template' data-template='query' data-layout='list'>
	<div>
	<% if(search_icon) { %>
	<img src='/images/search-engines/<%= search_icon %>.png' title=<%= search_engine %> />
		<%= _.escape(text) %>
	</div>
	<% } %>
	<p> Saved <%= moment(created_at).subtract(5, 'hours').format('MMM Mo h:mma') %> </p>
	<p>By <%= user_name %>
		<% if(Config.get('permission') == 'w' || Config.get('permission') == 'o') { %>
		| <a data-id='<%= id %>' class='delete'>Delete</a>
		<% } %>
	</p>
</script>