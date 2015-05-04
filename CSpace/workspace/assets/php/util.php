<?php


function extend_data($arr, $type){
  $extended = array();
  foreach($arr as $row){
    array_push($extended, array(
      "type" => $type,
      "data" => $row
    ));
  }
  return $extended;
}

/*
@param array $arr1 expected to be array returned by extend_data. E.g. should have "data" field with "timestamp"
@param array $arr2 same as $arr1

Merges both arrays and returns sorted by timestamp
*/
function timestamp_merge($arr1, $arr2){
  $merged = array();
  $i1 = 0;
  $i2 = 0;
  while($i1 < count($arr1) || $i2 < count($arr2)){
    $choice = null;
    if($i1 == count($arr1)){
      $choice = $arr2[$i2];
      $i2++;
    } else if($i2 == count($arr2)){
      $choice = $arr1[$i1];
      $i1++;
    } else {
      $t1 = $arr1[$i1]["data"]["timestamp"];
      $t2 = $arr2[$i2]["data"]["timestamp"];
      if($t1 > $t2){
        $choice = $arr1[$i1];
        $i1++;
      } else {
        $choice = $arr2[$i2];
        $i2++;
      }
    }
    array_push($merged, $choice);
  }
  return $merged;
}

/*
builds array mapping unique sources to their corresponding snippets/bookmarks
*/
function buildSources($bookmarks, $snippets){
  $sources = array();
  foreach($bookmarks as $bookmark){
    $source = $bookmark["data"]["host"];
    if(!$source){
      continue;
    }
    if(!array_key_exists($source, $sources)){
      $sources[$source] = array(
        "bookmarks" => array(),
        "snippets" => array(),
        "source" => $source
      );
    }
    array_push($sources[$source]["bookmarks"], $bookmark);
  }
  foreach($snippets as $snippet){
    $source = $snippet["data"]["host"];
    if(!$source){
      continue;
    }
    if(!array_key_exists($source, $sources)){
      $sources[$source] = array(
        "bookmarks" => array(),
        "snippets" => array(),
        "source" => $source
      );
    }
    array_push($sources[$source]["snippets"], $snippet);
  }
  return extend_data($sources, "source");
}
