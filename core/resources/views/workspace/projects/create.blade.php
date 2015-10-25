@extends('layouts.workspace.project')
@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/create'>New</a>
@endsection('navigation')

@section('page-content')

@include('helpers.showAllMessages')
<div class='col-sm-6'>
	<form class='form-horizontal create-form' action='/workspace/projects/create' method='post'>
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
				<label for='inputProjectDescription'>Project Description</label>
				<small>(Optional)</small>
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
				<a class='btn btn-danger' href='/workspace/projects'>Cancel</a>
				<button type='submit' class="btn btn-primary pull-right">Create Project</button>
			</div>
		</div>
	</form>
</div>


@endsection('page-content')