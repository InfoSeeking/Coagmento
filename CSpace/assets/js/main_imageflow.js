//fancybox pop-up
$(document).ready(function() {
	$(".various").fancybox({
		maxWidth	: 700,
		maxHeight	: 800,
		autoSize	: false,		
		fitToView	: false,
		autoCenter  : true,
		width		: '80%',
		height		: '80%',
		openEffect	: 'none',
		closeEffect	: 'none',
		type		: 'ajax'
	});

});

//top right slide toggle
// $(document).ready(function(){
// 	$(".flip").click(function(){
// 		$(".panel").slideToggle("slow");
// 	  });
// });

//new fancybox menu
$(document).ready(function(){
	$(".flip").fancybox();
});

function filterData(str) {
$.get("services/filterData.php", { q: str },
        function(data) {
        $("#impress").empty().append(data);
    });
}

//javascript to jump to different display modes on dropdown menu
function jumpto(x){

if (document.form1.displayMode.value != "3D") {
    document.location.href = x
    }

}