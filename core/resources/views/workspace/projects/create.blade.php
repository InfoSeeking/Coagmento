@extends('layouts.workspace.project')

@section('page-content')

@include('helpers.showAllMessages')
<div class='col-sm-6'>
	<form class='form-horizontal' action='/workspace/projects/create' method='post'>
		<div class='form-group'>
			<label class='col-sm-4 control-label'>Create a Project</label>	
		</div>

		<div class='form-group'>
			<label for='inputProjectTitle' class='col-sm-4 control-label'>Project Title</label>
			<div class='col-sm-8'>
				<input type='text' maxlength='512' class='form-control' id='inputProjectTitle' name='title' />
			</div>
		</div>
		
		<div class='form-group'>
			<div class='col-sm-4 control-label'>
				<div class='row'>
					<label for='inputProjectDescription'>Project Description</label>
				</div>
				<div class='row'><small>(Optional)</small></div>
			</div>
			<div class='col-sm-8'>
				<textarea class='form-control' id='inputProjectDescription' name='description'></textarea>
			</div>
		</div>

		<div class='form-group'>
			<label for='inputVisibility' class='col-sm-4 control-label'>Visibility</label>
			<div class='col-sm-8'>
				<label>
					<input type='radio' name='visibility' id='inputVisibility' value='public' checked/>
					Public
				</label>
				<p>Public projects are publicly searchable and can be viewed by others.</p>
				<label>
					<input type='radio' name='visibility' id='inputVisibility' value='public'/>
					Private
				</label>
				<p>Private projects are unlisted and restricted to project members.</p>
			</div>
		</div>

		<div class='form-group'>
			<div class='col-sm-4'></div>
			<div class='col-sm-8'>
				<button type='submit'>Create Project</button>
			</div>
		</div>
	</form>
</div>


@endsection('page-content')