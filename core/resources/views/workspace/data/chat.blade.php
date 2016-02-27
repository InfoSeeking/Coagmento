<script type='text/template' data-template='chat' data-layout='list'>
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