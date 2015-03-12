<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>CSpace</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="style/zoomooz.css" type="text/css" media="screen">
		<link rel="stylesheet" href="style/website.css" type="text/css" media="screen">
		<link rel="stylesheet" href="style/prettyPhoto.css" type="text/css" media="screen">
		
		<script type="text/javascript" src="lib/jquery-1.4.4.js"></script>
		<script type="text/javascript" src="lib/sylvester.js"></script>
		<script type="text/javascript" src="lib/jquery.masonry.min.js"></script>
		<script type="text/javascript" src="lib/jquery.prettyPhoto.js"></script>
		<script type="text/javascript" src="js/purecssmatrix.js"></script>
		<script type="text/javascript" src="js/jquery.animtrans.js"></script>
		<script type="text/javascript" src="js/jquery.zoomooz.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function() {
			   $(".section, .highlight").click(function(evt) {
					$(this).zoomTo({targetsize:0.75, duration:600});
					evt.stopPropagation();
				});
				$(window).click(function(evt) {
					$(".chug").zoomTo({targetsize:1.0, duration:600});
					evt.stopPropagation();
				});
				$(".chug").zoomTo({targetsize:1.0, duration:600});
				
				$(".innerContent").masonry({columnWidth:210,animate:false});
				
				$(document).ready(function(){
				    var videoLink = $("a[rel^='prettyPhoto']");
		            videoLink.prettyPhoto();
		            videoLink.click(function() {
		                $(".chug").zoomTo({targetsize:1.0, duration: 0.0});
				    });
	            });
			});
		</script>
	</head>
	<body>
    
    <div class="chug">
    <?php
	
	echo '<link href="css/style.css" rel="stylesheet" type="text/css" />';
	
	require_once("../connect.php"); 
	
	$userID = 2;
	$title = "Coagmento";
	
	$query = "SELECT * FROM users WHERE userID=$userID";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$firstName = $line['firstName'];
	$lastName = $line['lastName'];
	
	?>
	
	    <div id="title">
	        <h2 id="topsubtitle">User: <? echo "$firstName $lastName" ?></h2>
		    <h1>CSpace</h1>
		    <div class="topdescription">
		        Welcome to your CSpace.  Navigate through your search results by clicking on the thumbnails to view details, and the surrounding area to zoom out.
		    </div>
            <div class="navigation">
            	<a href="1.php"><font color="#999">Today</font></a>
                <a href="2.php">Yesterday</a>
                <a href="3.php">Two Days Ago</a>
                <a href="4.php">One Week Ago</a>
                <a href="index.php">All</a>
            </div>
		</div>
        
         <? 
		 $today = "2009-04-26";
		 $yesterday = "2009-04-25"; ?>
		 
 			$userID = 2;
		
		if (!isset($_GET['page']))
			$pageNum = 1;
		else
			$pageNum = $_GET['page'];
	
		$min = $pageNum*25-24;
		$max = $pageNum*25;
		$objects = $_GET['objects'];
		if (!$objects)
			$objects = 'pages';
		$projectID = $_GET['projectID'];
		$session = $_GET['session'];
		$orderBy = $_GET['orderby'];
		$projectID = $_GET['projectID'];
		
		if (!$orderBy)
			$orderBy = 'timestamp';
?>

