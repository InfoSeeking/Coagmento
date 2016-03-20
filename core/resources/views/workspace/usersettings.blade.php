@extends('workspace.layouts.project')

@section('page')
page-user-settings
@endsection

@section('navigation')
<a href='/workspace/user/settings'><span class='fa fa-user'></span> User Settings</a>
@endsection('navigation')

@section('main-content')
<div class='row'>
	@include('helpers.showAllMessages')
	<div class='col-md-4'>
		<p>Update your user information.</p>
		<form action='/workspace/user/settings' method='post' enctype='multipart/form-data'>
			<div class='form-group'>
				<label>Name</label><input class='form-control' type='text' placeholder='Your name' value='{{ $user->name }}' name='name'/>
			</div>
			<div class='form-group'>
				<label>Email</label><input class='form-control' type='email' placeholder='Please provide an email' 
					value='{{ strpos($user->email, 'coagmento.org') !== false ? '' : $user->email }}' name='email'/>
			</div>

			<div class='form-group'>
				<label>Profile Image</label>
				@if ($user->avatar)
					<p>
						<img class='profile-image' src='/images/users/{{ $user->id }}.png' />
					</p>
				@endif
				<p>
					<small>File must be of type png, jpg, or gif. Images are scaled down to 200 x 200 after upload.</small>
				</p>
				<input type='file' class='form-control' name='avatar' />
			</div>

			<div class='form-group'>
				<button type='submit' class='btn btn-primary pull-right'>Update</button>
			</div>
		</form>
	</div>
</div>
@endsection