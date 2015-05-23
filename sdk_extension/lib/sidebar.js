var globalURL = 'http://coagmento.org/';
//var globalURL = 'http://localhost:8888/';

var sidebar_id = 'coagmento-sidebar';

function populateSidebar() {
    console.log("HELLO SIDEBAR!");
    var sidebar = top.document.getElementById("sidebar-object");
//        var urlplace = "hello.html";
    var urlplace = globalURL+"/loginOnSideBar.php";
    console.log(sidebar);
    sidebar.setAttribute("data", urlplace);
    console.log(sidebar.getAttribute("src"));

    console.log("GOODBYE SIDEBAR!"+src);
    
}
