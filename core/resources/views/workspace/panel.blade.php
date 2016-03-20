@extends('workspace.layouts.main')

@section('page')
page-panel
@endsection

@section('main-content')

<div class='row panel'>
	<div class='column col-md-2'>
		<a href='/workspace/projects'><div class='pane projects'><span>Your Projects</span></div></a>
		<a href='/workspace/user/settings'><div class='pane user-settings'>User Settings</div></a>
	</div>
	<div class='column col-md-2'>
		<a href='/workspace/projects/sharedWithMe'><div class='pane shared-projects'>Shared Projects</div></a>
		<div class='pane explore-projects'>Explore Projects</div>
		<a href='/workspace/projects/create'><div class='pane new-project'>New Project</div></a>
	</div>
</div>

<script>
	(function() {
		function recalculatePanes () {
			$('.pane').each(function(index, element){
				var el = $(element);
				el.height(el.width());
			});
		}
		$(window).on('resize', recalculatePanes);
		recalculatePanes();
	}());
</script>

@endsection