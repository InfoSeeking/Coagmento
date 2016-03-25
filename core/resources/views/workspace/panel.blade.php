@extends('workspace.layouts.main')

@section('page')
page-panel
@endsection

@section('main-content')

<!--
<div class='row panel'>
	<div class='column col-md-2'>
		<a href='/workspace/projects'><div class='pane projects'><span>Your Projects</span></div></a>
		<a href='/workspace/user/settings'><div class='pane user-settings'>User Settings</div></a>
	</div>
	<div class='column col-md-2'>
		<a href='/workspace/projects/sharedWithMe'><div class='pane shared-projects'>Shared Projects</div></a>
		<a href='/workspace/projects/create'><div class='pane new-project'>New Project</div></a>
	</div>
</div>
-->

<div class='col-md-8'>
	<p>Welcome to your Coagmento Workspace. Coagmento is still under active development, but you can follow the development on our <a href="https://github.com/InfoSeeking/Coagmento" target="_blank">GitHub page</a>.
	</p>
	<a class='action-button' href='/workspace/projects'>
		<h5><span class='fa fa-folder-open-o'></span> View your Projects</h5>
		<p>You currently have {{ $projectCount }} projects.</p>
		<div class='right-arrow'>&raquo;</div>
	</a>
	<a class='action-button' href='/workspace/projects/sharedWithMe'>
		<h5><span class='fa fa-users'></span> View Projects Shared with you</h5>
		<p>You are currently a member of {{ $sharedProjectCount }} other projects.</p>
		<div class='right-arrow'>&raquo;</div>
	</a>
	<a class='action-button' href='/workspace/projects/create'>
		<h5><span class='fa fa-plus-square-o'></span> Create a New Project</h5>
		<p>A project will hold all of your bookmarks, snippets, documents, and data you save while you research.</p>
		<div class='right-arrow'>&raquo;</div>
	</a>
	<a class='action-button' href='/workspace/user/settings'>
		<h5><span class='fa fa-user'></span> View your User Settings</h5>
		<p>Update your email and profile picture.</p>
		<div class='right-arrow'>&raquo;</div>
	</a>
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