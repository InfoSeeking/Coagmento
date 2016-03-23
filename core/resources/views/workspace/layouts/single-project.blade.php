@extends('workspace.layouts.main')

@section('sidebar')
<ul>
    <li><a href='/workspace/projects/{{ $project->id }}' class='link-history'><span class='fa fa-line-chart'></span> Activity<div class='highlight'></div></a></li>
    <li><a href='/workspace/projects/{{ $project->id }}/bookmarks' class='link-bookmarks'><span class='fa fa-star-o'></span> Bookmarks<div class='highlight'></div></a></li>
    <li><a href='/workspace/projects/{{ $project->id }}/snippets' class='link-snippets'><span class='fa fa-sticky-note-o'></span> Snippets<div class='highlight'></div></a></li>
    <li><a href='/workspace/projects/{{ $project->id }}/chat' class='link-chat'><span class='fa fa-comment'></span> Group Chat<div class='highlight'></div></a></li>
    <li><a href='/workspace/projects/{{ $project->id }}/docs' class='link-docs'><span class='fa fa-file-text-o'></span> Documents<div class='highlight'></div></a></li>
    <li><a href='/workspace/projects/{{ $project->id }}/settings' class='link-settings'><span class='fa fa-cog'></span> Project Settings<div class='highlight'></div></a></li>
</ul>

<div class='stats'>
	<h4>Project Statistics</h4>
	<p><span class='tag'>Bookmarks</span> <span class='bookmarks'>{{ $stats['bookmarks'] }}</span></p>
	<p><span class='tag'>Queries</span> <span class='queries'>{{ $stats['queries'] }}</span></p>
	<p><span class='tag'>Snippets</span> <span class='snippets'>{{ $stats['snippets'] }}</span></p>
	<p><span class='tag'>Web Pages</span> <span class='pages'>{{ $stats['pages'] }}</span></p>
	<p><span class='tag'>Documents</span> <span class='docs'>{{ $stats['docs'] }}</span></p>
</div>

<script>
	function updateStats(param) {
		// Update stats.
		var delta = 0;
		if (param.action == 'create') delta = 1;
		if (param.action == 'delete') delta = -1;
		var statEl = $('.stats .' + param.dataType);
		if (statEl.size() > 0) statEl.html(parseInt(statEl.html()) + delta);
	}
</script>

@yield('context')
@endsection('sidebar')