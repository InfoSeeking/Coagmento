<!-- Getting year -->
<script language="javascript" type="text/javascript">
<!-- 
//Browser Support Code
function filterYear(){
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			var ajaxDisplay = document.getElementById('monthDiv');
			ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
		else {
			document.getElementById("monthDiv").innerHTML = 'Loading..';
		}
	}
	var year = document.getElementById('year').value;
	var queryString = "?year=" + year;
	ajaxRequest.open("GET", "getYear.php" + queryString, true);
	ajaxRequest.send(null); 
}

function filterMonth(){
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			var ajaxDisplay = document.getElementById('dayDiv');
			ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
		else {
			document.getElementById("dayDiv").innerHTML = 'Loading..';
		}
	}
	var year = document.getElementById('month').value;
	var queryString = "?month=" + year;
	ajaxRequest.open("GET", "getMonth.php" + queryString, true);
	ajaxRequest.send(null); 
}

//-->
</script>