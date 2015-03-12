// var menuheight = $('.panel').height();

// $('.panel').removeClass('open').css('top','-'+menuheight+'px');

// $('.flip').off().click(function(){
//     $('.panel').stop(false,true).toggleClass('open');
//     if ($('.panel').hasClass('open')) {
//         $('.panel').animate({
//             top: '0px'
//             }, 500);
//     } else {
//         $('.panel').animate({
//             top: '-'+menuheight+'px'
//             }, 500);
//     }
//     return false;
// });

$(document).ready(function(){
$(".flip").click(function(){
    $(".panel").slideToggle("slow");
  });
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

function jumpto(x){

if (document.form1.displayMode.value != "timeline") {
    document.location.href = x
    }

}

//global var
var allVals = [];
var numChecked = 0;

function showList(str) {    
    // on click, check if the str is in array               
    var check = jQuery.inArray(str, allVals);

    // if it is not, add it to array
    if (check == -1) {
        allVals.push(str);         
    }

    // if it is in array already, remove it
    else {
        var index = allVals.indexOf(str);
        allVals.splice(index, 1);
    }

    // send data to php
    $.post("getList.php", { cpages: allVals },
        function(data) {
        $("#results").empty().append(data);
    });    

    // limiting grouping options based on # of checked boxes //

    // count checked variables
    numChecked = $(".checked_pages:checked").size();

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

function removeID(str) {
    // remove value from allVals
    var check = allVals.indexOf(str);
    allVals.splice(check, 1);
    // uncheck
    $('input:checkbox[value="' + str + '"]').prop('checked', false);
    // send data to php
    $.post("getList.php", { cpages: allVals },
        function(data) {
        $("#results").empty().append(data);
    });
}

// show/hide options when method is selected
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
    }); 
});

$(function () {
    //on click on "go", gets value of selected opt
    $("#go").click(function () {     
        var opt = $("#opt option:selected").val();
        var length = $("#summary_opt option:selected").val();
        var numGroups = $("#group_opt option:selected").val();

        // Create a new instance of ladda for the specified button
        var l = Ladda.create( document.querySelector( '.blue' ) );

        // Start loading
        l.start();


        if (opt == "summarize") {
            if (numChecked == 0) {
                alert("Nothing has been selected!");
                l.stop();
            }

            else {
            $.post("summarize.php", {cpages:allVals, n:length},
                function(data) {
                    $.post("http://coagmento.rutgers.edu/coagmentomiddlelayer/index.php?xmldata=" + data,
                        function(response) {                            
                            sendXML(response);
                            l.stop();
                    });
                });
            }
        }

        else {
            //check before submitting to XML
            if (numChecked == 0) {
                alert("Nothing has been selected!");
                l.stop();
            }

            else if (numGroups > numChecked) {
                alert("The number of pages being selected is less than the number of groups being selected.");
                l.stop();
            }

            else {
                $.post("cluster.php", {cpages:allVals, n: numGroups},
                    function(data) {
                        $.post("http://coagmento.rutgers.edu/coagmentomiddlelayer/index.php?xmldata=" + data,
                            function(response) {                                
                                sendXML(response);
                                l.stop();
                        });
                });
            }
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
    $.post("parseXML.php", {xmldata:response},
        function(data) {
            $("#xml_response").empty().append(data);        
        });
}

function showDetails(str)
{
if (str=="")
  {
  document.getElementById("intro").innerHTML="";
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
    document.getElementById("intro").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("intro").innerHTML = '<div style="padding-left: 20px; padding-top: 20px; font-family: arial;"><img src="loading.gif"/></div>'; }
  }
xmlhttp.open("GET","getDetails.php?q="+str,true);
xmlhttp.send();
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

