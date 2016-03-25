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

<script type='text/template' data-template='chat' data-layout='sidebar'>
	<span class='chat-name pull-left'>
		<%= _.escape(user.name) %>&nbsp;
	</span>

	<div class='chat-body clearfix'>
		<p> <%= _.escape(message) %> </p>
	</div>
</script>