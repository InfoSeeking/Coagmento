<!--


Before $displayMode to '3D','coverflow','timeline'


 -->


<div id="topbar">

    <div id="toptext">
        <h2><a href="index.php?projects=all&objects=all&years=all&months=all&displayMode=<?php echo $displayMode?>&formSubmit=Submit">Coagmento CSpace</a></h2><br>
        <p id="getToolbar">Get Toolbar: <a href="../getToolbar.php">Firefox</a> <a href="https://chrome.google.com/webstore/search/coagmento" target="_blank">Chrome</a></p>
    </div>

	<div id="left">
        <div class="form">
        <form name="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="get">

        <div id="display_box">
        Interface:
        <select name="displayMode" onChange="jumpto(document.form1.displayMode.options[document.form1.displayMode.options.selectedIndex].value)">


				<?php


					echo "<option value=\"http://".$_SERVER['HTTP_HOST']."/CSpace/index.php?projects=all&objects=all&years=all&months=all&displayMode=timeline&formSubmit=Submit\"";

					if ($displayMode=='timeline')
						echo "selected=\"selected\"";

					echo ">Timeline</option>";

					echo "<option value=\"http://".$_SERVER['HTTP_HOST']."/CSpace/index.php?projects=all&objects=all&years=all&months=all&displayMode=coverflow&formSubmit=Submit\"";


					if ($displayMode=='coverflow')
						echo "selected=\"selected\"";
					echo ">Coverflow</option>";

					echo "<option value=\"http://".$_SERVER['HTTP_HOST']."/CSpace/impress/index.php?projects=all&objects=all&years=all&months=all&displayMode=3D&formSubmit=Submit\"";
					if ($displayMode=='3D')
						echo "selected=\"selected\"";
					echo ">3D</option>";

				?>

        </select>
        </div>

        <select name="projects">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_GET['formSubmit']))
            {
              if($_GET['projects'] == 'all') { echo '<option value="all" selected="selected">All Projects</option>'; echo '<option value="" disabled="disabled"> ---------- </option>'; }
            else {?>
                <option value="<?php echo $_GET['projects']; ?>" selected="selected"><?php echo $_GET['projects']; ?></option>
                <option value="" disabled="disabled"> ---------- </option>
            <? }
            } ?>

            <?php
                echo '<option value="all">All Projects</option>';
                $query = "SELECT * FROM memberships WHERE userID='$userID'";
                $results = $connection->commit($query);
                while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
                    $projID = $line['projectID'];
                    $query1 = "SELECT * FROM projects WHERE projectID='$projID'";
                    $results1 = $connection->commit($query1);
                    $line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
                    $title = $line1['title'];
                    echo "<option value=\"$title\" ";
                    if ($projID==$projectID)
                        echo "SELECTED";
                        echo ">$title</option>\n";
                }
            ?>
        </select>

        <select id="objects" name="objects">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_GET['formSubmit']))
            {
              switch ($_GET['objects']) {
                    case 'pages':
                        echo '<option value="pages" selected="selected">Webpages</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'saved':
                        echo '<option value="saved" selected="selected">Bookmarks</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'queries':
                        echo '<option value="queries" selected="selected">Searches</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'snippets':
                        echo '<option value="snippets" selected="selected">Snippets</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'annotations':
                        echo '<option value="annotations" selected="selected">Annotations</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'all':
                        echo '<option value="all" selected="selected">All Objects</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                }
            } ?>

            <option value="all">All Objects</option>
            <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?>>Webpages</option>
            <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?>>Bookmarks</option>
            <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?>>Searches</option>
            <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?>>Snippets</option>
            <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?>>Annotations</option>
        </select>

        <select id="years" name="years">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_GET['formSubmit']))
            {
              if($_GET['years'] == 'all') { echo '<option value="all" selected="selected">All Years</option>'; echo '<option value="" disabled="disabled"> ---------- </option>'; }
            else {?>
                <option value="<?php echo $_GET['years']; ?>" selected="selected"><?php echo $_GET['years']; ?></option>
                <option value="" disabled="disabled"> ---------- </option>
            <? }
            } ?>

            <option value="all">All Years</option>
            <?
            $sql_year="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
            $result_year = $connection->commit($sql_year);

            $options="";
            $y=array();

            while ($row=mysql_fetch_array($result_year)) {
                $date=$row["date"];
                $year = date("Y",strtotime($date));

                if (!in_array($year, $y)){
                    $y[] = $year;
                    $options.="<OPTION VALUE=".$year.">".$year; echo'</OPTION>';
                }

            }
            echo $options;
            ?>
        </select>

        <select id="months" name="months">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_GET['formSubmit']))
            {
               switch ($_GET['months']) {
                    case '01':
                        echo '<option value="01" selected="selected">Jan</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '02':
                        echo '<option value="02" selected="selected">Feb</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '03':
                        echo '<option value="03" selected="selected">Mar</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '04':
                        echo '<option value="04" selected="selected">Apr</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '05':
                        echo '<option value="05" selected="selected">May</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '06':
                        echo '<option value="06" selected="selected">Jun</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '07':
                        echo '<option value="07" selected="selected">Jul</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '08':
                        echo '<option value="08" selected="selected">Aug</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '09':
                        echo '<option value="09" selected="selected">Sept</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '10':
                        echo '<option value="10" selected="selected">Oct</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '11':
                        echo '<option value="11" selected="selected">Nov</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '12':
                        echo '<option value="12" selected="selected">Dec</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'all':
                        echo '<option value="all" selected="selected">All Months</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                }
              } ?>

            <option value="all">All Months</option>
            <?php
            $sql_month="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet')";
            $result_month = $connection->commit($sql_month);

            $m=array();

            while ($row2=mysql_fetch_array($result_month)) {
                $date2=$row2["date"];
                $month = date("m",strtotime($date2));

                if (!in_array($month, $m)){
                    if($month == 01 || $month == 02 || $month == 03 || $month == 04 || $month == 05 || $month == 06 || $month == 07 || $month == 08 || $month == 09 || $month == 10 || $month == 11 || $month == 12) {
                      $m[] = $month;
                    }
                }
            }

            sort($m);

            for($i = 0; $i < count($m); ++$i) {
                echo "<option value=".$m[$i].">";
                if($m[$i]==01) { echo "Jan"; }
                elseif($m[$i]==02) { echo "Feb"; }
                elseif($m[$i]==03) { echo "Mar"; }
                elseif($m[$i]==04) { echo "Apr"; }
                elseif($m[$i]==05) { echo "May"; }
                elseif($m[$i]==06) { echo "Jun"; }
                elseif($m[$i]==07) { echo "Jul"; }
                elseif($m[$i]==08) { echo "Aug"; }
                elseif($m[$i]==09) { echo "Sept"; }
                elseif($m[$i]==10) { echo "Oct"; }
                elseif($m[$i]==11) { echo "Nov"; }
                elseif($m[$i]==12) { echo "Dec"; }
                echo "</option>";
            }
            ?>
        </select>

        <input type="checkbox" name="userOnly" value="Yes" <?php if (isset($_GET['userOnly']) == 'Yes') { echo 'checked="checked"'; }?> /> <span style="font-size: 12px;">My stuff only</span>

        <input type="submit" name="formSubmit" value="Submit" />

        </form>
        </div>

        <div style="clear:both;"></div>

        <?php
            if(isset($_GET['formSubmit']))
            {
                $varProjects = $_GET['projects'];
                $varObjects = $_GET['objects'];
                $varYears = $_GET['years'];
                $varMonths = $_GET['months'];
                $userOnly = $_GET['userOnly'];
                $displayMode = $_GET['displayMode'];

                $str = $varProjects.'-'.$varObjects.'-'.$varYears.'-'.$varMonths.'-'.$userOnly.'-'.$displayMode;

                echo '<div class="details">';
                echo 'Viewing ';
                // Objects
                switch ($varObjects) {
                    case "all":
                        echo "<b>All Objects</b>";
                        break;
                    case "pages":
                        echo "<b>Webpages</b>";
                        break;
                    case "saved":
                        echo "<b>Bookmarks</b>";
                        break;
                    case "queries":
                        echo "<b>Searches</b>";
                        break;
                    case "snippets":
                        echo "<b>Snippets</b>";
                        break;
                    case "annotations":
                        echo "<b>Annotations</b>";
                        break;
                }

                echo ' from ';

                //  Projects
                if($varProjects == "all") {
                    echo "<b>All Projects</b>";
                }
                else {
                    echo "<b>".$varProjects."</b>";
                }

                echo ' from ';

                // Months
                switch ($varMonths) {
                    case "all":
                        echo "<b>All Months</b>";
                        break;
                    case 01:
                        echo "<b>Jan</b>";
                        break;
                    case 02:
                        echo "<b>Feb</b>";
                        break;
                    case 03:
                        echo "<b>Mar</b>";
                        break;
                    case 04:
                        echo "<b>Apr</b>";
                        break;
                    case 05:
                        echo "<b>May</b>";
                        break;
                    case 06:
                        echo "<b>Jun</b>";
                        break;
                    case 07:
                        echo "<b>Jul</b>";
                        break;
                    case 08:
                        echo "<b>Aug</b>";
                        break;
                    case 09:
                        echo "<b>Sept</b>";
                        break;
                    case 10:
                        echo "<b>Oct</b>";
                        break;
                    case 11:
                        echo "<b>Nov</b>";
                        break;
                    case 12:
                        echo "<b>Dec</b>";
                        break;
                }

                echo ' ';

                // Years
                if($varYears == "all") {
                    echo "<b>All Years</b>";
                }
                else {
                    echo "<b>".$varYears."</b>";
                }
                echo '</div>';

                echo '<script type="text/javascript">';
                echo 'filterData("'.$str.'")';
                echo '</script>';
            }


        ?>

    </div>

    <div class="right" style="position: fixed; top: 25px; right: 20px;">
			<p class="flip" style="float: right;">
				<?php
				echo "<img src=\"http://".$_SERVER['HTTP_HOST']."/img/".$avatar." width=45 height=45 style=\"vertical-align:middle;border:3px solid #000;\">";
			?><br><img src="assets/img/arrow.png"/></p>
        <div style="clear:both;"></div>
        <div id="panel" class="panel">
            <table>
                <tr>
                    <td valign="top" width="150">
                        <b>Collaborators</b><br/>
                        <a href="../addCollaborator.php">Add</a>
                        <a href="../collaborators.php">View</a><br/>

                        <b>Projects</b>
                        <a href="../createProject.php">Create</a>
                        <a href="../projects.php">Select</a>
                        <a href="../showPublicProjs.php">Join</a>
                    </td>
                    <td valign="top" width="150">
                        <b>Sharing</b>
                        <a href="../showRecommendations.php">Recommendations</a>
                        <a href="../interProject.php">Inter-project</a><br/>

                        <b>Workspace</b>
                        <a href="../etherpad.php">Editor</a>
                        <a href="../files.php">Files</a>
                        <a href="../printreport.php">Print reports</a>
                    </td>
                    <td valign="top" width="150">
                        <b>Settings</b>
                        <a href="../profile.php">Profile</a>
                        <a href="../settings.php">Options</a><br/>

                        <a href="help.php"><font color=green>Help</font></a><br/>
                        <a href="../../login.php?logout=true"><font color=red>Log out</font></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div>