<table class="body" width=100%>
	<tr><td><div id="message"></div></td></tr>
		<form id="form1" action="log.php" method="GET">
		<table class="body" border=0>
		    <tr>
    		<td>
			<select id="projectID">
		      <option value="" selected="selected">Project:</option>
		      <?php
			  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
				$results = mysql_query($query) or die(" ". mysql_error());
				while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
					$projID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					echo "<option value=\"$projID\" ";
					if ($projID==$projectID)
						echo "SELECTED";
					echo ">$title</option>\n";
				}
		      ?>
		    </select>
		    </td>
    		<td>
			<select id="session">
		      <option value="" selected="selected">Session:</option>
		      <?php
			  	$query = "SELECT distinct date FROM pages WHERE userID='$userID' AND source!='coagmento' ORDER BY date desc";
				$results = mysql_query($query) or die(" ". mysql_error());
				while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
					$date = $line['date'];
					echo "<option value=\"$date\" ";
					if ($date==$session)
						echo "SELECTED";
					echo ">$date</option>\n";
				}
		      ?>
		    </select>
		    </td>
    		<td>
			<select id="objects">
		      <option value="" <?php if (!$objects) echo "SELECTED";?>>Objects:</option>
		      <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?>>Webpages</option>
		      <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?>>Bookmarks</option>
		      <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?>>Searches</option>
		      <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?>>Snippets</option>
		      <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?>>Annotations</option>
		    </select>
		    </td>
			<td>&nbsp;<input type="button" value="Filter" onClick="filterAllData();" />&nbsp;&nbsp; <span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('allData.php?objects=pages','content');">Show All</span></td>
			<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php // include("pagingnav2.php");?></td>
    		</tr>
    		<?php
    			if (!$projectID)
    				$projID = 0;
    			else
    				$projID = $projectID;
    			if (!$session)
    				$sess = 0;
    			else
    				$sess = $session;
 				echo "<tr><td colspan=5><input type=\"text\" size=40 id=\"searchString\" value=\"$searchString\" onKeyDown=\"if (event.keyCode == 13) document.getElementById('sButton').click();\"/> <input type=\"button\" id=\"sButton\" value=\"Search\" onclick=\"searchAll($projID, '$objects', '$sess');\"/></td></tr>";   				
    		?>
 		</table>
 
 
		 <? echo "<div class='records'>Displaying records for <font color='#999'>$today</font></div>" ?>
		
	    <div class="innerContent">
	
		<!--************************
		**  TITLE                 **
		*************************-->
        
        <? $query = "SELECT * FROM pages ORDER BY date,time LIMIT 100";
	$results = mysql_query($query) or die(" ". mysql_error());
	$records = 0;
	$count = 1;
	
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$title = $line['title'];
		$url = $line['url'];
		$date = $line['date'];
		$time = $line['time'];
		$saved = $line['result'];
					
		if($date == $today) {
			if($saved) {
				echo "<div class='section' id='description'>$count) <a href=\"$url\">$title</a> <br /> <img src='website-images/screenshot.png' width='100' height='56'> <br /> $date $time<br/></div>";
			}
			else {
				echo "<div class='section'>$count) <a href=\"$url\">$title</a> <br /> <img src='website-images/screenshot.png' width='100' height='56'> <br /> $date $time<br/></div>";
			}
		$records++; $count++; }
	}
	 ?>
    
    </div>
    <br /><br />
	
	<!--     <div class="section" id="video">
            <a href="http://www.youtube.com/watch?v=faSRI1iAang" rel="prettyPhoto">
                <img src="website-images/video.jpg" width="350" height="259" />
                Zoomooz on iPad video
            </a>
            
            </div>
	
	    <div class="section" id="description">
		    <p>
		        <a class="downloadlink" href="http://github.com/jaukia/zoomooz/zipball/master">Download Zoomooz.js (zip)</a>
		    </p>
		    <div id="version">Version 0.87 (October 26, 2011, fixed a bug with settings and a couple of demos)</div>
            <p>
            <a class="downloadlink" href="http://github.com/jaukia/zoomooz/" rel="me">Fork me on GitHub!</a>
		    </p>
        </div>
			
		<div class="section" id="gettingstarted">
		        <h2>Praise</h2>
		        <p>
		        "The fantastic Javascript zooming library has been updated to work with Firefox 4."<br />
		        — @azaaza
		        </p>
		        
		        <p>
		        "Really awesome example of a zoomable interface with JavaScript"<br />
		        — @tdhooper
		        </p>
		        
		        <p>
		        "Amazing little js library for zooming and rotating that works with JQuery"<br />
		        — @mike_j_edwards
		        </p>
		        
		        <p>
		        "#prezi like styles for your own website. damn cool and pretty easy"<br />
		        — @wollepb
		        </p>
		        
		</div>
		
   	
		<div class="section" id="gettingstarted">
		        <h2>Zoomooz in use</h2>
		 
		        <p>
		        <a href="http://www.azarask.in/blog/post/how-to-prototype-and-influence-people/">Aza Raskin prototyping with Zoomooz</a>
		        </p>
		        
		        <p>
		        <a href="http://www.freshwidows.com/STAR/Legs.html">A photo gallery using Zoomooz</a>
		        </p>
		
		        <p>
		        <a href="http://richard.milewski.org/archives/788">A zooming comic experiment</a>
		        </p>
		        
		        <p>
		        <a href="http://richard.milewski.org/archives/804">Simple HTML Slides uses Zoomooz</a>
		        </p>
		</div>
		
		
		<!--************************
		**  EXAMPLES              **
		*************************-->

		 <!--   <div class="section">
		            <a class="linkblock" href="examples/imagestack/index.html"><img src="website-images/thumbnails/imagestack-clipped.png" width="140" height="105" />Flickr image stack</a>
		    </div>
		    <div class="section">
		            <a class="linkblock" href="examples/isometric/index.html"><img src="website-images/thumbnails/isometric-clipped.png" width="140" height="105" />Isometric cube</a>
		    </div>
		    <div class="section">
		        	<a class="linkblock" href="examples/svgtree/index.html"><img src="website-images/thumbnails/svgtree-clipped.png" width="140" height="105" />SVG Tree demo</a>
			</div>
		    <div class="section">
		    		<a class="linkblock" href="examples/rootchange/index.html"><img src="website-images/thumbnails/rootchange-clipped.png" width="140" height="105" />Zooming container demo</a>
		    </div>
		    
		<!--************************
		**  ENABLING THE LIBRARY  **
		*************************-->
	
		<!--    <div class="section" id="gettingstarted">
		        <h2>Enabling the library</h2>
		        
		        <p>Put the following in the head-section of your page:</p>
		        
		        <div class="highlight">
		        	<pre><span class="nt">&lt;link</span> <span class="na">rel=</span><span class="s">&quot;stylesheet&quot;</span> <span class="na">href=</span><span class="s">&quot;style/zoomooz.css&quot;</span> <span class="na">type=</span><span class="s">&quot;text/css&quot;</span> <span class="nt">/&gt;</span>
