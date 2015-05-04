<table class='questionnaires'>
  <th>Questionnaires</th><th>Start Date</th><th>End Date</th>
<?php
/* renders questionaires table */
$questionnaires = array();
$a = array(array("first",1),array("second",2),array("third",3));
$cxn = Connection::getInstance();
$questionnaire = Questionnaires::getInstance();
$userID = $base->getUserID();
$projectID = $base->getProjectID();
foreach($a as $v){
  $word = $v[0];
  $num = $v[1];
  $r = $cxn->commit("SELECT I.questionnaire".(string)$num."start as questionnaire".(string)$num."start,I.questionnaire".(string)$num."end as questionnaire".(string)$num."end FROM recruits R,instructors I WHERE R.userID='$userID' AND R.instructorID=I.instructorID");
  $line = mysql_fetch_array($r,MYSQL_ASSOC);
  $startdatestr = date("M d",strtotime($line["questionnaire".(string)$num."start"]));
  $enddatestr = date("M d",strtotime($line["questionnaire".(string)$num."end"])-86400);
  if($word =='third' && ($questionnaire->isQuestionnaireComplete("spring2015-midtask-third",array("$userID","$projectID"),array('userID','projectID'),'questionnaire_midtask_third')
  && $questionnaire->isQuestionnaireComplete("spring2015-midtask-third-parttwo",array("$userID","$projectID"),array('userID','projectID'),'questionnaire_midtask_third_parttwo'))){
    $questionnaires[$num] = array("complete",$startdatestr,$enddatestr,$word);
  }else if($word != 'third'  &&
  ($questionnaire->isQuestionnaireComplete("spring2015-midtask-$word",array("$userID","$projectID"),array('userID','projectID'),"questionnaire_midtask_$word"))){
    $questionnaires[$num] = array("complete",$startdatestr,$enddatestr,$word);
  }else if(strtotime($line["questionnaire".(string)$num."start"]) - time() >=0){
    $questionnaires[$num] = array("early",$startdatestr,$enddatestr,$word);
  }else if(time() - strtotime($line["questionnaire".(string)$num."end"]) >= 0){
    $questionnaires[$num] = array("late",$startdatestr,$enddatestr,$word);
  }else if(time() - strtotime($line["questionnaire".(string)$num."end"]) >= -259200){
    $questionnaires[$num] = array("warning",$startdatestr,$enddatestr,$word);
  }else{
    $questionnaires[$num] = array("okay",$startdatestr,$enddatestr,$word);
  }
}

foreach($questionnaires as $k=>$v){
  $status = $v[0];
  $start = $v[1];
  $end = $v[2];
  $url = "../instruments/questionnaire_" . $v[3] . ".php";
  $text = sprintf("Questionnaire %s", $k);
  if($status == "warning" || $status == "okay"){
    //show link
    $text = sprintf("<a href='%s' style=\"color:blue;\">%s</a>", $url, $text);
  }

  printf("<tr class='%s'><td>%s</td><td>%s</td><td>%s</td></tr>", $status, $text, $start, $end);
}
?>
</table>
