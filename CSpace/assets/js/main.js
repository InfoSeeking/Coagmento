//fancybox pop-up
$(document).ready(function() {
    $(".various").fancybox({
        maxWidth    : 500,
        maxHeight   : 425,
        autoSize    : false,        
        fitToView   : false,
        autoCenter  : true,
        width       : '100%',
        height      : '100%',
        openEffect  : 'none',
        closeEffect : 'none',
        type        : 'ajax'
    });
});

$(document).ready(function(){
$(".flip").click(function(){
	$(".panel").slideToggle("slow");
  });
});

function filterData(str) {
$.get("services/filterData.php", { q: str },
        function(data) {
        $("#content").empty().append(data);
    });
}

function jumpto(x){

if (document.form1.displayMode.value != "coverflow") {
    document.location.href = x
    }

}
