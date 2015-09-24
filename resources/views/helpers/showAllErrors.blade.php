<!-- General errors are on the session -->
@if (Session::has('generalErrors'))
	<div class='alert alert-danger'>
		<ul>
		@foreach (Session::get('generalErrors') as $error)
			<li>{{ $error }}<li/>
		@endforeach
		</ul>
	</div>
@endif

<!-- Shows both general and (for now) validation errors -->
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif