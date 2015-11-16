<h2>Select a project</h2>

<ul>
@foreach($projects as $project)
<li><a target="_self" href='/sidebar/{{$project->id}}'>{{$project->title}}</a>
{{$project->level}}
</li>
@endforeach
</ul>

<script src='/js/sidebar.js'></script>
<script>
Sidebar.onParentMessage(function(evt){
	console.log('Parent window message received.');
	console.log(evt.data);
});

Sidebar.sendToParent({
	'state': {
		'user': {
			'status': true,
			'id': {{ $user->id }},
			'name': '{{ $user->username }}'
		},
		'page': 'select'
	}
});
</script>