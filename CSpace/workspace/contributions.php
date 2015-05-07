<?php
/*
Small script to echo out summary of user contributions
Ensure to require connection and base before including this file.
*/

function getGroupCounts(){
  $cxn = Connection::getInstance();
  $base = Base::getInstance();
  $projectID = $base->getProjectID();
  $group = array();

  $q = "select username FROM users WHERE users.projectID=$projectID ORDER BY username";
  $results = $cxn->commit($q);
  while($row = mysqli_fetch_assoc($results)){
    $group[$row["username"]] = array(
      "bookmarks" => 0,
      "snippets" => 0,
      "searches" => 0
    );
  }

  $q =  "select u.username as username, b.userID, count(b.userID) as count from bookmarks b, users u where b.projectID=$projectID AND b.userID = u.userID group by userID";
  $results = $cxn->commit($q);
  while($row = mysqli_fetch_assoc($results)){
    $group[$row["username"]]["bookmarks"] = $row["count"];
  }
  $q =  "select u.username as username, b.userID, count(b.userID) as count from snippets b, users u where b.projectID=$projectID AND b.userID = u.userID group by userID";
  $results = $cxn->commit($q);
  while($row = mysqli_fetch_assoc($results)){
    $group[$row["username"]]["snippets"] = $row["count"];
  }
  $q =  "select u.username as username, b.userID, count(b.userID) as count from queries b, users u where b.projectID=$projectID AND b.userID = u.userID group by userID";
  $results = $cxn->commit($q);
  while($row = mysqli_fetch_assoc($results)){
    $group[$row["username"]]["searches"] = $row["count"];
  }
  return $group;
}

function printContributionTable(){
  $group = getGroupCounts();
  printf("<table class='contributions'>");
  //print top row
  printf("<tr><th>Stats</th>");
  foreach($group as $user => $data){
    printf("<td>%s</td>", $user);
  }
  printf("</tr>");

  //print bookmarks
  printf("<tr><th>Bookmarks</th>");
  foreach($group as $user => $data){
    printf("<td>%s</td>", $data["bookmarks"]);
  }
  printf("</tr>");

  //snippets
  //print bookmarks
  printf("<tr><th>Snippets</th>");
  foreach($group as $user => $data){
    printf("<td>%s</td>", $data["snippets"]);
  }
  printf("</tr>");

  //searches
  //print bookmarks
  printf("<tr><th>Searches</th>");
  foreach($group as $user => $data){
    printf("<td>%s</td>", $data["searches"]);
  }
  printf("</tr>");
  printf("</table>");
}