<span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span> <span class="na">src=</span><span class="s">&quot;lib/sylvester.js&quot;</span><span class="nt">&gt;&lt;/script&gt;</span>
<span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span> <span class="na">src=</span><span class="s">&quot;lib/jquery-1.4.2.js&quot;</span><span class="nt">&gt;&lt;/script&gt;</span>
<span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span> <span class="na">src=</span><span class="s">&quot;js/purecssmatrix.js&quot;</span><span class="nt">&gt;&lt;/script&gt;</span>
<span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span> <span class="na">src=</span><span class="s">&quot;js/jquery.animtrans.js&quot;</span><span class="nt">&gt;&lt;/script&gt;</span>
<span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span> <span class="na">src=</span><span class="s">&quot;js/jquery.zoomooz.js&quot;</span><span class="nt">&gt;&lt;/script&gt;</span>
</pre>
				</div>
			</div>

		<!--************************
		**  ADDING ZOOM EFFECT    **
		*************************-->
	
	<!--		<div class="section" id="samplecode">
				<h2>Adding zoom effect</h2>

				<p>This adds a simple zoom on clicking to elements with class "zoomTarget". The zoom can be reset by clicking the body.</p>
<div class="highlight"><pre><span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span> <span class="na">language=</span><span class="s">&quot;javascript&quot;</span><span class="nt">&gt;</span>
  <span class="nx">$</span><span class="p">(</span><span class="nb">document</span><span class="p">).</span><span class="nx">ready</span><span class="p">(</span><span class="kd">function</span><span class="p">()</span> 
    <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;.zoomTarget&quot;</span><span class="p">).</span><span class="nx">click</span><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">evt</span><span class="p">)</span> <span class="p">{</span>
      <span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">).</span><span class="nx">zoomTo</span><span class="p">();</span>
      <span class="nx">evt</span><span class="p">.</span><span class="nx">stopPropagation</span><span class="p">();</span>
    <span class="p">});</span>
    <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;body&quot;</span><span class="p">).</span><span class="nx">click</span><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">evt</span><span class="p">)</span> <span class="p">{</span>
      <span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">).</span><span class="nx">zoomTo</span><span class="p">({</span><span class="nx">targetsize</span><span class="o">:</span><span class="mf">1.0</span><span class="p">});</span>
      <span class="nx">evt</span><span class="p">.</span><span class="nx">stopPropagation</span><span class="p">();</span>
    <span class="p">});</span>
  <span class="p">});</span>
