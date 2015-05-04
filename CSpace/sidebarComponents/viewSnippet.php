<?php
        session_start();
	if ((isset($_SESSION['CSpace_userID']))) {
		//require_once("../connect.php");
                require_once("insertAction.php");
                $userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];

                $snippetID = $_GET['value'];
                $query = "SELECT *, (SELECT userName FROM users where users.userID = snippets.userID) AS userName, (SELECT sum(value) from rating where active = 1 and idResource = snippetID and type = 'snippets' group by idResource)/(SELECT count(*) from rating where active = 1 and idResource = snippetID and type = 'snippets' group by idResource) as finalRating FROM snippets WHERE snippetID='$snippetID' AND status=1";
                $results = mysql_query($query) or die(" ". mysql_error());
                $line = mysql_fetch_array($results, MYSQL_ASSOC);
                $userName = $line['userName'];
                $finalRating = $line['finalRating'];
                $note = $line['note'];
                //$date = $line['date'];
                $time = $line['time'];
                $title = $line['title'];
                $snippet = stripslashes($line['snippet']);
                $url = $line['url'];
                if ($finalRating == null)
                    $finalRating = 0;
                $finalRating = number_format($finalRating, 2);;
                $date = strtotime($line['date']);
                $date = strftime("%B %e, %Y", $date);

                if ($title=="")
                    $title = "no title";

                $fullSnippet = $snippet. " || [Note: " . $note . "]". " || [Average Rating: " . $finalRating . "]". " || [Source: " . $title . "]". " || [URL: " . $url . "]". " || [Collected on: " . $date." at ".$time . "]". " || [Citation Format in APA Style (Complete Authors' Names, Year of Publication, etc.) : LastName1, FirstName_InitialLetter. (Year). " . $title . ". Retrieved ".$date.", from ".$url."]";
?>
<html>
    <head>

	<title>Snippet View</title>
	<style type="text/css">
                    .cursorType{
                            cursor:pointer;
                            cursor:hand;
                    }

        </style>
        <script type="text/javascript" src="ZeroClipboard.js"></script>
        <script type="text/javascript" src="../assets/js/utilities.js"></script>
        </head>
    <body>

                <div id="fullSnippet" style="display:none"><?php echo $fullSnippet; ?></div>
<!--                <div id="d_clip_button" style="border:1px solid black; padding:15px;">Copy Snippet</div>-->
                 <center><BUTTON name="Copy" id="d_clip_button"><STRONG>Copy Snippet</STRONG></BUTTON></center>

                <script language="JavaScript">
                        var clip = new ZeroClipboard.Client();
                        clip.setText( document.getElementById('fullSnippet').innerHTML );
                        clip.glue( 'd_clip_button' );
                        clip.addEventListener( 'onMouseDown', recordCopyAction );

                        function recordCopyAction(client) {
                                <?php echo "javascript:ajaxpage('insertAction.php?action=copy&value='+$snippetID,null);" ?>
                        }
                </script>


                <center>
                    <table width="445" height="193" border="0">
                      <tr>
                        <td height="37" colspan="2" align="center">

                            </p>
                            <p style="font-size:11px; font-weight: bold; color:grey">[By copying this snippet using the above button, it will also include your notes, its rating, its source, collection date, and citation format. To paste in your document press CTRL+V]</p>
                            <hr /></td>
                      </tr>
                      <tr>
                        <td width="120" height="33" valign="top"><strong>Note:</strong></td>
                        <td width="334" valign="top"><?php echo $note; ?></td>
                      </tr>
                        <tr>
                            <td height="27" valign="top"><strong>Average Rating:</strong></td>
                            <td valign="top"><?php echo $finalRating; ?></td>
                        </tr>
                      <tr>
                        <td height="27" valign="top"><strong>Source:</strong></td>
                        <td valign="top"><a onclick="javascript:ajaxpage('insertAction.php?action=sidebar-snippet&value=<?php echo $snippetID?>',null)" href="<?php echo $url; ?>" target="_new"><?php echo $title; ?></a></td>
                      </tr>
                      <tr>
                        <td height="27" valign="top"><strong>Collected on:</strong></td>
                        <td valign="top"><?php echo $date." at ".$time; ?></td>
                      </tr>
                      <tr>
                        <td height="36" valign="top"><strong>Snippet:</strong></td>
                        <td valign="top"><?php echo $snippet; ?></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top">
                          <hr />
                          <p><strong><em>How good is this snippet? Rate it: </em></strong>
                          <p style="font-size:11px; font-weight: bold; color:grey">[Your rating will be reflected on the sidebar in a few seconds]</p>
                          <table width="200" border="0">
                          <tr>
                            <td align="center" valign="top"><input type="radio" id="rating" onclick="javascript:saveRatingSimple('<?php echo $snippetID; ?>','snippets',1)" name="rating"  value="1" /></td>
                            <td align="center" valign="top"><input type="radio" id="rating" onclick="javascript:saveRatingSimple('<?php echo $snippetID; ?>','snippets',2)" name="rating"  value="2" /></td>
                            <td align="center" valign="top"><input type="radio" id="rating" onclick="javascript:saveRatingSimple('<?php echo $snippetID; ?>','snippets',3)" name="rating"  value="3" /></td>
                            <td align="center" valign="top"><input type="radio" id="rating" onclick="javascript:saveRatingSimple('<?php echo $snippetID; ?>','snippets',4)" name="rating"  value="4" /></td>
                            <td align="center" valign="top"><input type="radio" id="rating" onclick="javascript:saveRatingSimple('<?php echo $snippetID; ?>','snippets',5)" name="rating"  value="5" /></td>
                          </tr>
                          <tr>
                            <td align="center" valign="top">1</td>
                            <td align="center" valign="top">2</td>
                            <td align="center" valign="top">3</td>
                            <td align="center" valign="top">4</td>
                            <td align="center" valign="top">5</td>
                          </tr>
                        </table>
                        </td>
                      </tr>
                    </table>
                    <center><BUTTON name="Close" id="close" onclick="javascript:window.close()"><STRONG>Close</STRONG></BUTTON></center>
            </center>

</body>
</html>

<?php

        }
        else {
		echo "Your session has expired. Please <a href=\"http://www.coagmento.org/loginOnSideBar.php\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
?>
