<!doctype html>
<html lang="en">
<head>

<title>Coagmento CSpace Coverflow View</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<style>

body {
  margin: 0;
  padding: 0;
  background: #000;
  font-family: "Georgia", serif;
}


/*
  The gallery container.
   - Fills the browser window.
   - Renders its contents in 3D.
   - Has a gradient mask to fade out slides at the left and right edges.
*/

#gallery {
  position: absolute;
  width: 100%;
  height: 100%;
  -webkit-perspective: 600px;
  overflow: hidden;
  -webkit-mask-image: -webkit-gradient(linear, left top, right top, color-stop(0, rgba(0,0,0,.2)), color-stop(.1, rgba(0,0,0,1)), color-stop(.9, rgba(0,0,0,1)), color-stop(1, rgba(0,0,0,.2)) );
}


/*
  Images within the gallery.
   - Add a reflection below the image.
   - Fade the reflection out gradually towards the bottom.
   - Make the image resize with its containing div.
   - Hide the image initially.
*/

#gallery img {
  -webkit-box-reflect:
    below
    0
    -webkit-gradient(
      linear,
      left top,
      left bottom,
      color-stop(0, rgba(255, 255, 255, 0)),
      color-stop(.5, rgba(255, 255, 255, .3)),
      color-stop(1, rgba(255, 255, 255, .3))
    );

    max-width: inherit;
    max-height: inherit;
    display: none;
}


/*
  Divs wrapped around the gallery images and reflections.
   - Add some bottom padding to allow for the reflection.
   - Position the div below the gallery bottom so the reflection is partially hidden.
   - Give the div a black background to prevent other reflections leaking through.
*/

#gallery div {
  position: absolute;
  padding-bottom: 400px;
  bottom: -300px;
  background: #000;
}


/*
  The slider / scrollbar.
   - Position it below the gallery.
   - Turn off the default appearance.
   - Give it a rounded border and light grey background.
   - Make it semitransparent.
   - Hide it initially.
*/

#slider {
  position: absolute;
  bottom: 20px;
  left: 5%;
  right: 5%;
  -webkit-appearance: none !important;
  border-radius: 10px;
  border: 1px solid white;
  background: #999;
  opacity: .5;
  display: none;
}


/*
  The slider's thumb control.
   - Turn off the default appearance.
   - Give it a rounded border and gradient background.
*/

#slider::-webkit-slider-thumb {
  -webkit-appearance: none !important;
  width: 50px;
  height: 18px;
  border-radius: 10px;
  border: 2px solid #fff;
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #999), color-stop(.5, #000) );
}


/*
  The current slide caption.
   - Position it below the slide.
   - Centre it horizontally.
   - Give it a font that's remarkably similar to the real thing. :)
*/

#caption {
  position: absolute;
  z-index: 2;
  bottom: 75px;
  width: 100%;
  color: #fff;
  text-align: center;
  font-family: "Lucida Grande", sans-serif;
  font-size: 12px;
  font-weight: bold;
}


/*
  Loading text.
   - Position in the centre of the gallery container
   - Hide by default
*/

#loading {
  position: absolute;
  z-index: 1;
  bottom: 50%;
  width: 100%;
  color: #ffc;
  text-align: center;
  font-family: "Georgia", serif;
  font-size: 36px;
  letter-spacing: .1em;
  opacity: 0;
  filter: alpha(opacity=0);
}


/*
  Tutorial info box.
   - Position it in the bottom right corner of the window
   - Give the 'i' h1 a circular border
   - Hide the whole div by default
   - Fade it in on hover
*/

#info {
  color: #ffc;
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 99;
  border: none;
  border-radius: 20px;
  padding: 10px 20px;
  background: transparent;
  -webkit-transition: background-color .5s;
  -moz-transition: background-color .5s;
  -o-transition: background-color .5s;
  transition: background-color .5s;
  font-size: 70%;
}

#info>* {
  margin: 10px 50px 10px 0;
  cursor: default;
  opacity: 0;
  filter: alpha(opacity=0);
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  -webkit-transition: opacity .5s;
  -moz-transition: opacity .5s;
  -o-transition: opacity .5s;
  transition: opacity .5s;
  zoom: 1;
}

