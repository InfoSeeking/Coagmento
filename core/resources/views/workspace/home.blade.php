@extends('layouts.workspace.main')

@section('content')

<div class="row">
<div class='col-sm-2'>
</div>
<div class='col-sm-10 main-content'>
	@include('helpers.showAllMessages')
	<h2>Welcome to Coagmento 2.0</h2>
	<p>
		This is your new Coagmento workspace. Here you can easily manage and share your projects and see updates on projects you follow.
	</p>
</div>
</div>


@endsection('content')