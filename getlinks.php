<?php

//location of the entries
$entriesdir = "./entries/";
$entryext = ".txt";

if(array_key_exists('y',$_GET)) {
  $y = $_GET['y'];
  //TODO: ensure it's onlt in yyyy format
  if(array_key_exists('m',$_GET)) {
    //get the contents of /year/month/
    $m = $_GET['m'];
    foreach(glob($entriesdir.$y."/".$m."/*") as $d) {
      $entries[] = basename($d,$entryext);
    }
    echo json_encode($entries);
    
  } else {
    //get the contents of /year/
    foreach(glob($entriesdir.$y."/*",GLOB_ONLYDIR) as $m) {
      $months[] = basename($m);
    }
    echo json_encode($months);
  }
}
?>