#info p {
  opacity: 0;
  filter: alpha(opacity=0);
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
}

#info h1 {
  position: fixed;
  z-index: 99;
  margin: 0;
  padding: 17px 1px 4px 14px;
  right: 30px;
  top: 30px;
  width: 20px;
  height: 14px;
  font-size: 20px;
  border: 3px solid #ffc;
  line-height: 1px;
  border-radius: 20px;
  color: #ffc;
  opacity: .5;
  filter: alpha(opacity=50);
}

#info:hover, #info.hover {
  background: rgba(50,50,50,.6);
}

#info:hover *, #info.hover * {
  opacity: 1;
  filter: alpha(opacity=100);
  color: #ffc;
}

/* (Give the link the same colour as the other text in the box) */

#info a {
  color: #ffc;
}

</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.jswipe-0.1.2.js"></script>
<script type="text/javascript">

//  --- Begin Config ---
var loadingMessageDelay = 2000;       // How long to wait before showing loading message (in ms)
var loadingMessageSpeed = 1200;       // Duration of each pulse in/out of the loading message (in ms)
var loadingMessageMinOpacity = 0.4;   // Minimum opacity of the loading message
var loadingMessageMaxOpacity = 1;     // Maximum opacity of the loading message
var bgRotateAngle = 65;               // Rotation angle of background slides (in degrees)
var stepPercent = 15;                 // Horiz gap between background slides (as % of window height)
var currentSlidePaddingPercent = 70;  // Horiz gap between current slide and background slides (as % of window height)
var swipeXThreshold = 30;             // X-axis minimum threshold for swipe action (in px)
var swipeYThreshold = 90;             // Y-axis maximum threshold for swipe action (in px)
var leftKeyCode = 37;                 // Character code for "move left" key (default: left arrow)
var rightKeyCode = 39;                // Character code for "move right" key (default: right arrow)
//  --- End Config ---

var currentSlide = 1;                 // The slide that the user is currently viewing
var oldCurrentSlide = currentSlide;   // The previous slide that the user viewed
var totalSlides = 0;                  // Total number of slides in the gallery (computed later)
var flipTimeouts = new Array;         // For storing the timeout IDs so we can clear them


// Display the loading message after a short delay
$( function() {

  var userAgent = navigator.userAgent.toString().toLowerCase();
  if ((userAgent.indexOf('safari') == -1) || (userAgent.indexOf('chrome') != -1)) alert("This demo currently only runs on Safari (Mac, Windows, and iOS). By the time you read this, it may work on Chrome too. Just so you know...!");

  $('#loading').delay( loadingMessageDelay );
  fadeInLoadingMessage();
} );

// Fire the init() function once the page and all images have loaded
$(window).load( init );


// Set up the gallery

function init() {

  totalSlides = $('#gallery img').length;  // Total number of slides in the gallery
  var pos = 0;  // To track the index of the slide we're working with

  // Hide the loading message and reveal the slider
  $('#loading').clearQueue().stop().fadeTo( 'fast', 0 ).remove();
  if ( !navigator.platform.match(/(iPhone|iPod|iPad)/) ) $('#slider').fadeIn('slow');

  // For each image in the gallery:
  //  - Show the image
  //  - Wrap the image in a div
  //  - Store the image's index and initial oldLeftPos values in the div

  $('#gallery img').each( function() {
    $(this).css( 'display', 'inline' );
    var div = $('<div />').data('slideNum',++pos);
    $(this).wrap( div );
    $(this).parent().data('oldLeftPos',0);
  } );

  // Add a click handler to each div to jump to the div's image when clicked
  $('#gallery div').click( function() {
    oldCurrentSlide = currentSlide;
    currentSlide = $(this).data('slideNum');
    displayGallery();
  } );

  // Redraw the gallery whenever the user resizes the browser
  $(window).resize( displayGallery );

  // Bind the moveRight() and moveLeft() functions to
  // the swipeLeft and swipeRight events respectively.
  $('body').swipe( {
       swipeLeft: moveRight,
       swipeRight: moveLeft,
       threshold: { x:swipeXThreshold, y:swipeYThreshold }
  } );

  // Bind the moveleft() and moveRight() functions to the
  // "move left" and "move right" keys on the keyboard

  $(document).keydown( function(event) {
    if ( event.which == leftKeyCode ) moveLeft();
    if ( event.which == rightKeyCode ) moveRight();
  } );

  // Set up the slider

  $('#slider').attr( {
    'min': 1,
    'max': totalSlides,
    'value': currentSlide
  } );

  $('#slider').change( function() {
    oldCurrentSlide = currentSlide;
    currentSlide = $(this).val();
    displayGallery();
  } );

  // All set! Show the gallery
  displayGallery();

}

