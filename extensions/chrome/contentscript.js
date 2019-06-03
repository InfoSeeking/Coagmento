var click_x = '';
var click_y = '';
var buttonsPressed = '';
var shiftPressed = '';

var dblclick_x = '';
var dblclick_y = '';
var dblbuttonsPressed = '';

var scrollpos = '';

var keystroke = '';

function saveclick(e)
{
  click_x = e.pageX;
  click_y = e.pageY;
  buttonsPressed = e.buttons;
}

function savedblclick(e)
{
  dblclick_x = e.pageX;
  dblclick_y = e.pageY;
  dblbuttonsPressed = e.buttons;
}

function savescroll(e)
{
  scrollpos = e.target.scrollTop;
}

function savekeypress(e)
{
  scrollpos = e.code;
}

//window.onload?
document.onclick = saveclick;
document.ondblclick = savedblclick;
document.onscroll = savescroll;
document.onkeypress = savekeypress;
