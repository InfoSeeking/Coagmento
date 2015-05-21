var { ActionButton } = require('sdk/ui/button/action');
var { Toolbar } = require("sdk/ui/toolbar");
var { Frame } = require("sdk/ui/frame");

var home = ActionButton({
  id: "home",
  label: "Home",
  icon: {
    "16" : "./icons/home.png"
  },
  hidden: false
});

var toolbar = Toolbar({
  title: "Coagmento Toolbar",
  items: [home]
});