// Display the slides in the gallery

function displayGallery() {

  var pos = 0;                              // To track the index of the slide we're working with
  var galleryWidth = $('#gallery').width(); // Width of the gallery/viewport in px
  var galleryCentre = galleryWidth / 2;     // Horizontal centre point of the gallery in px
  var windowHeight = $(window).height();    // Viewport height in px
  var slideHeight = windowHeight - 150;     // Maximum slide height based on window height

  // Compute the actual step and slide padding values, based on window height
  var step = windowHeight * stepPercent / 100;
  var currentSlidePadding = windowHeight * currentSlidePaddingPercent / 100;

  // Clear any previous timeouts to avoid clashes when moving the gallery quickly
  while ( t = flipTimeouts.pop() ) clearTimeout(t);

  // Move through each slide div, positioning it in 3D space

  $('#gallery div').each( function() {

    var div = $(this);

    // Resize each slide if necessary (e.g. if the window height has changed)
    div.css('max-width', slideHeight);
    div.css('max-height', slideHeight);

    if ( ++pos < currentSlide ) {

      // The slide is to the left of the current slide.

      // Compute its horizontal position
      var leftPos = galleryCentre - ( currentSlide * step ) + ( pos * step ) - (div.width()/2) - currentSlidePadding;

      // If the slide was previously to the right of the current slide, flip it immediately to the new angle.
      // (If we were to let it rotate slowly as it's repositioned then it would sometimes cut into other slides.)

      if ( pos > oldCurrentSlide ) {
        div.css( {
          '-webkit-transition': 'none',
          '-webkit-transform': 'translate3d(' +  div.data('oldLeftPos') + 'px,0,-' + (100+parseInt(div.width()/1.5)) + 'px) rotateY(' + bgRotateAngle + 'deg)'
        } );
      }

      // Wait 10 ms to give the slide a chance to rotate if necessary,
      // then reposition the slide to its new horiziontal position and angle

      var t = setTimeout( function() {
        div.css( {
          '-webkit-transition': '-webkit-transform .8s cubic-bezier(0, 0, .001, 1)',
          '-webkit-transform': 'translate3d(' + leftPos + 'px,0,-' + (100+parseInt(div.width()/1.5)) + 'px) rotateY(' + bgRotateAngle + 'deg)'
        } );
      }, 10 );

      // Store the timeout ID so we can clear it later
      flipTimeouts.push(t);

      // Store the new position in oldLeftPos
      div.data('oldLeftPos', leftPos);

    } else if ( pos > currentSlide ) {

      // The slide is to the right of the current slide.

      // Compute its horizontal position
      var leftPos = galleryCentre + ( (pos-currentSlide) * step ) - (div.width()/2) + currentSlidePadding;

      // If the slide was previously to the left of the current slide, flip it immediately to the new angle.
      // (If we were to let it rotate slowly as it's repositioned then it would sometimes cut into other slides.)

      if ( pos < oldCurrentSlide ) {
        div.css( {
          '-webkit-transition': 'none',
          '-webkit-transform': 'translate3d(' + div.data('oldLeftPos') + 'px,0,-' + (100+parseInt(div.width()/1.5)) + 'px) rotateY(-' + bgRotateAngle + 'deg)'
        } );
      }

      // Wait 10 ms to give the slide a chance to rotate if necessary,
      // then reposition the slide to its new horiziontal position and angle

      var t = setTimeout( function() {
        div.css( {
          '-webkit-transition': '-webkit-transform .8s cubic-bezier(0, 0, .001, 1)',
          '-webkit-transform': 'translate3d(' + leftPos + 'px,0,-' + (100+parseInt(div.width()/1.5)) + 'px) rotateY(-' + bgRotateAngle + 'deg)'
        } );
      }, 10 );

      // Store the timeout ID so we can clear it later
      flipTimeouts.push(t);

      // Store the new position in oldLeftPos
      div.data('oldLeftPos', leftPos);

    } else {

      // The slide is the current slide.
      // Position it in the horizontal centre of the gallery, facing forward.

      var leftPos = galleryCentre - ( div.width()/2 );

      div.css( {
        '-webkit-transform': 'translate3d(' + leftPos + 'px,0,0) rotateY(0deg)',
      } );

      // Store the new position in oldLeftPos
      div.data('oldLeftPos', leftPos);
    }

  } );

  // Update the slider value and caption
  $('#slider').val( currentSlide );
  var currentSlideImage = $('#gallery img').eq( currentSlide - 1 );
  $('#caption').text( currentSlideImage.attr('alt') );
}