<span class="nt">&lt;/script&gt;</span>
</pre></div>
			</div>

		<!--************************
		**  BROWSER SUPPORT       **
		*************************-->

		<!--	<div class="section">
		    	<h2>Browser support</h2>
		    	<p>All major browser platforms supported now:</p>
		    	<div class="browsers">
		    		<img src="website-images/safari.png" width="32" height="32" alt="Safari">
		    		<img src="website-images/chrome.png" width="32" height="32" alt="Chrome">
		    		<img src="website-images/ff.png" width="32" height="32" alt="Firefox"><br />
		    		<img src="website-images/opera.png" width="32" height="32" alt="Opera">
		    		<img src="website-images/ie.png" width="32" height="32" alt="IE">
		    	</div>
		    	<p>Supported versions</p>
		    	<ul>
		    		<li>Internet Explorer 9</li>
		    		<li>Safari 3 and newer</li>
		    		<li>Firefox 3.6 and newer</li>
		    		<li>Recent versions of Opera and Chrome</li>
		    	</ul>
		    	</ul>
		    </div>

		<!--************************
		**  DETAILED SETTINGS     **
		*************************-->

	<!--		<div class="section" id="detailedsettings">
				<h2>Detailed settings</h2>
				<div class="highlight"><pre><span class="nx">settings</span> <span class="o">=</span> <span class="p">{</span>
	<span class="c1">// zoomed size relative to screen</span>
	<span class="c1">// 0.0-1.0</span>
	<span class="nx">targetsize</span><span class="o">:</span> <span class="mf">0.9</span><span class="p">,</span>
	<span class="c1">// scale content to screen based on their size</span>
	<span class="c1">// &quot;width&quot;|&quot;height&quot;|&quot;both&quot;</span>
	<span class="nx">scalemode</span><span class="o">:</span> <span class="s2">&quot;both&quot;</span><span class="p">,</span>
	<span class="c1">// animation duration</span>
	<span class="nx">duration</span><span class="o">:</span> <span class="mi">1000</span><span class="p">,</span>
	<span class="c1">// easing of animation, similar to css transition params</span>
	<span class="c1">// &quot;linear&quot;|&quot;ease&quot;|&quot;ease-in&quot;|&quot;ease-out&quot;|
	// &quot;ease-in-out&quot;|[p1,p2,p3,p4]</span>
	<span class="c1">// [p1, p2, p3, p4] refer to cubic-bezier curve params</span>
	<span class="nx">easing</span><span class="o">:</span> <span class="s2">&quot;ease&quot;</span><span class="p">,</span>
	<span class="c1">// use browser native animation in webkit</span>
	<span class="c1">// true|false</span>
	<span class="nx">nativeanimation</span><span class="o">:</span> <span class="kc">true</span>
<span class="p">}</span>
<span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">).</span><span class="nx">zoomTo</span><span class="p">(</span><span class="nx">settings</span><span class="p">);</span>
</pre></div>
			</div>
		    
		<!--************************
		**  BUGS                  **
		*************************-->

	<!--	    <div class="section">
		    	<h2>Bugs and issues</h2>
		    	<div>
		    	<ul>
		    		<li>Does not work with full-page zooming in Webkit</li>
					<li>Requires a separate css file.</li>
					<li>Native animation problem in Webkit: animation not working in Webkit with very long pages when zooming with rotation.</li>
					<li>Css box-shadows make animations crawl.</li>
				    <li>Transforming elements with text content in Opera is buggy.</li>
				    <li>Rotation goes sometimes via the wrong direction.</li>
				</ul>
				</div>
			</div>
		
		<!--************************
		**  FEATURES              **
		*************************-->

	<!--		<div class="section">
		    	<h2>Some feature ideas</h2>
		    	<div>
		    	<ul>
		    		<li>Make it work in older IE versions.</li>
		    		<li>Have zooming out return to the same scroll position</li>
		    		<li>Support for 3d transformations.</li>
		    		<li>Hide elements that are not shown while transforming.</li>
		    		<li>Kinetic scrolling.</li>
		    	</ul>
				</div>
			</div>
        

        <!--************************
		**  GOOGLE ANALYTICS      **
		*************************-->
			
		<!--	<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-16288001-1");
pageTracker._trackPageview();
} catch(err) {}</script>

        </div>
 
         <!--************************
		**  FOOTER                **
		*************************-->

    <!-- <div id="footer">
    	Made by Janne Aukia, 2011. Uses jQuery Masonry. 
    </div> -->

	</body>
</html>