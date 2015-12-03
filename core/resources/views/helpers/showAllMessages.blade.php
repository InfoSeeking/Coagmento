<script src='/js/message.js'></script>
<div id="messageArea">
<!-- General errors are on the session -->
@if (Session::has('generalErrors'))
	<div class='messageList alert alert-danger'>
		<a href='#' class='close-btn'><span class='fa fa-close'></span></a>
		<ul>
		@foreach (Session::get('generalErrors') as $error)
			<li>{{ $error }}<li/>
		@endforeach
		</ul>
	</div>
@endif

<!-- Shows both general and (for now) validation errors -->
@if (count($errors) > 0)
    <div class='messageList alert alert-danger'>
    	<a href='#' class='close-btn'><span class='fa fa-close'></span></a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::has('successMessages'))
	<div class='messageList alert alert-success'>
		<a href='#' class='close-btn'><span class='fa fa-close'></span></a>
		<ul>
		@foreach (Session::get('successMessages') as $success)
			<li>{{ $success }}</li>
		@endforeach
		</ul>
	</div>
@endif

@if (Session::has('infoMessages'))
	<div class='messageList alert alert-info'>
		<a href='#' class='close-btn'><span class='fa fa-close'></span></a>
		<ul>
		@foreach (Session::get('infoMessages') as $info)
			<li>{{ $info }}</li>
		@endforeach
		</ul>
	</div>
@endif

<script type='text/template' id='messageTemplate'>
<div class='messageList alert alert-<%= className %>'>
	<a href='#' class='close-btn'><span class='fa fa-close'></span></a>
	<ul>
	<% _.each(items, function(item){ print("<li>" + item + "</li>")}) %>
	</ul>
</div>
</script>

</div>

<script>
    MessageDisplay.init();
</script>