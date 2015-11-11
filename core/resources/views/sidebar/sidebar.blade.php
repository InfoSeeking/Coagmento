<h3>Sidebar</h3>
<h4>Bookmarks</h4>

@foreach ($bookmarks as $bookmark)
	<li><a href="{{ $bookmark['url'] }}"> {{ $bookmark['title'] }} </a></li>
@endforeach

<script>

</script>