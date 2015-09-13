<h3>Sidebar</h3>
<h4>Bookmarks</h4>

@foreach ($bookmarks as $bookmark)
	<li><a href="{{ $bookmark['url'] }}"> {{ $bookmark['title'] }} </a></li>
@endforeach

<script>
// TODO until Node server is up and running.
window.setTimeout(function(){
	window.location.reload();
}, 5000);
</script>