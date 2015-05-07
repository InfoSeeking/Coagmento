<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Coagmento Timeline</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../assets/css/demo.css" />
        <link rel="stylesheet" type="text/css" href="../assets/css/style_sidebarComponentstimeline.css" />
    </head>
    <body>
        <div class="container">

        <?php
        
            require_once("../connect.php"); 
        
            $userID = 2;
            $title = "Coagmento";
            
            $query = "SELECT * FROM users WHERE userID=$userID";
            $results = $connection->commit($query);
            $line = mysqli_fetch_array($results, MYSQL_ASSOC);
            $firstName = $line['firstName'];
            $lastName = $line['lastName'];
        
        ?>
    
            <h1>Timeline for <? echo "$firstName $lastName" ?></h1>
			<div id="ss-links" class="ss-links">
				<a href="#november">Nov</a>
				<a href="#october">Oct</a>
				<a href="#september">Sep</a>
				<a href="#august">Aug</a>
				<a href="#july">Jul</a>
				<a href="#june">Jun</a>
			</div>
            <div id="ss-container" class="ss-container">
            
            	<?
			
				$query = "SELECT * from pages, thumbnails where pages.thumbnailID = thumbnails.thumbnailID order by date desc limit 150";
				$results = $connection->commit($query);
				$compareDate = NULL;
				$setDate = FALSE;
				$hasTableHeader = FALSE;
				$count = 0;
				$odd = TRUE;
				
				$jan = 01; $feb = 02; $mar = 03; $apr = 04; $may = 05; $jun = 06; $jul = 07; $aug = 08;
				$sept = 09; $oct = 10; $nov = 11; $dec = 12;
				
				while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
					$title = $line['title'];
					$url = $line['url'];
					$date = $line['date'];
					$month = date("m",strtotime($date));
					$lemonth = '';
					$time = $line['time'];
					$thumb = $line['fileName'];
					
					if($month == 01) { $lemonth = "January"; }
					else if($month == 02) { $lemonth = "February"; }
					else if($month == 03) { $lemonth = "March"; }
					else if($month == 04) { $lemonth = "April"; }
					else if($month == 05) { $lemonth = "May"; }
					else if($month == 06) { $lemonth = "June"; }
					else if($month == 07) { $lemonth = "July"; }
					else if($month == 08) { $lemonth = "August"; }
					else if($month == 09) { $lemonth = "September"; }
					else if($month == 10) { $lemonth = "October"; }
					else if($month == 11) { $lemonth = "November"; }
					else if($month == 12) { $lemonth = "December"; }
					
					
					echo $lemonth;
					
					if ($setDate == FALSE) {
						$compareDate = $date;
						$setDate = TRUE;
					}
					
					if($date == $compareDate) {
						if($hasTableHeader == FALSE) {
							/* month header */
							
							echo '<div class="ss-row ss-large">';
							
							if($odd == TRUE) {
								echo '<div class="ss-left">';
								$odd = FALSE;
							}
							else {
								echo '<div class="ss-right">';
								$odd = TRUE;
							}
							
							echo '<span class="date">'; echo $date; echo'</span>
							<a href="" class="ss-circle">';
					
							echo '<table><tr>';
							
							$hasTableHeader = TRUE; 
							$count = 0;
						}
						
						if(($count % 12) == 0) {
							echo "</tr><tr>";
							$count++;
						}
						else {
							$count++;
						}
						
						echo "<td>";
						echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//';
						echo $thumb;
						echo '" width="55" height="55" />';
						/* echo "<a href=\"$url\">$title</a> <br /> $date $time<br/>";*/
						echo "</td>"; 
					}
					else {
						$compareDate = $date;
						$hasTableHeader = FALSE;
						echo "</tr></table>";
						echo '</a></div>';
					}	
				}  
			
				echo '</div>';
			?>
				
            </div>
        </div>
        
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
		<script type="text/javascript">
		$(function() {

			var $sidescroll	= (function() {
					
					// the row elements
				var $rows			= $('#ss-container > div.ss-row'),
					// we will cache the inviewport rows and the outside viewport rows
					$rowsViewport, $rowsOutViewport,
					// navigation menu links
					$links			= $('#ss-links > a'),
					// the window element
					$win			= $(window),
					// we will store the window sizes here
					winSize			= {},
					// used in the scroll setTimeout function
					anim			= false,
					// page scroll speed
					scollPageSpeed	= 2000 ,
					// page scroll easing
					scollPageEasing = 'easeInOutExpo',
					// perspective?
					hasPerspective	= false,
					
					perspective		= hasPerspective && Modernizr.csstransforms3d,
					// initialize function
					init			= function() {
						
						// get window sizes
						getWinSize();
						// initialize events
						initEvents();
						// define the inviewport selector
						defineViewport();
						// gets the elements that match the previous selector
						setViewportRows();
						// if perspective add css
						if( perspective ) {
							$rows.css({
								'-webkit-perspective'			: 600,
								'-webkit-perspective-origin'	: '50% 0%'
							});
						}
						// show the pointers for the inviewport rows
						$rowsViewport.find('a.ss-circle').addClass('ss-circle-deco');
						// set positions for each row
						placeRows();
						
					},
					// defines a selector that gathers the row elems that are initially visible.
					// the element is visible if its top is less than the window's height.
					// these elements will not be affected when scrolling the page.
					defineViewport	= function() {
					
						$.extend( $.expr[':'], {
						
							inviewport	: function ( el ) {
								if ( $(el).offset().top < winSize.height ) {
									return true;
								}
								return false;
							}
						
						});
					
					},
					// checks which rows are initially visible 
					setViewportRows	= function() {
						
						$rowsViewport 		= $rows.filter(':inviewport');
						$rowsOutViewport	= $rows.not( $rowsViewport )
						
					},
					// get window sizes
					getWinSize		= function() {
					
						winSize.width	= $win.width();
						winSize.height	= $win.height();
					
					},
					// initialize some events
					initEvents		= function() {
						
						// navigation menu links.
						// scroll to the respective section.
						$links.on( 'click.Scrolling', function( event ) {
							
							// scroll to the element that has id = menu's href
							$('html, body').stop().animate({
								scrollTop: $( $(this).attr('href') ).offset().top
							}, scollPageSpeed, scollPageEasing );
							
							return false;
						
						});
						
						$(window).on({
							// on window resize we need to redefine which rows are initially visible (this ones we will not animate).
							'resize.Scrolling' : function( event ) {
								
								// get the window sizes again
								getWinSize();
								// redefine which rows are initially visible (:inviewport)
								setViewportRows();
								// remove pointers for every row
								$rows.find('a.ss-circle').removeClass('ss-circle-deco');
								// show inviewport rows and respective pointers
								$rowsViewport.each( function() {
								
									$(this).find('div.ss-left')
										   .css({ left   : '0%' })
										   .end()
										   .find('div.ss-right')
										   .css({ right  : '0%' })
										   .end()
										   .find('a.ss-circle')
										   .addClass('ss-circle-deco');
								
								});
							
							},
							// when scrolling the page change the position of each row	
							'scroll.Scrolling' : function( event ) {
								
								// set a timeout to avoid that the 
								// placeRows function gets called on every scroll trigger
								if( anim ) return false;
								anim = true;
								setTimeout( function() {
									
									placeRows();
									anim = false;
									
								}, 10 );
							
							}
						});
					
					},
					// sets the position of the rows (left and right row elements).
					// Both of these elements will start with -50% for the left/right (not visible)
					// and this value should be 0% (final position) when the element is on the
					// center of the window.
					placeRows		= function() {
						
							// how much we scrolled so far
						var winscroll	= $win.scrollTop(),
							// the y value for the center of the screen
							winCenter	= winSize.height / 2 + winscroll;
						
						// for every row that is not inviewport
						$rowsOutViewport.each( function(i) {
							
							var $row	= $(this),
								// the left side element
								$rowL	= $row.find('div.ss-left'),
								// the right side element
								$rowR	= $row.find('div.ss-right'),
								// top value
								rowT	= $row.offset().top;
							
							// hide the row if it is under the viewport
							if( rowT > winSize.height + winscroll ) {
								
								if( perspective ) {
								
									$rowL.css({
										'-webkit-transform'	: 'translate3d(-75%, 0, 0) rotateY(-90deg) translate3d(-75%, 0, 0)',
										'opacity'			: 0
									});
									$rowR.css({
										'-webkit-transform'	: 'translate3d(75%, 0, 0) rotateY(90deg) translate3d(75%, 0, 0)',
										'opacity'			: 0
									});
								
								}
								else {
								
									$rowL.css({ left 		: '-50%' });
									$rowR.css({ right 		: '-50%' });
								
								}
								
							}
							// if not, the row should become visible (0% of left/right) as it gets closer to the center of the screen.
							else {
									
									// row's height
								var rowH	= $row.height(),
									// the value on each scrolling step will be proporcional to the distance from the center of the screen to its height
									factor 	= ( ( ( rowT + rowH / 2 ) - winCenter ) / ( winSize.height / 2 + rowH / 2 ) ),
									// value for the left / right of each side of the row.
									// 0% is the limit
									val		= Math.max( factor * 50, 0 );
									
								if( val <= 0 ) {
								
									// when 0% is reached show the pointer for that row
									if( !$row.data('pointer') ) {
									
										$row.data( 'pointer', true );
										$row.find('.ss-circle').addClass('ss-circle-deco');
									
									}
								
								}
								else {
									
									// the pointer should not be shown
									if( $row.data('pointer') ) {
										
										$row.data( 'pointer', false );
										$row.find('.ss-circle').removeClass('ss-circle-deco');
									
									}
									
								}
								
								// set calculated values
								if( perspective ) {
									
									var	t		= Math.max( factor * 75, 0 ),
										r		= Math.max( factor * 90, 0 ),
										o		= Math.min( Math.abs( factor - 1 ), 1 );
									
									$rowL.css({
										'-webkit-transform'	: 'translate3d(-' + t + '%, 0, 0) rotateY(-' + r + 'deg) translate3d(-' + t + '%, 0, 0)',
										'opacity'			: o
									});
									$rowR.css({
										'-webkit-transform'	: 'translate3d(' + t + '%, 0, 0) rotateY(' + r + 'deg) translate3d(' + t + '%, 0, 0)',
										'opacity'			: o
									});
								
								}
								else {
									
									$rowL.css({ left 	: - val + '%' });
									$rowR.css({ right 	: - val + '%' });
									
								}
								
							}	
						
						});
					
					};
				
				return { init : init };
			
			})();
			
			$sidescroll.init();
			
		});
		</script>
    </body>
</html>