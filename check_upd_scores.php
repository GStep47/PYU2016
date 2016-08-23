<?php
session_start();

require 'html_head.inc.php';
require 'login_bar_all.inc.php';
require 'banner.inc.php';
// CHANGE LATER TO HAVE DIFFERENT NAVBARS
require 'navbar.inc.php';
require 'begin_content.inc.php';

echo "<h4>Please review scores for accuracy! If any errors please <a href=" . chr(39) . "mailto:angerthespy@gmail.com?subject=Score Correction" . chr(39) . ">email GarySJ</a>.</h4>";

// connect to database
require 'db_connect.inc.php'; 

foreach ($_POST as $name=>$value) { 

// split $name into week# and what is being updated
$thesplit = explode (",", $name, 2);

//validate
$value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);

//account for non-entered or incorrectly entered items
if ($value == "" OR $value>99 OR $value < 0) {
$value = "NULL";
} else {
//put quote marks around score
$value = chr(34) . $value . chr(34); 
}

$thequery = "UPDATE pyu_games SET " . $thesplit[1] . "=" . $value . " WHERE GID =" . chr(34) . $thesplit[0] . chr(34) . chr(59);
// execute UPDATE statement
$result = mysql_query($thequery);
// query DB for team names

$thequery2 = "SELECT HOME, HOMESCORE, AWAY, AWAYSCORE, GID FROM pyu_games  WHERE ISNULL(HOMESCORE)=FALSE AND GID=" . chr(39) . $thesplit[0] . chr(39) . chr(59);
$result2 = mysql_query($thequery2);
$teamtext = mysql_fetch_row($result2);

//print results
if ($teamtext[1] < $teamtext [3]) {
// the away team won
echo "\n<p>" . $teamtext[2] . " " . $teamtext[3] . ", " . $teamtext[0] . " " . $teamtext[1]. "</p>";
} elseif  ($teamtext[1] > $teamtext [3]) {
// the home team won
echo "\n<p>" . $teamtext[0] . " " . $teamtext[1] . ", " . $teamtext[2] . " " . $teamtext[3] . "</p>";
}

} //end foreach

mysql_close();
?>

<!-- PAGE-SPECIFIC CONTENT ENDS HERE -->
<p><a href="update_scores.php">Enter More Game Results</a></p>
</div>
</body>