// on page load
$(document).ready(function(){
    //if flip panel is clicked, slide toggle slowly
    $(".flip").click(function(){
        $(".panel").slideToggle("slow");
      });

    //hide search analysis section
    $("#list").hide();
});

function filterData(str)
{
if (str=="")
  {
  document.getElementById("box_left").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("box_left").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("box_left").innerHTML = '<img src="loading.gif"/>'; }
  }
xmlhttp.open("GET","filterData.php?q="+str,true);
xmlhttp.send();
}

// if interface dropdown changes, re-direct to corresponding URL
function jumpto(x){
if (document.form1.displayMode.value != "timeline") {
    document.location.href = x
    }

}

//global variables for POST requests
var allVals = [];
var numChecked = 0;

// triggers when page is checked on left side, str is pageID
function showList(str) {    
    // clear instructions
    $("#clearthis").empty();
    // show search analysis section
    $("#list").show();

    // on click, check if the pageID is in array               
    var check = jQuery.inArray(str, allVals);

    // if it is not, add it to array
    if (check == -1) {
        allVals.push(str);         
    }

    // if it is in array already, user is unchecking; therefore, remove it
    else {
        var index = allVals.indexOf(str);
        allVals.splice(index, 1);
        numChecked = numChecked - 1;
    }

    // operator selection
    var opt = $("#opt").val();

    // post allVals array and operator to get pages/thumbnails list through PHP
    // interface change depends on operator variable
    $.post("getList.php", { cpages: allVals, operator: opt},
        //on success, refresh the list (basically, clear and append results to div)
        function(data) {
        $("#results").empty().append("<h3>Your Selected Pages</h3>");
        $("#results").append(data);
    });    

    limitGrouping();
}    

function removeID(str) {
    numChecked = numChecked - 1;
    var opt = $("#opt").val();

    // remove value from allVals
    var check = allVals.indexOf(str);
    allVals.splice(check, 1);
    // uncheck
    $('input:checkbox[value="' + str + '"]').prop('checked', false);
    
    limitGrouping();

    // send data to php
    $.post("getList.php", { cpages: allVals, operator: opt},
        function(data) {
            $("#results").empty().append("<h3>Your Selected Pages</h3>");
            $("#results").append(data);
    });

}

// limiting grouping # option based on # of checked boxes //
function limitGrouping() {
    // count checked variables
    numChecked = $(".checked_pages:checked").size();

    // if more than 10 h
    if (numChecked > 10) {
        $("#group_opt option").attr("disabled", false);
    }

    else if (numChecked <= 10) {
        $("#group_opt option").attr("disabled", true);

        //enable options
        for (var i = 2; i <= numChecked; i++) {
            $("#group_opt option[value='" + i + "']").attr("disabled", false);
        }

    }
}

// when drop-down menu changes
$(document).ready(function() {
    $("#opt").change(function () {
        var opt = $("#opt").val();
        
        $("#group_opt").hide();
        $("#summary_opt").hide();
    
        if (opt == "summarize") {
            //display length option
            $("#summary_opt").show();

        }

        if (opt == "cluster") {
            //display # of groups option
            $("#group_opt").show();
        }

        var opt = $("#opt").val();

        // send data to php
        $.post("getList.php", { cpages: allVals, operator: opt},
            function(data) {
            $("#results").empty().append("<h3>Your Selected Pages</h3>");
            $("#results").append(data);
        });  
    }); 
});

$(function () {
    //on click on "go", gets value of selected opt
    $("#go").click(function () {     
        var opt = $("#opt option:selected").val();
        var length = $("#summary_opt option:selected").val();
        var numGroups = $("#group_opt option:selected").val();
        $("#xml_response").empty();
        // Create a new instance of ladda for the specified button
        // var l = Ladda.create( document.querySelector( '#go' ) );
         var l = Ladda.create( document.querySelector( '#go' ) );

        // start spinning wheel
        l.start();

        if (opt == "summarize") {
            if (numChecked == 0) {
                alert("Nothing has been selected!");
                // stop spinning wheel
                l.stop();
            }

            else {
            $.post("iris/summarize.php", {cpages:allVals, n:length},
                function(data) {
                    $.post("http://iris.comminfo.rutgers.edu/", {xmldata: data},
                        function(response) {                            
                            sendXML(response);
                            l.stop();
                    });
                });
            }
        }

        else if (opt == "cluster") {
            //check before submitting to XML
            if (numChecked == 0) {
                alert("Nothing has been selected!");
                l.stop();
            }

            else if (numGroups > numChecked) {
                alert("The number of pages being selected (" + numChecked + ") is less than the number of groups (" + numGroups + ") being selected.");
                l.stop();
            }

            else {
                $.post("iris/cluster.php", {cpages:allVals, n: numGroups},
                    function(data) {
                        $.post("http://iris.comminfo.rutgers.edu/", {xmldata: data},
                            function(response) {                      
                                sendXML(response);
                                l.stop();
                        });
                });
            }
        }

        else if (opt == "rank") {
            if (numChecked == 0) {
                alert("Nothing has been selected!");
                l.stop();
            }

            else if (numChecked == 1) {
                alert("Cannot rank with only one document selected.");
                l.stop();
            }

            else {
            $.post("iris/ranking.php", {cpages:allVals},
                function(data) {
                    $.post("http://iris.comminfo.rutgers.edu/", {xmldata: data},
                        function(response) {                            
                            sendXML(response);
                            l.stop();
                    });
                });
            }
        }
        // if operator sleection is empty
        else {
            alert("No operator has been selected!");
            l.stop();
        }
    });
});

$(function () {
    $('#clear').click(function() {
        //empties list and checked items
        $("#xml_response").empty();
        $("#results").empty();
        $('input:checkbox').removeAttr('checked');
        allVals.length = 0;
        numChecked = 0;
    }); 
});

function sendXML(response) {
    var opt = $("#opt option:selected").val();
    
    if (opt == "summarize") {
        var x = $("#summary_opt option:selected").val();
    }

    else if (opt == "rank") {
        var x = allVals[0];
    }

    $.post("services/parseXML.php", {xmldata:response, x:x},
        function(data) {
            $("#xml_response").append("<h3>Results</h3>" + data);      
        });
}

function showDetails(str) {
$.post("getDetails.php?q=" + str,
    function(data) {
        $("#clearthis").empty();
        $("#details").html(data);
    });
}

// Reveal Javascript (Modal box)
$(document).ready(function(){
    //checks for cookie
    if (!$.cookie("tutorial")) { 
        $('#myModal').foundation('reveal', 'open');
    }

    $('#close-box').click(function() {
        $('#myModal').foundation('reveal', 'close');
    }); 
}); 

function validate(){
    var dismiss = document.getElementById('dismiss');
    if (dismiss.checked) {
        $.cookie("tutorial", 1, {expires: 365});
    }
    else {
        $.removeCookie('tutorial');
    }
}

