Select a project:

<ul>
@foreach($projects as $project)
<li><a href='/sidebar/{{$project->id}}'>{{$project->title}}</a>
{{$project->level}}
</li>
@endforeach
</ul>