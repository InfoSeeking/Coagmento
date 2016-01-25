@extends('workspace.layouts.single-project')

@section('page')
page-activity
@endsection

@section('navigation')
<a href='/workspace/projects'><span class='fa fa-folder-open-o'></span> Projects</a>
<span class='fa fa-angle-right'></span>
<a href='/workspace/projects/{{ $project->id }}'>{{ $project->title }}</a>
@endsection('navigation')

@section('main-content')

<div class='row'>
	@include('helpers.showAllMessages')
	
    <div class='col-md-4'>
    	<!--
    	<canvas id="canvas" height="200" width="300"></canvas>
    	<p><small>* This data is random and only a temporary placeholder</small></p>
    	-->
		<!-- <h3>Statistics</h3> -->
    	@if ($permission == 'o')
    	<p>There are {{ count($sharedUsers)}} collaborators. <a href='/workspace/projects/{{ $project->id }}/settings'>Share</a> with others.</p>
    	@endif
		<p>{{ count($bookmarks) }} bookmarks have been saved in total.</p>
		<p>{{ count($snippets) }} snippets have been saved in total.</p>
	</div>
</div>

<script src='/js/vendor/chart.js'></script>
<script>
var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
var lineChartData = {
	labels : ["January","February","March","April","May","June","July"],
	datasets : [
		{
			label: "Bookmarks saved",
			fillColor : "rgba(220,220,220,0.2)",
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(220,220,220,1)",
			data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
		},
		{
			label: "Snippets saved",
			fillColor : "rgba(151,187,205,0.2)",
			strokeColor : "rgba(151,187,205,1)",
			pointColor : "rgba(151,187,205,1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(151,187,205,1)",
			data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
		}
	]
}
/*
window.onload = function(){
	var ctx = document.getElementById("canvas").getContext("2d");
	window.myLine = new Chart(ctx).Line(lineChartData, {});
}
*/
</script>

@endsection('main-content')