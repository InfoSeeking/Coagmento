$(document).ready(function() {



    $( "#pid" ).click(function() {
			console.log("clicked");
			var projectid = $("#projectid").val()
			if(projectid === ''){
					$('#error').show();
				}
			else {
					chrome.storage.local.set({ "projectid": parseInt(projectid) }, function(){
							    //  Data's been saved boys and girls, go on home
								console.log("done");
								$('#suc').show()
								
					 });
							//

			}
    	});





});
