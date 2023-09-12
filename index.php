<?php
//password to access the entries
//change this to whatever you want it to be
$pass = "password";

//ensure proper time zone
//change this to be in your timezome
date_default_timezone_set('UTC');

$entriesdir = "./entries/"; //location of the entries files
$entriesext = ".txt"; //exntension of the entries file

?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Daily Journal</title>
    <link rel="stylesheet" type="text/css" href="./journal.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
<?php
//if a password is set or if the date and entry are set
if((array_key_exists("pass",$_POST) and $_POST['pass']==$pass) or (array_key_exists('date',$_POST) and array_key_exists("entry",$_POST))) {

  //if data is present, write to the file
  if(array_key_exists("date",$_POST) and array_key_exists("entry",$_POST)) {

    //TODO: clean up inputs
    $date = $_POST['date'];
    $entry = $_POST['entry'];

    //TODO: have it create a new month and/or year directory too

    //ensure that the date is correctly formatted
    if(DateTime::createFromFormat('Y-m-d', $date)) {
      //if so, then write to the file
      $y = substr($date,0,4);
      $m = substr($date,5,2);
      if(!is_dir($entriesdir.$y."/".$m)) {
        mkdir($entriesdir.$y."/".$m,0777,true);
      }
      $entryfile = $entriesdir.$y."/".$m."/".$date.$entriesext;
      $wrote = file_put_contents($entryfile,$entry);
      
      if($wrote) {
        echo '<p style="color: green;">Saved entry '.$date.'</p>';
      } else {
        echo '<p style="color: red;">Error saving entry '.$date.'</p>';
      }

    } else {
      echo "<p>Incorrectly formatted date</p>";
      var_dump($date);
      exit();
    }

  }
  
  //load the list of entries (after the write occurs)
  //$years = glob($entriesdir."*",GLOB_ONLYDIR);
  foreach(glob($entriesdir."*",GLOB_ONLYDIR) as $e) {
    $years[] = basename($e);
  }

  if(array_key_exists('date',$_GET)) {
    $displayeddate = $_GET['date'];
  } else {
    $displayeddate = date("Y-m-d");
  }
  $y = substr($displayeddate,0,4);
  $m = substr($displayeddate,5,2);

  //read the file if it exists, else use the placeholder text

  $displayedentry = "Today I ";
  if(in_array($y,$years)) {
    foreach(glob($entriesdir.$y."/"."*",GLOB_ONLYDIR) as $e) {
      $months[] = basename($e);
    }
    if(in_array($m,$months)) {
      $dates = glob($entriesdir.$y."/".$m."/"."*");
      $entryfile = $entriesdir.$y."/".$m."/".$displayeddate.$entriesext;
      if(file_exists($entryfile)) {
        $displayedentry = file_get_contents($entryfile);
      }
    }
  }
?>
    <form name="journalEntry" method="post">
      <input name="date" type="date" value="<?php echo $displayeddate; ?>" readonly title="Note: if the date is of a previous entry, it will overwrite it">
      <br>
      <p>What did you do today?</p>
      <textarea name="entry" autofocus><?php echo $displayedentry; ?></textarea>
      <br>
      <button type="submit">Done</button>
    </form>
    <div class="break"></div>
    <p><b>Previous Entries</b></p>
    <?php 
    echo '<div id="prevents">';
    echo '<div class="linkwrapper">';
    foreach($years as $y) {
      echo '<p><a href="javascript:;" onclick="getMonth(this);">'.$y.'</a></p><div class="linkwrapper"></div>';
    }
    echo '</div>';
    echo '</div>';

    echo '<script src="./journal.js"></script>';

  } else {
      //no password or incorrect password given
      ?>
    <form name="pw" method="post">
      <p id="passline">Password: <input name="pass" type="password" autofocus><button type="submit" >Submit</button></p>
<?php } ?>
  </body>
</html>
