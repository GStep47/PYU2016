<?php
session_start();

require 'html_head.inc.php';
require 'login_bar_all.inc.php';
require 'banner.inc.php';
require 'navbar.inc.php';
require 'begin_content.inc.php';

$bettype["AP"] = "away pointspread";
$bettype["HP"] = "home pointspread";
$bettype["AM"] = "away money line";
$bettype["HM"] = "home pointspread";
$bettype["OV"] = "over";
$bettype["UN"] = "under";

// connect to database
require 'db_connect.inc.php'; 

foreach ($_POST as $name=>$value) { 

// split $name into week# and bet type
$thesplit = explode (",", $name, 2);

// query database for existing score
$thequery1 = "SELECT " . $thesplit[1] . ", HOME, AWAY FROM pyu_games WHERE GID=" .chr(39) . $thesplit[0] . chr(39) . chr(59);
$result1 = mysql_query($thequery1);
$existingscore = mysql_fetch_row($result1);

// compare existing score to value from form
if ($existingscore[0] !== $value AND $value != "") {

//validate - FIGURE OUT WHY THIS DOESN'T WORK
//if ($thesplit[1] == "AM" OR $thesplit[1] == "HM") {
// $value = filter_var($value, FILTER_SANITIZE_INT);
//} else {
// $value = filter_var($value, FILTER_SANITIZE_FLOAT);
//}

//UNEDITED QUERY
//$thequery2 = "UPDATE pyu_games SET " . $thesplit[1] . "=" . chr(34) . $value . chr(34) . " WHERE GID =" . chr(34)  . $thesplit[0] . chr(34) . chr(59);
$thequery2 = "UPDATE pyu_games SET LASTUPDATE=NOW(), " . $thesplit[1] . "=" . chr(34) . $value . chr(34) . " WHERE GID =" . chr(34)  . $thesplit[0] . chr(34) . chr(59);
$result2 = mysql_query($thequery2);

// make text tweaks if necessary
if ($thesplit[1] == "AP" OR $thesplit[1] == "HP" OR $thesplit[1] == "AM" OR $thesplit[1] == "HM") {
 if ($value > 0) {$value = "+" . $value;}
 if ($existingscore[0] > 0) {$existingscore[0] = "+" . $existingscore[0];}
}

  if ($existingscore[0] == "") {
     switch ($thesplit[1]) {
      case "AP":
         echo "\n<p>Set opening line: " . $existingscore[1] . " " . $value . " vs. " . $existingscore[2] . ".</p>";
         break;
      case "HP":
         echo "\n<p>Set opening line: " . $existingscore[2] . " " . $value . " vs. " . $existingscore[1] . ".</p>";
         break;
      case "AM":
         echo "\n<p>Set opening money line: " . $existingscore[1] . " " . $value . " vs. " . $existingscore[2] . ".</p>";
         break;
      case "HM":
         echo "\n<p>Set opening money line: " . $existingscore[2] . " " . $value . " vs. " . $existingscore[1] . ".</p>";
         break;
      case "OV":
         echo "\n<p>Set opening Over at " . $value . " for " . $existingscore[1] . "-" . $existingscore[2] . " game.</p>";
         break;
      case "UN":
         echo "\n<p>Set opening Under at " . $value . " for " . $existingscore[1] . "-" . $existingscore[2] . " game.</p>";
         break;
     }
  //  echo "\n<p>Set opening line for " . $bettype[$thesplit[1]] . " at " . $value . " for " . $existingscore[1] . "-" . $existingscore[2] . " game.</p>";
  } else {
     switch ($thesplit[1]) {
      case "AP":
         echo "\n<p>Changed " . $existingscore[1] . " point spread from " . $existingscore[0] . " to " . $value  . " vs. " . $existingscore[2] . ".</p>";
         break;
      case "HP":
         echo "\n<p>Changed " . $existingscore[2] . " point spread from " . $existingscore[0] . " to " . $value  . " vs. " . $existingscore[1] . ".</p>";
         break;
      case "AM":
         echo "\n<p>Changed " . $existingscore[1] . " money line from " . $existingscore[0] . " to " . $value  . " vs. " . $existingscore[2] . ".</p>";
         break;
      case "HM":
         echo "\n<p>Changed " . $existingscore[2] . " money line from " . $existingscore[0] . " to " . $value  . " vs. " . $existingscore[1] . ".</p>";
         break;
      case "OV":
         echo "\n<p>Changed Over from " . $existingscore[0] . " to " . $value . " for " . $existingscore[1] . "-" . $existingscore[2] . " game.</p>";
         break;      
      case "UN": 
         echo "\n<p>Changed Under from " . $existingscore[0] . " to " . $value . " for " . $existingscore[1] . "-" . $existingscore[2] . " game.</p>";
         break;
     }
  }
} //end if

} //end foreach

mysql_close();
?>

<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
</div>
</body>