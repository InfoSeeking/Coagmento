<!-- General errors are on the session -->
@if (Session::has('generalErrors'))
	<div class='messageList alert alert-danger'>
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
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::has('successMessages'))
	<div class='messageList alert alert-success'>
		<ul>
		@foreach (Session::get('successMessages') as $success)
			<li>{{ $success }}</li>
		@endforeach
		</ul>
	</div>
@endif

@if (Session::has('infoMessages'))
	<div class='messageList alert alert-info'>
		<ul>
		@foreach (Session::get('infoMessages') as $info)
			<li>{{ $info }}</li>
		@endforeach
		</ul>
	</div>
@endif