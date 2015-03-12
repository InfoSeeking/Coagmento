<?php
  require_once "src/phpfreechat.class.php"; // adjust to your own path
  $params["serverid"] = md5(__FILE__);
  session_start();  
  if(isset($_SESSION['userName']))
  	$params["nick"] = $_SESSION['userName']; //$_POST['nickname'];// it can be useful to take nicks from a database
  $params["title"] = "Coagmento";
  $params["display_ping"] = FALSE;
  $params["displaytabclosebutton"] = FALSE;
  $params["showwhosonline"] = FALSE;
  //$params["btn_sh_whosonline"] = FALSE;
  $params["displaytabimage"]= FALSE;
  $params["height"]= "180px";
  $params["startwithsound"] = TRUE;
  $params["max_text_len"] = 5000;
  //$params["container_type"]= "Mysql";
  $params["date_format"] = "m/d/Y";
  $params["time_format"] = "H:i";
  $params["short_url_width"] = 20;
  $params["showsmileys"] = FALSE;
  //$params["container_cfg_mysql_password"] = "sa";
  $params["dyn_params"] = array("nick"); 
  $params["max_msg"] = 0;
  $params["max_nick_len"]   = 20;
  //$params["debug"]          = true;
  $params['admins'] = array('admin'  => 'soportechat');
  $params['skip_proxies'] = array('censor','noflood');
  $chat = new phpFreeChat($params);
   
  ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <title>Coagmento Chat</title>
    </head>
 
    <body>
      <?php $chat->printChat();?>
	<div id="bottomSidebar">
		<p>Hello World</p>
	</div>
    </body>
  </html>