// Move one slide to the left by sliding the gallery left-to-right

function moveLeft() {
  if ( currentSlide > 1 ) {
    oldCurrentSlide = currentSlide;
    currentSlide--;
    displayGallery();
  }
}


// Move one slide to the right by sliding the gallery right-to-left

function moveRight() {
  if ( currentSlide < totalSlides ) {
    oldCurrentSlide = currentSlide;
    currentSlide++;
    displayGallery();
  }
}


// Functions to pulse the loading message

function fadeInLoadingMessage() {
  $('#loading').animate( { opacity: loadingMessageMaxOpacity }, loadingMessageSpeed, 'swing', fadeOutLoadingMessage );
}

function fadeOutLoadingMessage(){
  $('#loading').animate( { opacity: loadingMessageMinOpacity }, loadingMessageSpeed, 'swing', fadeInLoadingMessage );
}


</script>

</head>

<body>

<?php
// Connecting to database
$con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("shahonli_coagmento", $con);
$userID=2;

$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID." AND pages.projectID='8'";
$pageResult = $connection->commit($getPage);
?>

  <div id="gallery">
  	<? while($line = mysqli_fetch_array($pageResult)) {
		$thumb = $line['fileName'];
		$title = $line['title'];

		echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' alt='".$title."'>";
	}
	?>

    <!-- <img src="slides/BarbedWire.jpg" alt="Barbed Wire" />
    <img src="slides/Chillies.jpg" alt="Chillies" />
    <img src="slides/BigBen.jpg" alt="Big Ben" />
    <img src="slides/DriftStuff.jpg" alt="Drift Stuff" />
    <img src="slides/CricketMatch.jpg" alt="Cricket Match" />
    <img src="slides/Driftwood.jpg" alt="Driftwood" />
    <img src="slides/LionStatue.jpg" alt="Lion Statue" />
    <img src="slides/RainbowRibbons.jpg" alt="Rainbow Ribbons" />
    <img src="slides/Feather.jpg" alt="Feather" />
    <img src="slides/LondonEyeFromBelow.jpg" alt="London Eye From Below" />
    <img src="slides/GrassLight.jpg" alt="Grass and Light" />
    <img src="slides/ParliamentSquare.jpg" alt="Parliament Square" />
    <img src="slides/DriftwoodGuy.jpg" alt="Driftwood and Guy" />
    <img src="slides/PostBox.jpg" alt="Post Box" />
    <img src="slides/SeaweedGasmask.jpg" alt="Seaweed Gasmask" />
    <img src="slides/StickSea.jpg" alt="Stick and Sea" />
    <img src="slides/Surfers.jpg" alt="Surfers" /> -->

  </div>
  <input type="range" name="slider" id="slider" />
  <div id="caption"></div>
  <div id="loading">Please wait...</div>

  <!--  <div id="info">
    <h1>i</h1>
    <h2>Coagmento CSpace Coverflow View</h2>

    <p>&copy; Elated.com | <a href="http://www.elated.com/articles/cover-flow-remade-with-css-and-jquery/">Back to Tutorial</a> | Cover Flow is a trademark owned by Apple Inc.</p>

  </div> -->

<?

?>

</body>
</html>
