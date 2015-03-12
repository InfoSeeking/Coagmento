<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <link rel="stylesheet" title="Standard" href="styles.css" type="text/css" media="screen" />

    <script language="JavaScript" type="text/javascript" src="contentflow.js"></script>
</head>
<body>

<script src="prototype.js" type="text/javascript"></script>
<script type="text/javascript">

var ajax_cf = new ContentFlow('ajax_cf');

function addPictures(t){
        var ic = document.getElementById('itemcontainer');
        var is = ic.getElementsByTagName('img');
        for (var i=0; i< is.length; i++) {
            ajax_cf.addItem(is[i], 'last');
        }
}
function getPictures(n) {
    n = parseInt(n);
    new Ajax.Updater('itemcontainer', "getpics.php?n="+n, {
        onComplete: addPictures
    });
    return false;
}


</script>
    
   <form method="get" onsubmit="return getPictures(document.getElementById('pics').value)">
    <div style="margin: 20px 0 20px  100px">
        Add <input id="pics" type="text" value="3" size="2"/> images to the ContentFlow.
        <input type="submit" value="ok"/>
    </div>
</form>
<a href="http://www.jacksasylum.eu/ContentFlow/getpics.php">download</a>


<div id="ajax_cf" class="ContentFlow">
    <div class="flow"> </div>
    <div class="scrollbar"><div class="slider"><div class="position"></div></div></div>
</div>
<div id="itemcontainer" style="height: 0px; width: 0px; visibility: hidden"></div>


</body>
</html>
